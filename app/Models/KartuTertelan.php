<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KartuTertelan extends Model
{
    use HasFactory;

    protected $table = 'kartu_tertelan';
    
    // Menentukan bahwa primary key menggunakan string (UUID)
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
        'input_oleh',
        'diubah_oleh'
    ];

    // Relasi ke tabel Users untuk data pembuat/penginput kartu
    public function user_input()
    {
        return $this->belongsTo(User::class, 'input_oleh');
    }

    // Relasi ke tabel Users untuk data petugas yang mengubah status terakhir
    public function user_ubah()
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}