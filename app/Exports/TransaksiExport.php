<?php

namespace App\Exports;

use App\Models\TransaksiBbm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transaksi;

    public function __construct($transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function collection()
    {
        return $this->transaksi;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Satker',
            'No Polisi',
            'Jenis BBM',
            'Liter',
            'Harga/L',
            'Total',
            'Petugas',
            'Driver (Personel)',
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->id,
            $transaksi->created_at->format('d/m/Y H:i'),
            $transaksi->kendaraan->satker->nama_satker,
            $transaksi->kendaraan->no_polisi,
            $transaksi->kendaraan->jenis_bbm,
            $transaksi->liter,
            $transaksi->harga_per_liter,
            $transaksi->total,
            $transaksi->petugas->name,
            $transaksi->personel->nama ?? '-', // Assuming relation exists or we used user logic
        ];
    }
}
