<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Mengecek duplikasi Transaksi BBM (berdasarkan kendaraan, satker, liter, dan waktu yang sama persis):\n";
$duplicateTransactions = \DB::table('transaksi_bbms')
    ->select('kendaraan_id', 'satker_id', 'liter', 'tanggal', \DB::raw('COUNT(*) as count'))
    ->groupBy('kendaraan_id', 'satker_id', 'liter', 'tanggal')
    ->having('count', '>', 1)
    ->get();

if ($duplicateTransactions->isEmpty()) {
    echo "Tidak ada transaksi ganda.\n";
} else {
    foreach ($duplicateTransactions as $t) {
        echo "- Kendaraan: {$t->kendaraan_id}, Satker: {$t->satker_id}, Liter: {$t->liter}, Waktu: {$t->tanggal} (Jumlah: {$t->count})\n";
    }
}

echo "\nMengecek duplikasi Riwayat Topup (berdasarkan kendaraan, jumlah, dan waktu):\n";
$duplicateTopups = \DB::table('riwayat_topups')
    ->select('kendaraan_id', 'jumlah', 'created_at', \DB::raw('COUNT(*) as count'))
    ->groupBy('kendaraan_id', 'jumlah', 'created_at')
    ->having('count', '>', 1)
    ->get();

if ($duplicateTopups->isEmpty()) {
    echo "Tidak ada topup ganda.\n";
} else {
    foreach ($duplicateTopups as $tp) {
        echo "- Kendaraan: {$tp->kendaraan_id}, Jumlah: {$tp->jumlah}, Waktu: {$tp->created_at} (Jumlah: {$tp->count})\n";
    }
}
