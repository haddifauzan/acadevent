<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acara;
use App\Models\AcaraSekolah;
use App\Models\Hari;
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

    public function showUpcomingEvent()
    {
        $currentDateTime = Carbon::now();

        // Ambil acara utama yang akan datang berdasarkan tanggal dan waktu
        $acaraUtama = Acara::where('status_acara', 'aktif')
            ->whereDate('tanggal_acara', '>=', $currentDateTime->toDateString())
            ->orderBy('tanggal_acara')
            ->orderBy('waktu_mulai')
            ->first();

        // Map data hari dan acara sekolah terkait
        $harian = Hari::all();
        $acaraSekolah = AcaraSekolah::all();
        $harianWithAcara = $harian->map(function ($hari) use ($currentDateTime, $acaraSekolah) {
            $acaraUntukHari = $acaraSekolah->where('id_hari', $hari->id_hari)->values();
            $tanggalAcara = null;

            $daysOfWeek = [
                'Senin' => Carbon::MONDAY,
                'Selasa' => Carbon::TUESDAY,
                'Rabu' => Carbon::WEDNESDAY,
                'Kamis' => Carbon::THURSDAY,
                'Jumat' => Carbon::FRIDAY,
                'Sabtu' => Carbon::SATURDAY,
                'Minggu' => Carbon::SUNDAY,
            ];

            if (array_key_exists($hari->nama_hari, $daysOfWeek)) {
                $diff = $daysOfWeek[$hari->nama_hari] - $currentDateTime->dayOfWeek;
                if ($diff <= 0) {
                    $diff += 7;
                }
                $tanggalAcara = $currentDateTime->copy()->addDays($diff)->format('Y-m-d');
            }

            if ($acaraUntukHari->isNotEmpty() && $tanggalAcara) {
                $tanggalAcara = Carbon::parse($tanggalAcara)->format('d M Y');
            }

            $hari->acara_sekolah = $acaraUntukHari;
            $hari->tanggal_acara = $tanggalAcara;

            return $hari;
        })->sortBy(function ($hari) {
            return $hari->tanggal_acara ? Carbon::parse($hari->tanggal_acara)->timestamp : PHP_INT_MAX;
        })->values();

        $acaraSekolahTerdekat = $harianWithAcara->firstWhere('tanggal_acara', '!=', null);

        $acaraTerdekat = null;
        if ($acaraUtama && $acaraSekolahTerdekat) {
            $acaraUtamaDateTime = Carbon::parse($acaraUtama->tanggal_acara);
            $acaraSekolahDateTime = Carbon::parse($acaraSekolahTerdekat->tanggal_acara);

            $acaraTerdekat = ($acaraUtamaDateTime->lte($acaraSekolahDateTime)) ? $acaraUtama : $acaraSekolahTerdekat->acara_sekolah->first();
            if ($acaraTerdekat instanceof AcaraSekolah) {
                $acaraTerdekat->tanggal_acara = $acaraSekolahTerdekat->tanggal_acara;
            }
        } elseif ($acaraUtama) {
            $acaraTerdekat = $acaraUtama;
        } elseif ($acaraSekolahTerdekat) {
            $acaraTerdekat = $acaraSekolahTerdekat->acara_sekolah->first();
            $acaraTerdekat->tanggal_acara = $acaraSekolahTerdekat->tanggal_acara;
        }

        if (!$acaraTerdekat) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada acara yang akan datang',
            ], 404);
        }

        $data = [
            'id_acara' => $acaraTerdekat instanceof Acara ? $acaraTerdekat->id_acara : null,
            'id_acara_sekolah' => $acaraTerdekat instanceof AcaraSekolah ? $acaraTerdekat->id_acara_sekolah : null,
            'nama_acara' => $acaraTerdekat->nama_acara,
            'jenis_acara' => $acaraTerdekat->jenis_acara,
            'tingkat' => $acaraTerdekat->tingkat,
            'deskripsi' => $acaraTerdekat->deskripsi,
            'tanggal_acara' => $acaraTerdekat->tanggal_acara,
            'waktu_mulai' => $acaraTerdekat->waktu_mulai,
            'waktu_selesai' => $acaraTerdekat->waktu_selesai,
            'status_acara' => $acaraTerdekat->status_acara,
            'hari' => ($acaraTerdekat instanceof AcaraSekolah) ? $acaraSekolahTerdekat->nama_hari : null,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Acara Berikutnya Ditemukan',
            'data' => $data,
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
