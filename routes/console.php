<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use App\Models\SatisfactionIndex;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    SatisfactionIndex::truncate();
    Log::info('Satisfaction Index data has been reset for the new month.');
})->monthlyOn(1, '00:00');

// Auto Backup Database ke Google Drive setiap hari sesuai jam & zona waktu (WITA/WIB) yang diatur
try {
    $backupTime = \App\Models\Setting::where('key', 'backup_gdrive_time')->value('value') ?: '23:00';
    $backupTz = \App\Models\Setting::where('key', 'backup_gdrive_timezone')->value('value') ?: 'WITA';
    $tzName = ($backupTz === 'WIB') ? 'Asia/Jakarta' : 'Asia/Makassar';

    Schedule::command('backup:database-to-drive')
        ->dailyAt($backupTime)
        ->timezone($tzName);
} catch (\Throwable $e) {
    Schedule::command('backup:database-to-drive')->dailyAt('23:00')->timezone('Asia/Makassar');
}


