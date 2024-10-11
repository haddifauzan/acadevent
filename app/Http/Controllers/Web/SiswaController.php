<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil keyword pencarian jika ada
        $search = $request->input('search');

        // Query data siswa
        $siswa = Siswa::all();

        // Kirim data siswa ke view
        return view('admin.data-siswa.index', compact('siswa'));
    }

    // Menyimpan siswa baru
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:tbl_siswa,nis',
            'nama_siswa' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'email' => 'required|email',
            'no_hp' => 'required',
        ]);

        Siswa::create($request->all());

        return redirect()->route('data.siswa')->with('success', 'Siswa berhasil ditambahkan');
    }

    // Memperbarui data siswa
    public function update(Request $request, $id)
    {
        $siswa = Siswa::find($id);

        $request->validate([
            'nis' => 'required|unique:tbl_siswa,nis,' . $siswa->id_siswa . ',id_siswa',
            'nama_siswa' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'email' => 'required|email',
            'no_hp' => 'required',
        ]);

        $siswa->update($request->all());

        return redirect()->route('data.siswa')->with('success', 'Siswa berhasil diperbarui');
    }

    // Menghapus siswa
    public function destroy($id)
    {
        $siswa = Siswa::find($id);
        $siswa->delete();

        return redirect()->route('data.siswa')->with('success', 'Siswa berhasil dihapus');
    }

}
