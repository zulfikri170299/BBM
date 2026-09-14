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
use PhpOffice\PhpSpreadsheet\Style\Protection;

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
            'K' => 10,  // Indeks B2
            'L' => 11,  // Hari B2
            'M' => 11, // Pertamax B2
            'N' => 11, // Dex B2
            'O' => 10,  // Indeks B3
            'P' => 11,  // Hari B3
            'Q' => 11, // Pertamax B3
            'R' => 11, // Dex B3
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

                // Add dropdown for Triwulan (Col B, Row 3)
                $validationTw = $sheet->getCell('B3')->getDataValidation();
                $validationTw->setType(DataValidation::TYPE_LIST);
                $validationTw->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationTw->setAllowBlank(false);
                $validationTw->setShowDropDown(true);
                $validationTw->setShowInputMessage(true);
                $validationTw->setShowErrorMessage(true);
                $validationTw->setErrorTitle('Input Error');
                $validationTw->setError('Pilih Triwulan dari daftar yang tersedia.');
                $validationTw->setFormula1('"TW I,TW II,TW III,TW IV"');

                $highestRow = $sheet->getHighestRow();

                // Group rows 1 to 7 so they can be collapsed to save space
                for ($r = 1; $r <= 7; $r++) {
                    $sheet->getRowDimension($r)->setOutlineLevel(1);
                    $sheet->getRowDimension($r)->setVisible(false);
                    $sheet->getRowDimension($r)->setCollapsed(true);
                }
                $sheet->setShowSummaryBelow(false);

                // Freeze Panes (Header row 1-10)
                $sheet->freezePane('A11');

                // Set Number Format
                $sheet->getStyle('B4:D4')->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('J5:L7')->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('G11:R' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');

                // Instead of Worksheet Protection (which blocks row deletion),
                // we use Data Validation to prevent typing in master data columns.
                $readOnlyValidation = new DataValidation();
                $readOnlyValidation->setType(DataValidation::TYPE_CUSTOM);
                $readOnlyValidation->setErrorStyle(DataValidation::STYLE_STOP);
                $readOnlyValidation->setAllowBlank(true);
                $readOnlyValidation->setShowErrorMessage(true);
                $readOnlyValidation->setErrorTitle('Kolom Terkunci');
                $readOnlyValidation->setError('Data master ini tidak boleh diubah. Jika kendaraan ini tidak diperlukan, Anda dapat menghapus seluruh baris.');
                $readOnlyValidation->setFormula1('FALSE'); // Nothing is valid to type

                // Apply to ID, NO, JENIS RANDIS, NOPOL, JENIS BBM individually to ensure it applies to all columns
                $sheet->setDataValidation("A11:A{$highestRow}", clone $readOnlyValidation);
                $sheet->setDataValidation("B11:B{$highestRow}", clone $readOnlyValidation);
                $sheet->setDataValidation("D11:D{$highestRow}", clone $readOnlyValidation);
                $sheet->setDataValidation("E11:E{$highestRow}", clone $readOnlyValidation);
                $sheet->setDataValidation("F11:F{$highestRow}", clone $readOnlyValidation);
            },
        ];
    }
}
