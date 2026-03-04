<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Paksa timezone WITA agar semua fungsi PHP (date, time, now, today, Carbon)
        // selalu menggunakan waktu WITA, bahkan jika config cache masih UTC
        date_default_timezone_set('Asia/Makassar');

        config(['app.locale' => 'id']);
        \Carbon\Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id', 'indonesian');

        if($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
