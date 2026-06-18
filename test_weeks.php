<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Carbon\Carbon;

$tahun = 2026;
$bulan = 5;
$startDate = Carbon::create($tahun, $bulan, 1)->startOfDay();
$endDate = $startDate->copy()->endOfMonth()->endOfDay();

$weeks = [];
$currentDate = $startDate->copy();

$weekNumber = 1;
while ($currentDate <= $endDate) {
    $weekStart = $currentDate->copy()->startOfWeek(Carbon::MONDAY);
    $weekEnd = $currentDate->copy()->endOfWeek(Carbon::SUNDAY);
    
    if ($weekStart < $startDate) $weekStart = $startDate->copy();
    if ($weekEnd > $endDate) $weekEnd = $endDate->copy();
    
    $weekName = 'MINGGU ' . $weekNumber;
    $weeks[$weekName] = [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')];
    
    $currentDate = $weekEnd->copy()->addDay();
    $weekNumber++;
}
echo json_encode($weeks, JSON_PRETTY_PRINT);
