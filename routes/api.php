<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AcaraController;
use App\Http\Controllers\API\PesertaAcaraController;
use App\Http\Controllers\API\AcaraSekolahController;


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
        Route::get('/acara/{id}', [AcaraController::class, 'show']);
        Route::post('/acara/{id_acara}/daftar', [AcaraController::class, 'registerToEvent']);
        Route::post('/acara/{id_acara}/kehadiran', [AcaraController::class, 'submitKehadiran']);
        Route::get('/acara/{id_acara}/peserta', [PesertaAcaraController::class, 'getPeserta']);
        Route::get('/history-acara', [AcaraController::class, 'historyAcara']);

        Route::get('/acara-berikutnya', [AcaraController::class, 'showUpcomingEvent']);


        Route::get('/acara-sekolah', [AcaraSekolahController::class, 'index']);
        Route::get('/acara-sekolah/{id}', [AcaraSekolahController::class, 'show']);
        
        
        Route::post('/logout', [AuthController::class, 'logout']);
    });

});
