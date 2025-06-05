<?php

namespace App\Observers;

use App\Models\PelaporLaporan;
use App\Models\User;
use App\Notifications\PelaporNotifikasi;
use App\Models\LaporanKerusakan;

class UpdateLaporanObserver
{
    /**
     * Handle the PelaporLaporan "created" event.
     */
    public function created(PelaporLaporan $laporan): void
    {
        $assignedUser = User::find($laporan->id_user);

        if ($assignedUser) {
            // Notify the assigned user
            $assignedUser->notify(new PelaporNotifikasi($laporan));
        }
    }

    /**
     * Handle the PelaporLaporan "updated" event.
     */
    public function updated(PelaporLaporan $laporan): void
    {
        
    }

    /**
     * Handle the PelaporLaporan "deleted" event.
     */
    public function deleted(PelaporLaporan $PelaporLaporan): void
    {
        //
    }

    /**
     * Handle the PelaporLaporan "restored" event.
     */
    public function restored(PelaporLaporan $PelaporLaporan): void
    {
        //
    }

    /**
     * Handle the PelaporLaporan "force deleted" event.
     */
    public function forceDeleted(PelaporLaporan $PelaporLaporan): void
    {
        //
    }
}
