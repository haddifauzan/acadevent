<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\SiswaFormatExport;
use App\Imports\SiswaImport;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\SiswaController;
use App\Http\Controllers\Web\AcaraController;
use App\Http\Controllers\Web\AcaraSekolahController;
use App\Http\Controllers\Web\HariController;

Route::get('/', [AuthController::class, 'show_login'])->name('login');
Route::get('/login', [AuthController::class, 'show_login'])->name('login');
Route::post('/login-proses', [AuthController::class, 'login'])->name('login-proses');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::get('/data-user', [AuthController::class, 'index'])->name('user.index');

    // MENU DATA SISWA
    Route::get('/data-siswa', [SiswaController::class, 'index'])->name('data.siswa');
    Route::post('/data-siswa/store', [SiswaController::class, 'store'])->name('data.siswa.store');
    Route::post('/data-siswa/update/{id}', [SiswaController::class, 'update'])->name('data.siswa.update');
    Route::delete('/data-siswa/delete/{id}', [SiswaController::class, 'destroy'])->name('data.siswa.destroy');
    Route::get('/siswa/download-format', function() {
        return Excel::download(new SiswaFormatExport, 'format_siswa.xlsx');
    })->name('siswa.downloadFormat');
    Route::post('/siswa/import-excel', function (Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        Excel::import(new SiswaImport, $request->file('file'));
        return redirect()->route('data.siswa')->with('success', 'Data siswa berhasil diimport');
    })->name('siswa.importExcel');

    // MENU ACARA
    Route::get('/acara', [AcaraController::class, 'index'])->name('acara');
    Route::get('/acara/create', [AcaraController::class, 'create'])->name('acara.create');
    Route::post('/acara/store', [AcaraController::class, 'store'])->name('acara.store');
    Route::get('/acara/edit/{id}', [AcaraController::class, 'edit'])->name('acara.edit');
    Route::put('/acara/update/{id}', [AcaraController::class, 'update'])->name('acara.update');
    Route::delete('/acara/delete/{id}', [AcaraController::class, 'destroy'])->name('acara.destroy');
    Route::patch('/acara/{id}/cancel', [AcaraController::class, 'cancel'])->name('acara.cancel');

    // MENU ACARA SEKOLAH
    Route::post('/hari', [HariController::class, 'store'])->name('hari.store');       // Menambah hari
    Route::delete('/hari/{id}', [HariController::class, 'destroy'])->name('hari.destroy'); // Hapus hari

    Route::get('/acara-sekolah', [AcaraSekolahController::class, 'index'])->name('acara_sekolah.index'); // Menampilkan acara sekolah
    Route::get('/acara_sekolah/create/{id_hari}', [AcaraSekolahController::class, 'create'])->name('acara_sekolah.create');
    Route::post('/acara-sekolah', [AcaraSekolahController::class, 'store'])->name('acara_sekolah.store'); // Menambah acara
    Route::get('/acara-sekolah/{id}/edit', [AcaraSekolahController::class, 'edit'])->name('acara_sekolah.edit'); // Form edit acara
    Route::put('/acara-sekolah/{id}', [AcaraSekolahController::class, 'update'])->name('acara_sekolah.update'); // Update acara
    Route::delete('/acara-sekolah/{id}', [AcaraSekolahController::class, 'destroy'])->name('acara_sekolah.destroy'); // Hapus acara
    Route::put('/acara_sekolah/{id}/cancel', [AcaraSekolahController::class, 'cancel'])->name('acara_sekolah.cancel');
    Route::put('/acara_sekolah/{id}/activate', [AcaraSekolahController::class, 'activate'])->name('acara_sekolah.activate');

    // Route untuk kalender acara
    Route::get('/acara_sekolah/calendar', [AcaraSekolahController::class, 'calendar'])->name('calendar');


});