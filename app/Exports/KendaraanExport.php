<?php

namespace App\Exports;

use App\Models\Kendaraan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KendaraanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $row = 0;
    private $satkerId;

    public function __construct($satkerId = null)
    {
        $this->satkerId = $satkerId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Kendaraan::with('satker');
        if ($this->satkerId) {
            $query->where('satker_id', $this->satkerId);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'SATKER',
            'KODE',
            'JENIS KENDARAAN',
            'NOPOL',
            'JENIS BBM',
        ];
    }

    public function map($kendaraan): array
    {
        return [
            ++$this->row,
            $kendaraan->satker->nama_satker ?? '-',
            $kendaraan->kode_kendaraan ?? '-',
            $kendaraan->jenis_kendaraan,
            $kendaraan->no_polisi,
            $kendaraan->jenis_bbm,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
