<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$c = new \App\Http\Controllers\Admin\LaporanSlogController();
$ref = new ReflectionMethod($c, 'getWeeksForMonth');
$ref->setAccessible(true);
$weeks = $ref->invoke($c, 5, 2026);
foreach ($weeks as $name => $dates) {
    echo $name . ": " . $dates[0]->toDateString() . " to " . $dates[1]->toDateString() . "\n";
}
