<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acara;
use App\Models\PesertaAcara;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AcaraController extends Controller
{
    public function index()
    {
        // Mendapatkan semua acara tanpa pagination
        $acara = Acara::all();

        // Mengirimkan response dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Daftar Acara Ditemukan',
            'data' => $acara
        ], 200);
    }

    public function show($id)
    {
        // Mencari acara berdasarkan ID
        $acara = Acara::withCount('users')->find($id); // Memuat relasi users (peserta) dan menghitung jumlah peserta

        // Jika acara tidak ditemukan, kembalikan response 404
        if (!$acara) {
            return response()->json([
                'success' => false,
                'message' => 'Acara tidak ditemukan',
            ], 404);
        }

        // Kembalikan response dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Acara Ditemukan',
            'data' => $acara,
        ], 200);
    }


    public function registerToEvent(Request $request, $id_acara)
    {
        // Validasi input, termasuk password
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Ambil user yang sedang login
        $user = Auth::user();

        // Verifikasi password user
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah',
            ], 401);
        }

        // Cek apakah acara ada
        $acara = Acara::find($id_acara);
        if (!$acara) {
            return response()->json([
                'success' => false,
                'message' => 'Acara tidak ditemukan',
            ], 404);
        }

        // Cek status acara
        if (!in_array($acara->status_acara, ['aktif'])) {
            return response()->json([
                'success' => false,
                'message' => 'Acara ' . $acara->status_acara . ', tidak dapat mendaftar',
            ], 409);
        }

        // Cek apakah user sudah mendaftar ke acara ini
        $isAlreadyRegistered = PesertaAcara::where('id_acara', $id_acara)
            ->where('id_user', $user->id)
            ->exists();

        if ($isAlreadyRegistered) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar di acara ini',
            ], 409);
        }

        // Cek kuota acara jika ada
        if ($acara->kuota_peserta !== null && $acara->jumlahPeserta() >= $acara->kuota_peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota peserta sudah penuh',
            ], 409);
        }

        // Mendaftarkan user ke acara
        PesertaAcara::create([
            'id_acara' => $id_acara,
            'id_user' => $user->id_user,
            'status_kehadiran' => 'belum hadir',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendaftar ke acara',
        ], 201);
    }

    public function submitKehadiran(Request $request, $id_acara)
    {
        // Validasi input kode_kehadiran
        $request->validate([
            'kode_kehadiran' => 'required|string',
        ]);

        // Temukan acara berdasarkan ID
        $acara = Acara::findOrFail($id_acara);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Periksa apakah user sudah terdaftar sebagai peserta
        $peserta = PesertaAcara::where('id_acara', $acara->id_acara)
                            ->where('id_user', $user->id_user)
                            ->first();

        if (!$peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum mendaftar ke acara ini.',
            ], 403);
        }

        // Ambil waktu saat ini
        $currentTime = Carbon::now();

        // Cek apakah acara sudah dimulai dan belum selesai
        if ($currentTime->between($acara->waktu_mulai, $acara->waktu_selesai)) {
            // Cek apakah kode kehadiran cocok
            if ($request->kode_kehadiran === $acara->kode_kehadiran) {
                // Update status kehadiran menjadi 'hadir'
                $peserta->status_kehadiran = 'hadir';
                $peserta->save();

                return response()->json(['success' => true,'message' => 'Kehadiran berhasil dikonfirmasi.'], 200);
            } else {
                return response()->json(['success' => false,'message' => 'Kode kehadiran salah.'], 400);
            }
        } elseif ($currentTime->greaterThan($acara->waktu_selesai)) {
            // Jika acara sudah selesai dan user belum absen, set status ke 'tidak hadir'
            if (!$peserta->status_kehadiran || $peserta->status_kehadiran === 'belum hadir') {
                $peserta->status_kehadiran = 'tidak hadir';
                $peserta->save();

                return response()->json(['success' => true,'message' => 'Acara sudah selesai. Anda dianggap tidak hadir.'], 200);
            }
        }

        return response()->json(['success' => false, 'message' => 'Kehadiran hanya dapat dikonfirmasi saat acara berlangsung.'], 400);
    }

    public function historyAcara(Request $request)
    {
        // Mendapatkan ID user dari request (atau bisa juga dari autentikasi jika menggunakan Sanctum/JWT)
        $userId = $request->user()->id_user;

        // Query untuk mendapatkan acara yang diikuti oleh user
        $history = PesertaAcara::where('id_user', $userId)
            ->with(['acara' => function($query) {
                $query->select('id_acara', 'nama_acara', 'tanggal_acara', 'status_acara');
            }])
            ->get();

        // Mengirimkan response dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'History Acara Ditemukan',
            'data' => $history
        ], 200);
    }


}
