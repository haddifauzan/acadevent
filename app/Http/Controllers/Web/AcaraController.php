<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acara;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;


class AcaraController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $acara = Acara::when($search, function ($query, $search) {
                return $query->where('nama_acara', 'like', '%' . $search . '%');
            })
            ->orderBy('tanggal_acara', 'desc')
            ->orderBy('waktu_mulai', 'desc')
            ->paginate(12);

        return view('admin.acara-umum.index', compact('acara'));
    }

    // Menampilkan form untuk menambah acara
    public function create()
    {
        return view('admin.acara-umum.create');
    }

    // Menyimpan acara baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_acara' => 'required|date',
            'tempat' => 'required|string|max:255',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'tingkat' => 'required|in:Tingkat 1,Tingkat 2,Tingkat 3,Seluruh Tingkat',
            'jenis_acara' => 'required',
            'kuota_peserta' => 'required|in:tidak terbatas,terbatas',
            'kuota_peserta_terbatas' => 'required_if:kuota_peserta,terbatas|nullable|integer|min:1',
        ]);

        $kuota_peserta = $request->kuota_peserta === 'tidak terbatas' ? null : (int) $request->kuota_peserta_terbatas;
        $kode_kehadiran = $kuota_peserta ? substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5) : null;

        $acara = Acara::create([
            'nama_acara' => $request->nama_acara,
            'penyelenggara' => $request->penyelenggara,
            'tingkat' => $request->tingkat,
            'deskripsi' => $request->deskripsi,
            'tanggal_acara' => $request->tanggal_acara,
            'waktu_mulai' => \Carbon\Carbon::createFromFormat('H:i', $request->waktu_mulai)->format('H:i:s'),
            'waktu_selesai' => \Carbon\Carbon::createFromFormat('H:i', $request->waktu_selesai)->format('H:i:s'),
            'tempat' => $request->tempat,
            'jenis_acara' => $request->jenis_acara,
            'kuota_peserta' => $kuota_peserta,
            'kode_kehadiran' => $kode_kehadiran,
            'status_acara' => 'aktif', // Assuming a default status for new events
        ]);

        // Create notification
        Notifikasi::create([
            'nama_acara' => $acara->nama_acara,
            'pesan' => "Acara baru telah ditambahkan: {$acara->nama_acara}",
            'link_acara' => "acara/{$acara->id_acara}",
        ]);

        return redirect()->route('acara')->with('success', 'Acara berhasil ditambahkan dan notifikasi telah dibuat!');
    }

    // Menampilkan form untuk mengedit acara
    public function edit($id)
    {
        $acara = Acara::findOrFail($id);
        return view('admin.acara-umum.edit', compact('acara'));
    }

    // Menyimpan perubahan data acara
    public function update(Request $request, $id)
    {
        $acara = Acara::findOrFail($id);

        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_acara' => 'required|date',
            'tempat' => 'required|string|max:255',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'tingkat' => 'required|in:Tingkat 1,Tingkat 2,Tingkat 3,Seluruh Tingkat',
            'jenis_acara' => 'required',
            'kuota_peserta' => 'required|in:tidak terbatas,terbatas',
            'kuota_peserta_terbatas' => 'required_if:kuota_peserta,terbatas|nullable|integer|min:1',
        ]);

        $kuota_peserta = $request->kuota_peserta === 'tidak terbatas' ? null : (int) $request->kuota_peserta_terbatas;

        $acara->update([
            'nama_acara' => $request->nama_acara,
            'penyelenggara' => $request->penyelenggara,
            'tingkat' => $request->tingkat,
            'deskripsi' => $request->deskripsi,
            'tanggal_acara' => $request->tanggal_acara,
            'waktu_mulai' => \Carbon\Carbon::createFromFormat('H:i', $request->waktu_mulai)->format('H:i:s'),
            'waktu_selesai' => \Carbon\Carbon::createFromFormat('H:i', $request->waktu_selesai)->format('H:i:s'),
            'tempat' => $request->tempat,
            'jenis_acara' => $request->jenis_acara,
            'kuota_peserta' => $kuota_peserta,
        ]);

        return redirect()->route('acara')->with('success', 'Acara berhasil diperbarui.');
    }

    // Menghapus acara
    public function destroy($id)
    {
        $acara = Acara::findOrFail($id);
        $acara->delete();
        return redirect()->route('acara')->with('success', 'Acara berhasil dihapus!');
    }

    public function cancel($id)
  {
      $acara = Acara::findOrFail($id);
      $acara->status_acara = 'batal';
      $acara->save();

      return redirect()->back()->with('success', 'Acara berhasil dibatalkan.');
  }
}
