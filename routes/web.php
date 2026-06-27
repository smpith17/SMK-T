<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KartuController;
use App\Http\Controllers\AkunController;

// --- RUTE GUEST (Bisa diakses tanpa login) ---
Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// --- RUTE TERPROTEKSI (Wajib Login) ---
Route::middleware('auth')->group(function () {

    // ==========================================
    // 1. SEMUA ROLE (Satpam, CS, Admin)
    // ==========================================
    Route::get('/input', function () {
        return view('kartu.input');
    });
    Route::post('/input', [KartuController::class, 'simpan']);


    // ==========================================
    // 2. KHUSUS ROLE: CUSTOMER SERVICE & ADMIN
    // ==========================================
    Route::get('/dashboard', function () {
        if (!in_array(strtolower(auth()->user()->role), ['cs', 'admin'])) abort(403);
        return app(KartuController::class)->dashboard();
    });

    Route::post('/kartu/{id}/status', function (Request $request, $id) {
        if (!in_array(strtolower(auth()->user()->role), ['cs', 'admin'])) abort(403);
        return app(KartuController::class)->updateStatus($request, $id);
    });

    Route::get('/arsip', function () {
        if (!in_array(strtolower(auth()->user()->role), ['cs', 'admin'])) abort(403);
        return app(KartuController::class)->arsip();
    });

    Route::get('/rekap', function () {
        if (!in_array(strtolower(auth()->user()->role), ['cs', 'admin'])) abort(403);
        return app(KartuController::class)->rekap();
    });

    // Perbaikan Keamanan: Unduh Excel dikunci hanya untuk CS dan Admin
    Route::get('/rekap/excel', function () {
        if (!in_array(strtolower(auth()->user()->role), ['cs', 'admin'])) abort(403);
        return app(KartuController::class)->exportExcel();
    });


    // ==========================================
    // 3. KHUSUS ROLE: ADMINISTRATOR ONLY
    // ==========================================
    Route::get('/akun', function () {
        if (strtolower(auth()->user()->role) !== 'admin') abort(403);
        return app(AkunController::class)->index();
    });

    Route::post('/akun', function (Request $request) {
        if (strtolower(auth()->user()->role) !== 'admin') abort(403);
        return app(AkunController::class)->store($request);
    });

    Route::put('/akun/{id}', function (Request $request, $id) {
        if (strtolower(auth()->user()->role) !== 'admin') abort(403);
        return app(AkunController::class)->update($request, $id);
    });

    Route::post('/akun/reset/{id}', function ($id) {
        if (strtolower(auth()->user()->role) !== 'admin') abort(403);
        return app(AkunController::class)->resetPassword($id);
    });

    Route::delete('/akun/{id}', function ($id) {
        if (strtolower(auth()->user()->role) !== 'admin') abort(403);
        return app(AkunController::class)->destroy($id);
    });

    Route::patch('/akun/{id}/toggle', function ($id) {
        if (strtolower(auth()->user()->role) !== 'admin') abort(403);
        return app(AkunController::class)->toggleActive($id);
    });
});