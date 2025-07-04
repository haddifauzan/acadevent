<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hari;
use Illuminate\Support\Facades\Artisan;

class HariController extends Controller
{
    // Menampilkan daftar hari
    public function index()
    {
        $hari = Hari::all(); // Ambil semua data hari
        return view('index_hari', compact('hari'));
    }

    // Menambah hari
    public function store(Request $request)
    {
        $request->validate([
            'nama_hari' => 'required|string|max:255|unique:tbl_hari,nama_hari',
        ]);

        Hari::create([
            'nama_hari' => $request->nama_hari,
        ]);

        // Jalankan command hanya jika aplikasi tidak dalam mode pengujian
        if (!app()->runningUnitTests()) {
            Artisan::call('acara:update-status');
        }

        return redirect()->route('acara_sekolah.index')->with('success', 'Hari berhasil ditambahkan!');
    }

    // Menghapus hari
    public function destroy($id)
    {
        $hari = Hari::findOrFail($id);

        // Hapus data terkait di tbl_acara_sekolah
        $hari->acaraSekolah()->delete();

        // Hapus data di tbl_hari
        $hari->delete();

        return redirect()->route('acara_sekolah.index')
            ->with('success', 'Hari dan acara terkait berhasil dihapus!');
    }
}
