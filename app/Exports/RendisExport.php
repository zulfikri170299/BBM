<?php

namespace App\Exports;

use App\Models\RendisBbm;
use App\Models\Satker;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class RendisExport implements FromView, ShouldAutoSize, WithColumnFormatting
{
    protected $rendisBbm;

    public function __construct(RendisBbm $rendisBbm)
    {
        $this->rendisBbm = $rendisBbm;
    }

    public function view(): View
    {
        $this->rendisBbm->load('rendisKendaraans.kendaraan.satker');
        $kendaraansBySatker = $this->rendisBbm->rendisKendaraans->groupBy(function ($rk) {
            return $rk->kendaraan->satker_id ?? 0;
        });
        $satkers = Satker::all()->keyBy('id');

        return view('admin.rendis.excel', [
            'rendisBbm' => $this->rendisBbm,
            'kendaraansBySatker' => $kendaraansBySatker,
            'satkers' => $satkers
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'C' => '#,##0',
            'D' => '#,##0',
            'E' => '#,##0',
            'F' => '#,##0',
            'G' => '#,##0',
            'H' => '#,##0',
            'I' => '#,##0',
            'J' => '#,##0',
            'K' => '#,##0',
            'L' => '#,##0',
            'M' => '#,##0',
        ];
    }
}
