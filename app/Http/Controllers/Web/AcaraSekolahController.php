<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcaraSekolah;
use App\Models\Acara;
use App\Models\Hari;
use Carbon\Carbon;

class AcaraSekolahController extends Controller
{
    // Menampilkan daftar acara sekolah
    public function index()
    {
        // Eager load 'hari' untuk mengurangi query yang berlebihan
        $acaraSekolah = AcaraSekolah::with('hari')->orderBy('waktu_mulai')->get(); 
        $hari = Hari::all(); // Mengambil semua data hari
        return view('admin.acara-pembiasaan.index_acara', compact('acaraSekolah', 'hari'));
    }


    // Menampilkan form tambah acara sekolah
    public function create($id_hari)
    {
        $hari = Hari::findOrFail($id_hari);
        return view('admin.acara-pembiasaan.create_acara', compact('hari', 'id_hari'));
    }

    // Menyimpan acara sekolah baru
    public function store(Request $request)
    {
        // Validasi data, pastikan waktu mulai lebih awal dari waktu selesai
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'id_hari' => 'required|integer|exists:tbl_hari,id_hari', // Pastikan id_hari valid
            'deskripsi' => 'required|string',
            'tingkat' => 'required|string|in:Tingkat 1,Tingkat 2,Tingkat 3,Seluruh Tingkat',
            'jenis_acara' => 'required|string',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai', // Waktu selesai harus setelah waktu mulai
        ]);

        // Simpan data acara
        AcaraSekolah::create([
            'nama_acara' => $request->nama_acara,
            'id_hari' => $request->id_hari,
            'deskripsi' => $request->deskripsi,
            'tingkat' => $request->tingkat,
            'jenis_acara' => $request->jenis_acara,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'status_acara' => 'aktif', // Set status default 'aktif'
        ]);

        return redirect()->route('acara_sekolah.index')->with('success', 'Acara sekolah berhasil ditambahkan!');
    }


    // Menampilkan form edit acara sekolah
    public function edit($id)
    {
        $acaraSekolah = AcaraSekolah::findOrFail($id);
        $hari = Hari::all(); // Untuk pilihan select option
        return view('admin.acara-pembiasaan.edit_acara', compact('acaraSekolah', 'hari'));
    }

    // Update acara sekolah
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'id_hari' => 'required|integer',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
        ]);

        $acaraSekolah = AcaraSekolah::findOrFail($id);
        $acaraSekolah->update([
            'nama_acara' => $request->nama_acara,
            'id_hari' => $request->id_hari,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
        ]);

        return redirect()->route('acara_sekolah.index')->with('success', 'Acara sekolah berhasil diperbarui!');
    }

    // Menghapus acara sekolah
    public function destroy($id)
    {
        // Cari acara berdasarkan ID atau tampilkan 404 jika tidak ditemukan
        $acaraSekolah = AcaraSekolah::findOrFail($id);

        // Hapus data acara
        $acaraSekolah->delete();

        return redirect()->route('acara_sekolah.index')->with('success', 'Acara sekolah berhasil dihapus!');
    }

    public function cancel($id)
    {
        // Cari acara berdasarkan ID
        $acaraSekolah = AcaraSekolah::findOrFail($id);

        // Ubah status acara menjadi "dibatalkan"
        $acaraSekolah->update([
            'status_acara' => 'batal',
        ]);

        return redirect()->route('acara_sekolah.index')->with('success', 'Acara berhasil dibatalkan.');
    }

    public function activate($id)
    {
        // Cari acara berdasarkan ID
        $acaraSekolah = AcaraSekolah::findOrFail($id);

        // Ubah status acara menjadi "aktif"
        $acaraSekolah->update([
            'status_acara' => 'aktif',
        ]);

        return redirect()->route('acara_sekolah.index')->with('success', 'Acara berhasil diaktifkan kembali.');
    }



    public function calendar()
    {
        // Fungsi pembantu untuk memformat acara
        $formatEvent = function ($item, $isAcaraSekolah = false) {
            $event = [
                'title' => $item->nama_acara,
                'description' => $item->deskripsi,
                'jenis_acara' => $item->jenis_acara,
                'tingkat' => $item->tingkat,
                'status_acara' => $item->status_acara,
            ];

            if ($isAcaraSekolah) {
                // Untuk AcaraSekolah, gunakan hari untuk menentukan tanggal
                $hariMapping = [
                    'Senin' => 'Monday', 'Selasa' => 'Tuesday', 'Rabu' => 'Wednesday',
                    'Kamis' => 'Thursday', 'Jumat' => 'Friday', 'Sabtu' => 'Saturday', 'Minggu' => 'Sunday'
                ];
                $nama_hari = $item->hari->nama_hari;
                $hariBahasaInggris = $hariMapping[$nama_hari] ?? null;

                if ($hariBahasaInggris) {
                    $tanggal_acara = \Carbon\Carbon::parse('next ' . $hariBahasaInggris);
                    $event['start'] = $tanggal_acara->format('Y-m-d') . 'T' . $item->waktu_mulai;
                    $event['end'] = $tanggal_acara->format('Y-m-d') . 'T' . $item->waktu_selesai;
                }
            } else {
                // Untuk Acara, gunakan tanggal_acara
                $event['start'] = $item->tanggal_acara . 'T' . $item->waktu_mulai;
                $event['end'] = $item->tanggal_acara . 'T' . $item->waktu_selesai;
            }

            return $event;
        };

        // Ambil dan format data dari AcaraSekolah
        $acaraSekolah = AcaraSekolah::with('hari')->get()->map(function($item) use ($formatEvent) {
            return $formatEvent($item, true);
        });

        // Ambil dan format data dari Acara
        $acara = Acara::all()->map(function($item) use ($formatEvent) {
            return $formatEvent($item);
        });

        // Gabungkan semua acara
        $events = $acaraSekolah->concat($acara);

        // Kirim data ke view
        return view('admin.jadwal-acara.calendar', compact('events'));
    }


}
