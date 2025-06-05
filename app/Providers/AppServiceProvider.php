<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\PenugasanTeknisi;
use App\Observers\PenugasanTeknisiObserver;
use App\Models\PelaporLaporan;
use App\Observers\UpdateLaporanObserver;
use App\Models\LaporanKerusakan;
use App\Observers\FeedbackTeknisiObserver;
use App\Observers\LaporanKerusakanObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        PenugasanTeknisi::observe(PenugasanTeknisiObserver::class);
        PelaporLaporan::observe(UpdateLaporanObserver::class);
        LaporanKerusakan::observe(LaporanKerusakanObserver::class);
        PenugasanTeknisi::observe(FeedbackTeknisiObserver::class);
    }
}
