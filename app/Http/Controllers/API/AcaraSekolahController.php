<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcaraSekolah;
use App\Models\Hari;
use Carbon\Carbon;

class AcaraSekolahController extends Controller
{
    public function index()
    {
        // Ambil acara sekolah yang waktu_mulai >= tanggalSelanjutnya
        $acaraSekolah = AcaraSekolah::with('hari')
            ->orderBy('waktu_mulai')
            ->get();

        // Peta acara sekolah dengan hari
        $acaraSekolahWithHari = $acaraSekolah->map(function ($acaraSekolah) {
            // Dapatkan tanggal untuk hari saat ini
            $tanggalAcara = Carbon::today();
        
            // Cek hari dalam minggu
            $daysOfWeek = [
                'Senin' => Carbon::MONDAY,
                'Selasa' => Carbon::TUESDAY,
                'Rabu' => Carbon::WEDNESDAY,
                'Kamis' => Carbon::THURSDAY,
                'Jumat' => Carbon::FRIDAY,
                'Sabtu' => Carbon::SATURDAY,
                'Minggu' => Carbon::SUNDAY,
            ];
        
            // Cek apakah hari ada dalam array
            if (array_key_exists($acaraSekolah->hari->nama_hari, $daysOfWeek)) {
                // Menentukan selisih hari
                $diff = $daysOfWeek[$acaraSekolah->hari->nama_hari] - $tanggalAcara->dayOfWeek;
                if ($diff <= 0) {
                    // Jika hari yang diinginkan sudah lewat, ambil untuk minggu depan
                    $diff += 7;
                }
        
                // Set tanggal_acara ke tanggal yang sesuai
                $tanggalAcara = $tanggalAcara->addDays($diff)->format('d M Y');
            } else {
                // Jika nama hari tidak valid, set menjadi null
                $tanggalAcara = null;
            }
        
            // Update tanggal_acara jika ada acara untuk hari ini
            $acaraSekolah->tanggal_acara = $tanggalAcara; // Tambahkan tanggal_acara
            return $acaraSekolah;
        })->sortBy(function ($acaraSekolah) {
            return $acaraSekolah->tanggal_acara ? Carbon::parse($acaraSekolah->tanggal_acara)->timestamp : PHP_INT_MAX;
        })->values();

        // Tampilkan data acara terlebih dahulu, baru tampilkan hari nya
        $harian = Hari::all();
        $harianWithAcara = $harian->map(function ($hari) use ($acaraSekolahWithHari) {
            // Ambil acara sekolah untuk hari tertentu
            $acaraUntukHari = $acaraSekolahWithHari->where('id_hari', $hari->id_hari)->values();
        
            $hari->acara_sekolah = $acaraUntukHari;
            return $hari;
        })->values();

        // Kembalikan data dengan tanggal selanjutnya
        return response()->json([
            'success' => true,
            'message' => 'Daftar Acara Sekolah Ditemukan',
            'data' => $acaraSekolahWithHari,
        ]);
    }

    public function show($id)
    {
        // Mencari acara sekolah berdasarkan ID
        $acaraSekolah = AcaraSekolah::with('hari')->find($id); // Memuat relasi hari

        // Jika acara sekolah tidak ditemukan, kembalikan response 404
        if (!$acaraSekolah) {
            return response()->json([
                'success' => false,
                'message' => 'Acara Sekolah tidak ditemukan',
            ], 404);
        }

        // Dapatkan tanggal untuk hari saat ini
        $tanggalAcara = Carbon::today();

        // Cek hari dalam minggu
        $daysOfWeek = [
            'Senin' => Carbon::MONDAY,
            'Selasa' => Carbon::TUESDAY,
            'Rabu' => Carbon::WEDNESDAY,
            'Kamis' => Carbon::THURSDAY,
            'Jumat' => Carbon::FRIDAY,
            'Sabtu' => Carbon::SATURDAY,
            'Minggu' => Carbon::SUNDAY,
        ];

        // Cek apakah hari ada dalam array
        if (array_key_exists($acaraSekolah->hari->nama_hari, $daysOfWeek)) {
            // Menentukan selisih hari
            $diff = $daysOfWeek[$acaraSekolah->hari->nama_hari] - $tanggalAcara->dayOfWeek;
            if ($diff <= 0) {
                // Jika hari yang diinginkan sudah lewat, ambil untuk minggu depan
                $diff += 7;
            }

            // Set tanggal_acara ke tanggal yang sesuai
            $tanggalAcara = $tanggalAcara->addDays($diff)->format('d M Y');
        } else {
            // Jika nama hari tidak valid, set menjadi null
            $tanggalAcara = null;
        }

        // Tambahkan tanggal_acara ke data acara sekolah
        $acaraSekolah->tanggal_acara = $tanggalAcara;

        // Kembalikan response dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Acara Sekolah Ditemukan',
            'data' => $acaraSekolah,
        ], 200);
    }



}
