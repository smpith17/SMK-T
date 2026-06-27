<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KartuAtm extends Model
{
    protected $table = 'kartu_atm';

    protected $fillable = [
        'no_kartu_last4',
        'nama_nasabah',
        'kode_atm',
        'lokasi_simpan',
        'tanggal_masuk',
        'tanggal_batas',
        'status',
        'created_by',
    ];

    protected $appends = ['sisa_hari'];

    public function getSisaHariAttribute()
    {
        return Carbon::now()->diffInDays($this->tanggal_batas, false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(KartuLog::class, 'kartu_id');
    }
}