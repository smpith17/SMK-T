<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AkunController extends Controller
{
    private function checkAdmin()
    {
        if (Auth::user()->role !== 'admin') abort(403);
    }

    public function index()
    {
        $this->checkAdmin();
        // Pagination: Menampilkan 10 data per halaman
        $users = User::orderBy('nama')->paginate(10);
        return view('kartu.akun', compact('users'));
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $validator = Validator::make($request->all(), [
            'nama'     => 'required|min:2|max:100',
            'username' => 'required|min:3|max:50|unique:users,username',
            'role'     => 'required|in:satpam,cs,admin',
            'password' => 'required|min:6',
        ], [
            'nama.required'      => 'Nama wajib diisi.',
            'nama.min'           => 'Nama minimal 2 karakter.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah dipakai, pilih yang lain.',
            'username.min'       => 'Username minimal 3 karakter.',
            'role.required'      => 'Role wajib dipilih.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect('/akun')
                ->withErrors($validator, 'tambah')
                ->withInput();
        }

        User::create([
            'nama'      => $request->nama,
            'username'  => $request->username,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
            'is_active' => 1,
        ]);

        return redirect('/akun')->with('success', 'Akun baru berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);

        $rules = [
            'nama'     => 'required|min:2|max:100',
            'username' => 'required|min:3|max:50|unique:users,username,' . $id,
            'role'     => 'required|in:satpam,cs,admin',
        ];
        $messages = [
            'nama.required'     => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah dipakai, pilih yang lain.',
            'role.required'     => 'Role wajib dipilih.',
        ];

        if ($request->filled('password')) {
            $rules['password']        = 'min:6';
            $messages['password.min'] = 'Password minimal 6 karakter.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('/akun')
                ->withErrors($validator, 'edit')
                ->withInput();
        }

        $user->nama     = $request->nama;
        $user->username = $request->username;
        $user->role     = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/akun')->with('success', "Akun {$user->nama} berhasil diperbarui!");
    }

    public function toggleActive($id)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect('/akun')->with('error', 'Tidak dapat mengubah status akun sendiri.');
        }

        $user->is_active = $user->is_active ? 0 : 1;
        $user->save();

        $keterangan = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect('/akun')->with('success', "Akun {$user->nama} berhasil {$keterangan}.");
    }

    public function destroy($id)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect('/akun')->with('error', 'Keamanan Sistem: Anda tidak boleh menghapus akun Anda sendiri yang sedang digunakan.');
        }

        $user->delete();
        return redirect('/akun')->with('success', "Akun \"{$user->nama}\" telah berhasil dihapus dari sistem.");
    }

    public function resetPassword($id)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);

        $user->password = Hash::make('password123');
        $user->save();

        return redirect('/akun')->with('success', "Kata sandi untuk akun petugas \"{$user->nama}\" berhasil direset kembali menjadi: password123");
    }
}