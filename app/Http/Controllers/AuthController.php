<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // <--- Pastikan Model User dipanggil
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}