<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KartuTertelan extends Model
{
    use HasFactory;

    protected $table = 'kartu_tertelan';
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nomor_kartu',
        'nama_nasabah',
        'lokasi_atm',
        'lokasi_simpan',
        'tanggal_masuk',
        'deadline',
        'status',
        'input_oleh'
    ];

    // Relasi ke tabel Users untuk melihat siapa yang menginput kartu pertama kali
    public function user_input()
    {
        return $this->belongsTo(User::class, 'input_oleh');
    }
}