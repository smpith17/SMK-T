<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens; // Pastikan ini ada jika kamu pakai Sanctum untuk token login

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 1. PENTING: Beritahu Laravel bahwa Primary Key kita bertipe String (UUID) dan tidak auto-increment
    protected $keyType = 'string';
    public $incrementing = false;

    // 2. Daftarkan kolom-kolom baru kita agar bisa diisi data (Mass Assignment)
    protected $fillable = [
        'id',
        'nama',
        'username',
        'role',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 3. Otomatis membuat UUID v4 baru ketika ada user baru yang didaftarkan
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = (string) Str::uuid();
            }
        });
    }
}