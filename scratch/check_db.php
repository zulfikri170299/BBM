<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Database connection: " . config('database.default') . PHP_EOL;
echo "Database name: " . DB::connection()->getDatabaseName() . PHP_EOL;

try {
    $count = App\Models\Kendaraan::where(function ($q) {
        $q->where('status_approval', 'disetujui')
          ->orWhere(function ($sub) {
              $sub->where('status_approval', 'ditolak')
                  ->where('aksi_approval', 'edit');
          });
    })->count();
    echo "Query count: " . $count . PHP_EOL;
} catch (\Throwable $e) {
    echo "Query error: " . $e->getMessage() . PHP_EOL;
}

$columns = Schema::getColumnListing('kendaraans');
echo "Columns in kendaraans table: " . implode(', ', $columns) . PHP_EOL;

$migrations = DB::table('migrations')->pluck('migration')->toArray();
echo "Total migrations executed: " . count($migrations) . PHP_EOL;
foreach ($migrations as $m) {
    if (strpos($m, 'approval') !== false || strpos($m, 'kendaraan') !== false) {
        echo " - " . $m . PHP_EOL;
    }
}

