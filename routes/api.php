<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AcaraController;
use App\Http\Controllers\API\PesertaAcaraController;
use App\Http\Controllers\API\AcaraSekolahController;
use App\Http\Controllers\API\SemuaAcaraController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware([EnsureFrontendRequestsAreStateful::class])->group(function () {
    // Route untuk autentikasi
    Route::post('/check-nis', [AuthController::class, 'checkNIS']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'getProfile']);

        // Menu Acara
        Route::get('/acara', [AcaraController::class, 'index']);
        Route::get('/acara-sekolah', [AcaraSekolahController::class, 'index']);
        
        Route::get('/acara-sekolah/{id}', [AcaraSekolahController::class, 'show']);
        Route::get('/acara/{id}', [AcaraController::class, 'show']);
        

        Route::post('/acara/daftar/{id_acara}', [AcaraController::class, 'registerToEvent']);
        Route::post('/acara/kehadiran/{id_acara}', [AcaraController::class, 'submitKehadiran']);
        Route::get('/acara/peserta/{id_acara}', [PesertaAcaraController::class, 'getPeserta']);
        Route::get('/history-acara', [AcaraController::class, 'historyAcara']);

        Route::get('/acara-berikutnya', [AcaraController::class, 'showUpcomingEvent']);

        Route::get('/semua-acara', [SemuaAcaraController::class, 'index']);
        Route::get('/acara-minggu-ini', [SemuaAcaraController::class, 'acaraMingguIni']);
        Route::get('/acara-bulan-ini', [SemuaAcaraController::class, 'acaraBulanIni']);
        
        Route::post('/logout', [AuthController::class, 'logout']);
    });

});
