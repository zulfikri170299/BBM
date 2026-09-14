<?php
$modelsDir = __DIR__ . '/../app/Models';
$modelsToUpdate = [
    'TransaksiBbm',
    'RiwayatTopup',
    'RiwayatStokAdmin',
    'Hutang',
    'BaLog',
    'LogAktivitas',
    'Catatan',
    'SatisfactionIndex',
    'PembelianBbm',
    'RiwayatTransferSaldoPersonel',
    'RiwayatTransferAntarPersonel',
    'SinkronisasiBbm',
    'DailyMeterReading',
    'RendisBbm',
];

foreach ($modelsToUpdate as $modelName) {
    $filePath = "{$modelsDir}/{$modelName}.php";
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        
        // Skip if already applied
        if (strpos($content, 'FilterableByYear') !== false) {
            echo "Skipping $modelName\n";
            continue;
        }

        // Add use statement at the top if not exists
        if (strpos($content, 'use App\\Traits\\FilterableByYear;') === false) {
            $content = preg_replace('/(namespace App\\\\Models;)/', "$1\n\nuse App\\Traits\\FilterableByYear;", $content);
        }

        // Add to class traits
        if (strpos($content, 'use HasFactory;') !== false) {
            $content = preg_replace('/use HasFactory;/', "use HasFactory, FilterableByYear;", $content);
        } else {
            $content = preg_replace('/class ' . $modelName . ' extends Model\s*\{/', "class $modelName extends Model\n{\n    use FilterableByYear;\n", $content);
        }

        // Specific rules (e.g. BaLog and RendisBbm uses 'tahun' column)
        if (in_array($modelName, ['BaLog', 'RendisBbm'])) {
            if (strpos($content, 'yearFilterField') === false) {
                $content = preg_replace('/(use HasFactory, FilterableByYear;)/', "$1\n\n    public \$yearFilterField = 'tahun';", $content);
            }
        }

        file_put_contents($filePath, $content);
        echo "Updated $modelName\n";
    } else {
        echo "File not found: $modelName\n";
    }
}
