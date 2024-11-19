<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use App\Models\AcaraSekolah;
use App\Models\PesertaAcara;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // Statistik untuk cards
        $stats = [
            'total_acara' => Acara::count(),
            'total_siswa' => Siswa::count(),
            'acara_aktif' => Acara::where('status_acara', 'aktif')->count(),
            'total_peserta' => PesertaAcara::count(),
        ];

        // Data untuk grafik statistik peserta 6 bulan terakhir
        $monthlyStats = Acara::select(
            DB::raw('DATE_FORMAT(tanggal_acara, "%Y-%m") as month'),
            DB::raw('COUNT(DISTINCT tbl_acara.id_acara) as total_acara'),
            DB::raw('COUNT(DISTINCT tbl_peserta_acara.id_user) as total_peserta')
        )
        ->leftJoin('tbl_peserta_acara', 'tbl_acara.id_acara', '=', 'tbl_peserta_acara.id_acara')
        ->where('tanggal_acara', '>=', Carbon::now()->subMonths(2))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Data untuk pie chart distribusi jenis acara
        $jenisAcaraStats = Acara::select('jenis_acara', DB::raw('count(*) as total'))
            ->groupBy('jenis_acara')
            ->get();

        // Acara terbaru
        $acaraTerbaru = Acara::with(['users' => function($query) {
                $query->select('tbl_users.id_user');
            }])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Acara sekolah hari ini
        $acaraSekolahHariIni = AcaraSekolah::with('hari')
            ->where('status_acara', 'aktif')
            ->whereHas('hari', function($query) {
                $query->where('nama_hari', Carbon::now()->locale('id')->dayName);
            })
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyStats',
            'jenisAcaraStats',
            'acaraTerbaru',
            'acaraSekolahHariIni'
        ));
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
