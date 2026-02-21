<?php

namespace App\Exports;

use App\Models\Personel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SatkerPersonelExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $satkerId;

    public function __construct($satkerId)
    {
        $this->satkerId = $satkerId;
    }

    public function collection()
    {
        return Personel::where('satker_id', $this->satkerId)
            ->with('user')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA PERSONEL',
            'NRP/NIP',
            'JENIS BBM',
            'SALDO (LITER)',
            'PIN',
        ];
    }

    public function map($personel): array
    {
        static $row = 0;
        $row++;
        return [
            $row,
            $personel->nama,
            $personel->nrp,
            strtoupper($personel->jenis_bbm ?? '-'),
            $personel->saldo,
            $personel->pin,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        
        // Header style
        $sheet->getStyle('A1:F1')->applyFromArray([
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
            $sheet->getStyle('A1:F' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E2E8F0'],
                    ],
                ],
            ]);
            
            // Center alignment for some columns
            $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C2:C' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D2:F' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
}
