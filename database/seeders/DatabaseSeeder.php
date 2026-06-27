<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat Akun Satpam untuk Uji Coba Login
        User::create([
            'nama' => 'Budi Satpam',
            'username' => 'satpam_budi',
            'role' => 'satpam',
            'password' => Hash::make('password123'), // Password otomatis di-encrypt
            'is_active' => 1,
        ]);

        // Membuat Akun Customer Service (CS)
        User::create([
            'nama' => 'Siti CS',
            'username' => 'cs_siti',
            'role' => 'cs',
            'password' => Hash::make('password123'),
            'is_active' => 1,
        ]);

        // Membuat Akun Admin
        User::create([
            'nama' => 'Rian Admin',
            'username' => 'admin_rian',
            'role' => 'admin',
            'password' => Hash::make('password123'),
            'is_active' => 1,
        ]);
    }
}