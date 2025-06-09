<?php

namespace App\Observers;

use App\Models\PenugasanTeknisi;
use App\Notifications\SarprasNotifikasi;
use App\Models\User;

class FeedbackTeknisiObserver
{
    /**
     * Handle the PenugasanTeknisi "created" event.
     */
    public function created(PenugasanTeknisi $penugasanTeknisi): void
    {
        //
    }

    /**
     * Handle the PenugasanTeknisi "updated" event.
     */
    public function updated(PenugasanTeknisi $penugasanTeknisi): void
    {
        if ($penugasanTeknisi->isDirty('catatan_teknisi' ) && $penugasanTeknisi->catatan_teknisi) {
            $sarprasUsers = User::where('id_level', 2)->get(); 

            foreach ($sarprasUsers as $user) {
                $user->notify(new SarprasNotifikasi([

                    'tipe' => 'Laporan '.$penugasanTeknisi->laporan->fasilitas->nama_fasilitas,
                    'pesan' => $penugasanTeknisi->user->nama . '',
                    'link' => route('perbaikan.index', $penugasanTeknisi->id_laporan),

                    'tipe' => $penugasanTeknisi->laporan->fasilitas->nama_fasilitas . 
                                ' Telah dikerjakan oleh ' . ($penugasanTeknisi->user->nama ?? 'Teknisi'),
                    'pesan' => 'Catatan: ' . $penugasanTeknisi->catatan_teknisi,
                    'link' => url('/laporan/verifikasi/'. $penugasanTeknisi->id_laporan),

                ]));
            }
        }
    }

    /**
     * Handle the PenugasanTeknisi "deleted" event.
     */
    public function deleted(PenugasanTeknisi $penugasanTeknisi): void
    {
        //
    }

    /**
     * Handle the PenugasanTeknisi "restored" event.
     */
    public function restored(PenugasanTeknisi $penugasanTeknisi): void
    {
        //
    }

    /**
     * Handle the PenugasanTeknisi "force deleted" event.
     */
    public function forceDeleted(PenugasanTeknisi $penugasanTeknisi): void
    {
        //
    }
}
