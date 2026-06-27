<?php

namespace App\Observers;

use App\Models\KartuTertelan;
use App\Models\KartuLog;

class KartuObserver
{
    public function created(KartuTertelan $kartu): void
    {
        KartuLog::create([
            'user_id'    => auth()->id(),
            'kartu_id'   => $kartu->id,
            'action'     => 'Input',
            'old_status' => null,
            'new_status' => $kartu->status,
            'ip_address' => request()->ip(),
        ]);
    }

    public function updating(KartuTertelan $kartu): void
    {
        if ($kartu->isDirty('status')) {
            $statusBaru = $kartu->status;
            
            // Logika: Jika status jadi 'Dimusnahkan', action-nya adalah 'Musnahkan'
            $action = ($statusBaru === 'Dimusnahkan') ? 'Musnahkan' : 'Ubah_Status';

            KartuLog::create([
                'user_id'    => auth()->id(),
                'kartu_id'   => $kartu->id,
                'action'     => $action,
                'old_status' => $kartu->getOriginal('status'),
                'new_status' => $statusBaru,
                'ip_address' => request()->ip(),
            ]);
        }
    }
}