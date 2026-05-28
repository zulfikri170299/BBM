<?php

namespace App\Exports;

use App\Models\Personel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PersonelExport implements FromCollection, WithHeadings, WithMapping
{
    protected $satkerId;

    public function __construct($satkerId = null)
    {
        $this->satkerId = $satkerId;
    }

    public function collection()
    {
        $query = Personel::with('satker');
        
        if ($this->satkerId) {
            $query->where('satker_id', $this->satkerId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Satker',
            'Nama',
            'NRP/NIP',
            'Saldo',
            'Jenis BBM',
            'PIN',
            'Barcode',
            'Dibuat Pada'
        ];
    }

    public function map($personel): array
    {
        return [
            $personel->id,
            $personel->satker->nama_satker ?? '-',
            $personel->nama,
            $personel->nrp,
            $personel->saldo,
            $personel->jenis_bbm,
            $personel->pin,
            $personel->barcode,
            $personel->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
