<?php

namespace App\Exports;

use App\Models\Kendaraan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SatkerKendaraanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $satkerId;

    public function __construct($satkerId)
    {
        $this->satkerId = $satkerId;
    }

    public function collection()
    {
        return Kendaraan::where('satker_id', $this->satkerId)
            ->orderBy('jenis_bbm')
            ->orderBy('no_polisi')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'KODE KENDARAAN',
            'JENIS KENDARAAN',
            'NOPOL',
            'JENIS BBM',
            'SALDO (LITER)',
            'PIN',
        ];
    }

    public function map($kendaraan): array
    {
        static $row = 0;
        $row++;
        return [
            $row,
            $kendaraan->kode_kendaraan ?? '-',
            $kendaraan->jenis_kendaraan,
            $kendaraan->no_polisi,
            $kendaraan->jenis_bbm,
            $kendaraan->saldo,
            $kendaraan->pin,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        
        // Header style
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Indigo-600
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Border for whole table
        if ($highestRow > 1) {
            $sheet->getStyle('A1:G' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E2E8F0'],
                    ],
                ],
            ]);
            
            // Center alignment for some columns
            $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D2:G' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
}
