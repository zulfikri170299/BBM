<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class KendaraanTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'NO',
            'JENIS KENDARAAN',
            'RODA',
            'CC',
            'NOPOL',
            'JENIS BBM',
        ];
    }

    public function array(): array
    {
        // Example data rows to guide user
        return [
            [1, 'Mobil Patrol', 'R4', '1500', 'DR 1234 AB', 'Pertamax'],
            [2, 'Motor Dinas', 'R2', '150', 'DR 5678 CD', 'Pertamax'],
            [3, 'Bus Dinas', 'R6', '3000', 'DR 9012 EF', 'Pertamina Dex'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Indigo
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Data rows style (example rows 2-4)
        $sheet->getStyle('A2:F4')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '6B7280'], // gray
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        // Add note in row 6
        $sheet->setCellValue('A6', 'PETUNJUK PENGISIAN:');
        $sheet->setCellValue('A7', '1. Kolom NO bisa diisi urutan angka (opsional, tidak akan diproses)');
        $sheet->setCellValue('A8', '2. Kolom JENIS KENDARAAN wajib diisi (contoh: Mobil Patrol, Motor Dinas, dsb)');
        $sheet->setCellValue('A9', '3. Kolom NOPOL wajib diisi dan harus unik (belum terdaftar)');
        $sheet->setCellValue('A10', '4. Kolom JENIS BBM wajib diisi: "Pertamax" atau "Pertamina Dex"');
        $sheet->setCellValue('A11', '5. Hapus baris contoh (baris 2-4) sebelum mengisi data');

        $sheet->getStyle('A6')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'DC2626'], 'size' => 11],
        ]);
        $sheet->getStyle('A7:A11')->applyFromArray([
            'font' => ['size' => 10, 'color' => ['rgb' => '6B7280']],
        ]);

        // Auto-set column width
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(18);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
}
