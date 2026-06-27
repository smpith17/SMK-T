<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

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