<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\Illuminate\Support\Facades\DB::beginTransaction();

try {
    $g9 = \App\Models\Kendaraan::where('no_polisi', 'G9 1')->first();
    echo "G9 1 ID: " . ($g9 ? $g9->id : 'null') . "\n";

    // 1. Lepaskan foreign key kendaraan_id pada transfer agar tidak cascade delete
    \Illuminate\Support\Facades\DB::table('riwayat_transfer_saldo_personels')
        ->where('kendaraan_id', $g9->id)
        ->update([
            'kendaraan_id' => null,
            'keterangan' => 'Transfer saldo BBM dari Kendaraan (G9 1)'
        ]);

    // 2. Bersihkan riwayat topup G9 1
    \Illuminate\Support\Facades\DB::table('riwayat_topups')->where('kendaraan_id', $g9->id)->delete();

    // 3. Hapus kendaraan G9 1
    $g9->delete();
    echo "G9 1 deleted from kendaraans table.\n";

    // 4. Test Laporan Bulanan Satker 7 di bulan 8 (Agustus 2026)
    $controller = new \App\Http\Controllers\Admin\KendaraanController();
    $reflector = new ReflectionClass($controller);
    $method = $reflector->getMethod('buildLaporanBulananData');
    $method->setAccessible(true);

    $data8 = $method->invoke($controller, 7, 8, 2026);
    echo "\n=== LAPORAN BULAN AGUSTUS 2026 SATKER 7 ===\n";
    $foundG9 = false;
    foreach ($data8['rows'] as $r) {
        if ($r['no_polisi'] === 'G9 1') {
            $foundG9 = true;
        }
        if (strpos($r['no_polisi'], '903') !== false) {
            echo "Nopol: {$r['no_polisi']} | Topup: {$r['topup_bulan_ini']} | TM: {$r['tm_bulan_ini']} | TK: {$r['tk_bulan_ini']} | TotalPakai: {$r['total_pemakaian']} | SISA: {$r['sisa_bbm']}\n";
        }
    }
    echo "G9 1 ada di tabel laporan? " . ($foundG9 ? 'YA' : 'TIDAK (SUDAH TERHAPUS)') . "\n";
    echo "Summary Dex: " . json_encode($data8['summaryByBbm']['Pertamina Dex']) . "\n";

    // 5. Cek record transfer 755
    $tr = \App\Models\RiwayatTransferSaldoPersonel::find(755);
    echo "\nRecord transfer 755: ID: {$tr->id} | Kend ID: " . var_export($tr->kendaraan_id, true) . " | Tujuan ID: {$tr->tujuan_kendaraan_id} | Jumlah: {$tr->jumlah}\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    \Illuminate\Support\Facades\DB::rollBack();
}

