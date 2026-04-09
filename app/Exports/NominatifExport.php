<?php

namespace App\Exports;

use App\Models\BaLog;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NominatifExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $tahun;
    protected $bulan;
    protected $startDate;
    protected $endDate;

    public function __construct($tahun, $bulan, $startDate = null, $endDate = null)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $logs = \App\Http\Controllers\Admin\NominatifController::getSortedLogs($this->tahun, $this->bulan, $this->startDate, $this->endDate);
        $namaBulan = \Carbon\Carbon::create()->month((int) $this->bulan)->translatedFormat('F');
        $tw = \App\Http\Controllers\Admin\NominatifController::getTriwulan($this->bulan);
        $periodeText = \App\Http\Controllers\Admin\NominatifController::getPeriodeText($this->tahun, $this->bulan, $this->startDate, $this->endDate);

        return view('admin.nominatif.excel', [
            'logs' => $logs,
            'tahun' => $this->tahun,
            'namaBulan' => $namaBulan,
            'tw' => $tw,
            'periodeText' => $periodeText,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Styling tambahan di luar styling inline html
        // Misalnya mengatur lebar kolom spesifik
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);

        // Make headers bold
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);

        return [];
    }
}
