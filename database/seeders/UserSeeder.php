<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Utama',
            'username' => 'admin',
            'email'    => 'admin@smkt.local',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Customer Service 01',
            'username' => 'cs_01',
            'email'    => 'cs01@smkt.local',
            'password' => Hash::make('cs123'),
            'role'     => 'cs',
        ]);

        User::create([
            'name'     => 'Satpam 01',
            'username' => 'satpam_01',
            'email'    => 'satpam01@smkt.local',
            'password' => Hash::make('satpam123'),
            'role'     => 'satpam',
        ]);
    }
}