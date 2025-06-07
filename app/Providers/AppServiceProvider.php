<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\PelaporLaporan;
use App\Models\LaporanKerusakan;
use App\Models\PenugasanTeknisi;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Observers\UpdateLaporanObserver;
use App\Observers\FeedbackTeknisiObserver;
use App\Observers\LaporanKerusakanObserver;
use App\Observers\PenugasanTeknisiObserver;

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
        LaporanKerusakan::observe(LaporanKerusakanObserver::class);
        PenugasanTeknisi::observe(FeedbackTeknisiObserver::class);
        Carbon::setLocale('id');
    }
}
