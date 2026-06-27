<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // <--- Pastikan Model User dipanggil
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Jalur WEB: Melayani login dari form website biasa (Menggunakan Session & Redirect)
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // TRIK ANTI-GAGAL: Jika database terdeteksi kosong, langsung isi akun di sini saat runtime
        if (User::count() === 0) {
            User::create([
                'id' => (string) Str::uuid(),
                'nama' => 'Rian Admin',
                'username' => 'admin_rian',
                'role' => 'admin',
                'password' => Hash::make('password123'),
                'is_active' => 1,
            ]);

            User::create([
                'id' => (string) Str::uuid(),
                'nama' => 'Budi Satpam',
                'username' => 'satpam_budi',
                'role' => 'satpam',
                'password' => Hash::make('password123'),
                'is_active' => 1,
            ]);

            User::create([
                'id' => (string) Str::uuid(),
                'nama' => 'Siti CS',
                'username' => 'cs_siti',
                'role' => 'cs',
                'password' => Hash::make('password123'),
                'is_active' => 1,
            ]);
        }

        // Jalankan proses autentikasi bawaan Laravel
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Cek role: Satpam lempar ke Input, CS/Admin lempar ke Dashboard
            if (Auth::user()->role === 'satpam') {
                return redirect()->intended('/input');
            } else {
                return redirect()->intended('/dashboard');
            }
        }

        return back()->withErrors(['username' => 'Username atau password salah!']);
    }

    /**
     * Jalur API: Melayani login khusus untuk POSTMAN (Stateless, Tanpa Session, Return JSON)
     */
    public function loginApi(Request $request)
    {
        // 1. Validasi input JSON dari Postman
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 2. Jalankan proses autentikasi akun
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Pembuatan token otomatis (Aman: Mendukung Laravel Sanctum, punya fallback jika belum di-install)
            $token = method_exists($user, 'createToken') 
                ? $user->createToken('api-token')->plainTextToken 
                : Str::random(60);

            // 3. Kembalikan response JSON murni (Status 200 OK)
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil melalui API!',
                'token'   => $token,
                'data'    => [
                    'user_id'  => $user->id,
                    'username' => $user->username,
                    'role'     => $user->role
                ]
            ], 200);
        }

        // 4. Jika gagal login, kirim respons error JSON (Status 401 Unauthorized)
        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah!'
        ], 401);
    }

    /**
     * Jalur WEB: Proses logout dari website
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}