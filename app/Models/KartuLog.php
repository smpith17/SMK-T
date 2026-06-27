<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class KartuLog extends Model
{
    use HasUuids;

    // 1. Pastikan mengarah ke nama tabel di database kamu
    protected $table = 'log_audit';

    // 2. Matikan updated_at karena di tabel tidak ada
    const UPDATED_AT = null; 

    protected $fillable = [
        'id',
        'user_id',
        'kartu_id',
        'action',
        'old_status',
        'new_status',
        'ip_address',
        'timestamp'
    ];
}