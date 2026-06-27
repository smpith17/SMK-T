<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KartuController; // <-- BARU: Import KartuController di sini

// Public routes (tidak perlu login)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (harus login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::patch('/kartu/{id}/status', [KartuController::class, 'updateStatus']);
    
    // Fitur Kartu Tertelan
    Route::post('/kartu', [KartuController::class, 'simpan']);        // Satpam - Input
    Route::get('/kartu', [KartuController::class, 'index']);          // CS/Admin - Lihat Daftar
    Route::put('/kartu/{id}', [KartuController::class, 'updateStatus']); // CS/Admin - Ubah Status
});