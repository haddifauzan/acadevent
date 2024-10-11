<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function show_login()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Menangani 'remember me' option
        $remember = $request->has('remember-me') ? true : false;

        // Mencoba autentikasi pengguna
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $remember)) {
            // Jika autentikasi berhasil
            $request->session()->regenerate();

            // Redirect ke halaman dashboard admin
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil');
        }

        // Jika login gagal
        return back()->withErrors([
            'errors' => 'Email atau password salah.',
        ])->withInput($request->except('password')); // password tidak disimpan ke session
    }

    
    public function logout(Request $request)
    {
        // Mencabut session dan logout
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil');
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Ambil query pencarian

        // Query untuk mencari berdasarkan nama_user atau email
        $users = User::query()
            ->when($search, function($query, $search) {
                $query->where('nama_user', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(10); // Pagination dengan 10 item per halaman

        return view('admin.data-user.index', compact('users', 'search'));
    }
}
