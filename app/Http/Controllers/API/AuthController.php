<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Method untuk cek NIS.
     */
    public function checkNIS(Request $request)
    {
        $nis = $request->input('nis');

        // Cek apakah NIS ada di tbl_siswa
        $siswa = Siswa::where('nis', $nis)->first();

        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'NIS tidak ditemukan di database siswa.'
            ], 404);
        }

        // Cek apakah NIS sudah digunakan di tbl_user
        $user = User::where('nis', $nis)->first();
        if ($user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun dengan NIS ini sudah terdaftar.'
            ], 409);
        }

        // Jika NIS ada dan belum digunakan
        return response()->json([
            'status' => 'success',
            'message' => 'NIS valid. Silakan lanjutkan ke pendaftaran akun.',
            'siswa' => $siswa
        ], 200);
    }

    /**
     * Method untuk Register.
     */
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'nis' => 'required|exists:tbl_siswa,nis',
            'nama_user' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tbl_users',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $nis = $request->input('nis');

        // Cek apakah NIS sudah digunakan di tbl_user
        $userExists = User::where('nis', $nis)->exists();

        if ($userExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun dengan NIS ini sudah terdaftar.'
            ], 409);
        }

        // Dapatkan siswa berdasarkan NIS
        $siswa = Siswa::where('nis', $nis)->first();

        // Update data siswa jika diperlukan
        $siswa->update([
            'nama_siswa' => $request->input('nama_user'),
            'email' => $request->input('email'),
            'no_hp' => $request->input('no_hp'),
        ]);

        // Buat akun baru di tbl_user
        $user = User::create([
            'nama_user' => $request->input('nama_user'),
            'nis' => $nis,
            'email' => $request->input('email'),
            'no_hp' => $request->input('no_hp'),
            'password' => bcrypt($request->input('password')),
            'role' => 'siswa',
            'id_siswa' => $siswa->id_siswa, // Relasi ke siswa
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Akun berhasil didaftarkan dan data siswa diperbarui.',
            'user' => $user,
            'siswa' => $siswa
        ], 201);
    }

    /**
     * Method untuk login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Cek apakah email ada di database
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role !== 'siswa') {
                return response()->json(['success' => false, 'message' => 'Anda Bukan Siswa.'], 403);
            }

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil.',
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email atau password salah.',
        ], 401);
    }

    /**
     * Method untuk logout.
     */
    public function logout(Request $request)
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil.',
        ], 200);
    }

    /**
     * Method untuk get Profile User.
     */
    public function getProfile(Request $request)
    {
        // Pengecekan apakah user yang login memiliki peran 'siswa'
        if (auth()->user()->role !== 'siswa') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Jika peran user adalah 'siswa', kembalikan data profil
        $user = User::where('id_user', auth()->user()->id_user)->with('siswa')->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Data profil berhasil diperbarui.',
            'user' => $user
        ], 200);
    }

}
