<?php

namespace App\Observers;

use App\Models\LaporanKerusakan;
use App\Models\PelaporLaporan;
use App\Models\User;
use App\Notifications\SarprasNotifikasi;
use App\Notifications\PelaporNotifikasi;

class LaporanKerusakanObserver
{
    /**
     * Handle the LaporanKerusakan "created" event.
     */
    public function created(LaporanKerusakan $laporan): void
    {
        $sarprasUsers = User::where('id_level', 2)->get();
        foreach ($sarprasUsers as $user) {
            $user->notify(new SarprasNotifikasi([
                'tipe' => 'laporan baru: ' . ($laporan->fasilitas->nama_fasilitas ?? 'Fasilitas') . 
                            ' ruang ' . ($laporan->fasilitas->ruangan->nama_ruangan),
                'pesan' => 'Pesan:  ' . ($laporan->deskripsi),
                'link' => route('perbaikan.index', $laporan->id_laporan),
            ]));
        }
    }

    /**
     * Handle the LaporanKerusakan "updated" event.
     */
    public function updated(LaporanKerusakan $laporan): void
    {
        if ($laporan->isDirty('id_status') && !$laporan->wasRecentlyCreated) {
            $pelaporLaporan = PelaporLaporan::where('id_laporan', $laporan->id_laporan)->first();

            if ($pelaporLaporan) {
                $user = $pelaporLaporan->user;
                if ($user) {
                    $user->notify(new PelaporNotifikasi($pelaporLaporan));
                }
            }
        }
    }

    /**
     * Handle the LaporanKerusakan "deleted" event.
     */
    public function deleted(LaporanKerusakan $laporanKerusakan): void
    {
        //
    }

    /**
     * Handle the LaporanKerusakan "restored" event.
     */
    public function restored(LaporanKerusakan $laporanKerusakan): void
    {
        //
    }

    /**
     * Handle the LaporanKerusakan "force deleted" event.
     */
    public function forceDeleted(LaporanKerusakan $laporanKerusakan): void
    {
        //
    }
}
