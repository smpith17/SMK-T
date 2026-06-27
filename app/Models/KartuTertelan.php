<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 1. PASTIKAN BARIS INI ADA DI ATAS (DI LUAR CLASS)
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class KartuTertelan extends Model
{
    // 2. Baris ini dipasang di dalam class. Kalau baris nomor 1 sudah ada, ini tidak akan merah lagi
    use HasUuids; 

    protected $table = 'kartu_tertelan'; 

    protected $fillable = [
        'nomor_kartu', 
        'nama_nasabah', 
        'lokasi_atm', 
        'lokasi_simpan', 
        'tanggal_masuk', 
        'deadline', 
        'status', 
        'input_oleh'
    ]; 
}