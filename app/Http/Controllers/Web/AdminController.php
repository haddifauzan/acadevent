<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(){
        return view('admin.dashboard');
    }

    public function updateProfile(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:15',
            'currentPassword' => 'nullable|string|min:6',
            'newPassword' => 'nullable|string|min:6|confirmed',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Perbarui data profil
        $user->nama_user = $request->input('name');
        $user->email = $request->input('email');
        $user->no_hp = $request->input('phone');

        // Jika ada password yang diubah
        if ($request->filled('currentPassword') && $request->filled('newPassword')) {
            // Cek apakah password lama sesuai
            if (Hash::check($request->input('currentPassword'), $user->password)) {
                // Ubah password baru
                $user->password = Hash::make($request->input('newPassword'));
            } else {
                // Jika password lama salah
                return back()->withErrors(['current_password' => 'Password saat ini salah!'])->withInput();
            }
        }

        // Simpan perubahan
        $user->save();

        return back()->with('success', 'Profile berhasil diupdate!');
    }
}
