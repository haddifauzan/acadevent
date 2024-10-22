<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\User;

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

        // Jika id_siswa sudah ada pada tbl_users, maka hapus juga akun siswa tersebut
        $user = User::where('id_siswa', $id)->first();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('data.siswa')->with('success', 'Siswa berhasil dihapus');
    }

}
