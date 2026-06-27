<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KartuController; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (tidak perlu login)
// SEBELUMNYA: [AuthController::class, 'login'] (Bikin error session di Postman)
// SEKARANG: Diarahkan ke 'loginApi' khusus untuk merespons dengan JSON murni
Route::post('/login', [AuthController::class, 'loginApi']);

// Protected routes (harus login / membawa Bearer Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::patch('/kartu/{id}/status', [KartuController::class, 'updateStatus']);
    
    // Fitur Kartu Tertelan
    Route::post('/kartu', [KartuController::class, 'simpan']);        // Satpam - Input
    Route::get('/kartu', [KartuController::class, 'index']);          // CS/Admin - Lihat Daftar
    Route::put('/kartu/{id}', [KartuController::class, 'updateStatus']); // CS/Admin - Ubah Status
    Route::get('/kartu', [KartuController::class, 'index']);
    Route::delete('/kartu/{id}', [KartuController::class, 'destroy']);
});