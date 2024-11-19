<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acara;
use App\Models\AcaraSekolah;
use App\Models\Hari;
use Carbon\Carbon;
class SemuaAcaraController extends Controller
{
    public function index()
    {
        // Mendapatkan semua acara yang statusnya 'aktif' dan diurutkan berdasarkan tanggal acara terdekat
        $acara = Acara::whereIn('status_acara', ['aktif', 'berlangsung', 'batal'])
            ->orderBy('tanggal_acara')
            ->get()
            ->map(function ($acara) {
                $acara->tanggal_acara = Carbon::parse($acara->tanggal_acara)->format('d M Y');
                return $acara;
            });
    
        // Mendapatkan semua acara sekolah, diurutkan berdasarkan waktu mulai
        $acaraSekolah = AcaraSekolah::with('hari') // Mengambil relasi 'hari' jika dibutuhkan
            ->orderBy('waktu_mulai')
            ->get();
    
        // Menambahkan tanggal acara pada acara sekolah
        $acaraSekolahWithTanggal = $acaraSekolah->map(function ($acaraSekolahItem) {
            // Mendapatkan hari acara
            $hari = $acaraSekolahItem->hari;
    
            // Menentukan tanggal acara berdasarkan hari
            $tanggalAcara = Carbon::today();
            $daysOfWeek = [
                'Senin' => Carbon::MONDAY,
                'Selasa' => Carbon::TUESDAY,
                'Rabu' => Carbon::WEDNESDAY,
                'Kamis' => Carbon::THURSDAY,
                'Jumat' => Carbon::FRIDAY,
                'Sabtu' => Carbon::SATURDAY,
                'Minggu' => Carbon::SUNDAY,
            ];
    
            if (array_key_exists($hari->nama_hari, $daysOfWeek)) {
                $diff = $daysOfWeek[$hari->nama_hari] - $tanggalAcara->dayOfWeek;
                if ($diff <= 0) {
                    $diff += 7; // Menyesuaikan dengan minggu depan jika hari sudah lewat
                }
    
                $tanggalAcara = $tanggalAcara->addDays($diff)->format('d M Y');
            } else {
                $tanggalAcara = null;
            }
    
            // Menambahkan tanggal acara pada objek acara sekolah
            $acaraSekolahItem->tanggal_acara = $tanggalAcara;
    
            return $acaraSekolahItem;
        });
    
        // Menggabungkan acara dan acara sekolah
        $combinedAcara = $acara->merge($acaraSekolahWithTanggal);
    
        // Mengurutkan acara berdasarkan tanggal_acara
        $sortedAcara = $combinedAcara->sortBy(function ($item) {
            return $item->tanggal_acara ? Carbon::parse($item->tanggal_acara)->timestamp : PHP_INT_MAX;
        })->values();
    
        return response()->json([
            'success' => true,
            'message' => 'Daftar Acara Ditemukan',
            'data' => $sortedAcara
        ], 200);
    }

    public function acaraMingguIni()
    {
        // Mendapatkan tanggal awal dan akhir minggu ini
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Mengambil acara yang jatuh pada minggu ini
        $acara = Acara::whereIn('status_acara', ['aktif', 'berlangsung', 'batal', 'selesai'])
            ->whereBetween('tanggal_acara', [$startOfWeek, $endOfWeek])
            ->orderBy('tanggal_acara')
            ->get()
            ->map(function ($acara) {
                $acara->tanggal_acara = Carbon::parse($acara->tanggal_acara)->format('d M Y');
                return $acara;
            });

        $acaraSekolah = AcaraSekolah::with('hari')->get()->map(function ($acaraSekolahItem) {
            $hari = $acaraSekolahItem->hari;
            $tanggalAcara = Carbon::now()->startOfWeek();
            
            $daysOfWeek = [
                'Senin' => Carbon::MONDAY,
                'Selasa' => Carbon::TUESDAY,
                'Rabu' => Carbon::WEDNESDAY,
                'Kamis' => Carbon::THURSDAY,
                'Jumat' => Carbon::FRIDAY,
                'Sabtu' => Carbon::SATURDAY,
                'Minggu' => Carbon::SUNDAY,
            ];

            if (array_key_exists($hari->nama_hari, $daysOfWeek)) {
                $diff = $daysOfWeek[$hari->nama_hari] - $tanggalAcara->dayOfWeek;
                if ($diff <= 0) {
                    $diff += 7;
                }

                $acaraSekolahItem->tanggal_acara = $tanggalAcara->addDays($diff)->format('Y-m-d');
            }

            return $acaraSekolahItem;
        })->whereBetween('tanggal_acara', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])->values();

        // Menggabungkan dan mengurutkan acara
        $combinedAcara = $acara->merge($acaraSekolah)->sortBy('tanggal_acara')->values();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Acara Minggu Ini',
            'data' => $combinedAcara
        ], 200);
    }

    public function acaraBulanIni()
    {
        // Mendapatkan bulan dan tahun ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Mengambil acara yang jatuh pada bulan ini
        $acara = Acara::whereIn('status_acara', ['aktif', 'berlangsung', 'batal', 'selesai'])
            ->whereMonth('tanggal_acara', $currentMonth)
            ->whereYear('tanggal_acara', $currentYear)
            ->orderBy('tanggal_acara')
            ->get()
            ->map(function ($acara) {
                $acara->tanggal_acara = Carbon::parse($acara->tanggal_acara)->format('d M Y');
                return $acara;
            });

        $acaraSekolah = AcaraSekolah::with('hari')->get()->map(function ($acaraSekolahItem) {
            $hari = $acaraSekolahItem->hari;
            $tanggalAcara = Carbon::today();
            
            $daysOfWeek = [
                'Senin' => Carbon::MONDAY,
                'Selasa' => Carbon::TUESDAY,
                'Rabu' => Carbon::WEDNESDAY,
                'Kamis' => Carbon::THURSDAY,
                'Jumat' => Carbon::FRIDAY,
                'Sabtu' => Carbon::SATURDAY,
                'Minggu' => Carbon::SUNDAY,
            ];

            if (array_key_exists($hari->nama_hari, $daysOfWeek)) {
                $diff = $daysOfWeek[$hari->nama_hari] - $tanggalAcara->dayOfWeek;
                if ($diff <= 0) {
                    $diff += 7;
                }

                $acaraSekolahItem->tanggal_acara = $tanggalAcara->addDays($diff)->format('Y-m-d');
            }

            return $acaraSekolahItem;
        })->filter(function ($acaraSekolahItem) use ($currentMonth, $currentYear) {
            return Carbon::parse($acaraSekolahItem->tanggal_acara)->month == $currentMonth
                && Carbon::parse($acaraSekolahItem->tanggal_acara)->year == $currentYear;
        })->values();

        // Menggabungkan dan mengurutkan acara
        $combinedAcara = $acara->merge($acaraSekolah)->sortBy('tanggal_acara')->values();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Acara Bulan Ini',
            'data' => $combinedAcara
        ], 200);
    }
    
}
