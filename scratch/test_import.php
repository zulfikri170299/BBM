<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RendisTemplateExport;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class DummyImport implements ToCollection {
    public function collection(Collection $rows) {
        $data = [];
        for($i=0; $i<10; $i++) {
            $data[] = $rows[$i]->toArray();
        }
        file_put_contents(__DIR__.'/rows_dump.json', json_encode($data, JSON_PRETTY_PRINT));
    }
}

Excel::store(new RendisTemplateExport, 'test_template.xlsx', 'local');
// Find the exact path for local disk
$path = storage_path('app/test_template.xlsx');
if(!file_exists($path)) {
    $path = storage_path('app/private/test_template.xlsx');
}
Excel::import(new DummyImport, $path);
echo "Done\n";
