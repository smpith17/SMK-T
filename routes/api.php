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
// Perlindungan Brute-Force: Maksimal 5 kali percobaan login API per menit
Route::post('/login', [AuthController::class, 'loginApi'])->middleware('throttle:5,1');

// Protected routes (harus login / membawa Bearer Token Sanctum) + Throttling 10 request/menit
Route::middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Fitur Kartu Tertelan
    Route::get('/kartu', [KartuController::class, 'index']);             // CS/Admin - Lihat Daftar (Sekarang mendukung Filter & Pagination)
    Route::post('/kartu', [KartuController::class, 'simpan']);           // Satpam - Input
    Route::put('/kartu/{id}', [KartuController::class, 'updateStatus']); // CS/Admin - Ubah Status (Metode PUT)
    Route::patch('/kartu/{id}/status', [KartuController::class, 'updateStatus']); // CS/Admin - Ubah Status (Metode PATCH)
    Route::delete('/kartu/{id}', [KartuController::class, 'destroy']);   // CS/Admin - Hapus Permanen
});