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
