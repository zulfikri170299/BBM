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

class PersonelTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $isAdmin;

    public function __construct($isAdmin = false)
    {
        $this->isAdmin = $isAdmin;
    }

    public function headings(): array
    {
        $headings = ['NO'];
        if ($this->isAdmin) {
            $headings[] = 'SATKER';
        }
        $headings[] = 'NAMA';
        $headings[] = 'NRP';
        $headings[] = 'JENIS BBM';

        return $headings;
    }

    public function array(): array
    {
        // Example data rows to guide user
        if ($this->isAdmin) {
            return [
                [1, 'BIRO LOGISTIK', 'Fulan bin Fulan', '12345678', 'Pertamax'],
                [2, 'BIRO LOGISTIK', 'Personel Dua', '87654321', 'Pertamina Dex'],
                [3, 'BAG REN', 'Personel Tiga', '11223344', 'Pertamax'],
            ];
        } else {
            return [
                [1, 'Fulan bin Fulan', '12345678', 'Pertamax'],
                [2, 'Personel Dua', '87654321', 'Pertamina Dex'],
                [3, 'Personel Tiga', '11223344', 'Pertamax'],
            ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        $highestCol = $this->isAdmin ? 'E' : 'D';

        // Header style
        $sheet->getStyle('A1:' . $highestCol . '1')->applyFromArray([
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
        $sheet->getStyle('A1:' . $highestCol . '4')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        // Data rows style (example rows 2-4)
        $sheet->getStyle('A2:' . $highestCol . '4')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '6B7280'], // gray
            ],
        ]);

        // Add note in row 6
        $sheet->setCellValue('A6', 'PETUNJUK PENGISIAN:');
        $sheet->setCellValue('A7', '1. Kolom NO bisa diisi urutan angka (opsional, tidak akan diproses)');
        if ($this->isAdmin) {
            $sheet->setCellValue('A8', '2. Kolom SATKER wajib diisi sesuai nama Satuan Kerja yang terdaftar (case insensitive)');
            $sheet->setCellValue('A9', '3. Kolom NAMA & NRP wajib diisi. NRP harus unik (belum terdaftar).');
            $sheet->setCellValue('A10', '4. Hapus baris contoh (baris 2-4) sebelum mengisi data');
        } else {
            $sheet->setCellValue('A8', '2. Kolom NAMA & NRP wajib diisi. NRP harus unik (belum terdaftar).');
            $sheet->setCellValue('A9', '3. Hapus baris contoh (baris 2-4) sebelum mengisi data');
        }

        $sheet->getStyle('A6')->getFont()->setBold(true);

        // Auto-set column width
        $sheet->getColumnDimension('A')->setWidth(6);
        if ($this->isAdmin) {
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(30);
            $sheet->getColumnDimension('D')->setWidth(20);
        } else {
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(20);
        }

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
}
