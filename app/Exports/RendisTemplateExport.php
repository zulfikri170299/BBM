<?php

namespace App\Exports;

use App\Models\Kendaraan;
use App\Models\Satker;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RendisTemplateExport implements FromView, WithColumnWidths, WithStyles, WithEvents
{
    public function view(): View
    {
        $orderedSatkers = Satker::getOrderedForRendis();
        $satkerIds = $orderedSatkers->pluck('id')->toArray();
        
        $satkers = Satker::with(['kendaraans' => function ($q) {
                $q->orderBy('kategori_kendaraan')
                  ->orderBy('jenis_bbm')
                  ->orderBy('no_polisi');
            }])
            ->whereIn('id', $satkerIds)
            ->get()
            ->sortBy(function($s) use ($satkerIds) {
                $pos = array_search($s->id, $satkerIds);
                return $pos !== false ? $pos : 999;
            });

        return view('admin.rendis.excel_template', [
            'satkers' => $satkers
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18, // ID
            'B' => 5,  // NO
            'C' => 16, // URAIAN
            'D' => 28, // JENIS RANDIS
            'E' => 12, // NOPOL
            'F' => 15, // JENIS BBM
            'G' => 9,  // Indeks B1 / Netto Ptx
            'H' => 10, // Hari B1 / formula
            'I' => 10, // Pertamax B1 / Netto Dex
            'J' => 10, // Dex B1 / formula
            'K' => 8,  // Indeks B2
            'L' => 8,  // Hari B2
            'M' => 10, // Pertamax B2
            'N' => 10, // Dex B2
            'O' => 8,  // Indeks B3
            'P' => 8,  // Hari B3
            'Q' => 10, // Pertamax B3
            'R' => 10, // Dex B3
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            9 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Add dropdown for Uraian (Col C) from row 11 down to 500
                $validation = $sheet->getCell('C11')->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input Error');
                $validation->setError('Pilih kategori dari daftar yang tersedia.');
                $validation->setPromptTitle('Pilih Kategori');
                $validation->setPrompt('Silakan pilih Opsna, Pimpinan, atau Staff.');
                // Define dropdown options
                $validation->setFormula1('"Operasional,Pimpinan,Staff"');
                
                // Apply the validation to a range
                $sheet->setDataValidation('C11:C500', $validation);

                $highestRow = $sheet->getHighestRow();
                
                // Set background color for Indeks columns (G, K, O) - previously #e0f2fe
                $sheet->getStyle('G11:G' . $highestRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE0F2FE');
                $sheet->getStyle('K11:K' . $highestRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE0F2FE');
                $sheet->getStyle('O11:O' . $highestRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE0F2FE');

                // Set font color for Hari columns (H, L, P) - previously #6b7280
                $sheet->getStyle('H11:H' . $highestRow)->getFont()->getColor()->setARGB('FF6B7280');
                $sheet->getStyle('L11:L' . $highestRow)->getFont()->getColor()->setARGB('FF6B7280');
                $sheet->getStyle('P11:P' . $highestRow)->getFont()->getColor()->setARGB('FF6B7280');

                // Set Number Format
                $sheet->getStyle('B4')->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('D4')->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('G11:R' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
