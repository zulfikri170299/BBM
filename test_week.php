<?php
require 'vendor/autoload.php';
$startOfMonth = \Carbon\Carbon::createFromDate(2026, 5, 1)->startOfMonth();
$endOfMonth = $startOfMonth->copy()->endOfMonth();

$weeks = [];
$currentStart = $startOfMonth->copy();

$weekNumber = 1;
$romanNumerals = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];

while ($currentStart <= $endOfMonth) {
    // End of the week is Sunday
    $currentEnd = $currentStart->copy()->endOfWeek(\Carbon\Carbon::SUNDAY)->endOfDay();
    
    // If the end of the week is beyond the end of the month, cap it at the end of the month
    if ($currentEnd > $endOfMonth) {
        $currentEnd = $endOfMonth->copy();
    }

    $weeks['MINGGU ' . $romanNumerals[$weekNumber]] = [$currentStart->copy(), $currentEnd->copy()];
    
    $currentStart = $currentEnd->copy()->addSecond()->startOfDay();
    $weekNumber++;
}

foreach ($weeks as $name => $dates) {
    echo $name . ": " . $dates[0]->toDateString() . " to " . $dates[1]->toDateString() . "\n";
}
