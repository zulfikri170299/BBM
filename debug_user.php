<?php

use App\Models\Personel;
use App\Models\User;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$nrp = '123456';
$personel = Personel::where('nrp', $nrp)->first();

if ($personel) {
    echo "Personel Found: " . $personel->nama . "\n";
    echo "User ID: " . $personel->user_id . "\n";
    if ($personel->user) {
        echo "User Username: " . $personel->user->username . "\n";
        echo "User Role: " . $personel->user->role . "\n";
    } else {
        echo "User Not Found for this Personel.\n";
    }
} else {
    echo "Personel with NRP $nrp NOT FOUND.\n";
    
    // Check if user exists with username 123456 directly
    $user = User::where('username', $nrp)->first();
    if ($user) {
        echo "User Found with username $nrp (but not in Personel table linked by NRP?)\n";
        echo "Role: " . $user->role . "\n";
    } else {
        echo "User with username $nrp also NOT FOUND.\n";
    }
}
