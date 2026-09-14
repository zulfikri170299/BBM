<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\RiwayatTopup;
use App\Models\RiwayatStokAdmin;
use Carbon\Carbon;

$topups = RiwayatTopup::where('metode', 'RENDIS')->get();
$count = 0;

foreach($topups as $topup) {
    // keterangan: Top Up Rendis TW I 2026 Bulan 2
    if(preg_match('/Top Up Rendis (TW [I|V]+) (\d{4}) Bulan (\d)/', $topup->keterangan, $matches)) {
        $triwulan = $matches[1];
        $tahun = $matches[2];
        $bulan = (int)$matches[3];
        
        $twOffset = 0;
        if ($triwulan === 'TW II') $twOffset = 3;
        elseif ($triwulan === 'TW III') $twOffset = 6;
        elseif ($triwulan === 'TW IV') $twOffset = 9;
        
        $logicalMonth = $twOffset + $bulan;
        $topupDate = Carbon::create($tahun, $logicalMonth, 15, 12, 0, 0, 'UTC')->format('Y-m-d H:i:s');
        
        $topup->created_at = $topupDate;
        $topup->updated_at = $topupDate;
        $topup->save();
        $count++;
        
        // Coba perbaiki juga RiwayatStokAdmin terkait (tipe keluar, jumlah sama, waktu berdekatan)
        // Cari RiwayatStokAdmin yang dibuat pada hari yang sama dengan $topup->created_at asli (sebelum diubah)
        // Tapi keterangannya "Top-up via Rendis..."
        // Sayangnya kita tidak simpan ID, jadi kita skip dulu atau kita perbaiki dengan query keterangan
    }
}

// Perbaiki RiwayatStokAdmin
$stoks = RiwayatStokAdmin::where('keterangan', 'like', 'Top-up via Rendis%')->get();
$countStok = 0;
foreach($stoks as $stok) {
    if(preg_match('/Top-up via Rendis (TW [I|V]+) (\d{4}) Bulan (\d) /', $stok->keterangan, $matches)) {
        $triwulan = $matches[1];
        $tahun = $matches[2];
        $bulan = (int)$matches[3];
        
        $twOffset = 0;
        if ($triwulan === 'TW II') $twOffset = 3;
        elseif ($triwulan === 'TW III') $twOffset = 6;
        elseif ($triwulan === 'TW IV') $twOffset = 9;
        
        $logicalMonth = $twOffset + $bulan;
        $topupDate = Carbon::create($tahun, $logicalMonth, 15, 12, 0, 0, 'UTC')->format('Y-m-d H:i:s');
        
        $stok->created_at = $topupDate;
        $stok->updated_at = $topupDate;
        $stok->save();
        $countStok++;
    }
}

echo "Fixed $count RiwayatTopup and $countStok RiwayatStokAdmin records.\n";
