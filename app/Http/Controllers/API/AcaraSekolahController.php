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
        $harian = Hari::all();

        // Ambil acara sekolah yang waktu_mulai >= tanggalSelanjutnya
        $acaraSekolah = AcaraSekolah::with('hari')
            ->orderBy('waktu_mulai')
            ->get();

        // Peta hari dengan acara sekolah
        $harianWithAcara = $harian->map(function ($hari) use ($acaraSekolah) {
            // Ambil acara sekolah untuk hari tertentu
            $acaraUntukHari = $acaraSekolah->where('id_hari', $hari->id_hari)->values();
        
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
            if (array_key_exists($hari->nama_hari, $daysOfWeek)) {
                // Menentukan selisih hari
                $diff = $daysOfWeek[$hari->nama_hari] - $tanggalAcara->dayOfWeek;
                if ($diff <= 0) {
                    // Jika hari yang diinginkan sudah lewat, ambil untuk minggu depan
                    $diff += 7;
                }
        
                // Set tanggal_acara ke tanggal yang sesuai
                $tanggalAcara = $tanggalAcara->addDays($diff)->format('Y-m-d');
            } else {
                // Jika nama hari tidak valid, set menjadi null
                $tanggalAcara = null;
            }
        
            // Update tanggal_acara jika ada acara untuk hari ini
            if ($acaraUntukHari->isNotEmpty()) {
                $tanggalAcara = Carbon::parse($tanggalAcara . ' ' . $acaraUntukHari->first()->waktu_mulai)->format('Y-m-d H:i:s');
            } else {
                $tanggalAcara = null; // Set menjadi null jika tidak ada acara
            }
        
            $hari->acara_sekolah = $acaraUntukHari;
            $hari->tanggal_acara = $tanggalAcara; // Tambahkan tanggal_acara
            return $hari;
        })->sortBy(function ($hari) {
            return $hari->tanggal_acara ? Carbon::parse($hari->tanggal_acara)->timestamp : PHP_INT_MAX;
        })->values();

        // Kembalikan data dengan tanggal selanjutnya
        return response()->json([
            'success' => true,
            'message' => 'Daftar Acara Sekolah Ditemukan',
            'hari' => $harianWithAcara
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
            $tanggalAcara = $tanggalAcara->addDays($diff)->format('Y-m-d');
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
