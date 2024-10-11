<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hari;

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

        return redirect()->route('acara_sekolah.index')->with('success', 'Hari berhasil ditambahkan!');
    }

    // Menghapus hari
    public function destroy($id)
    {
        $hari = Hari::findOrFail($id);
        $hari->delete();

        return redirect()->route('acara_sekolah.index')->with('success', 'Hari berhasil dihapus!');
    }
}

