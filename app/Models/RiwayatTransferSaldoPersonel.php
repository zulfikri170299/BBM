<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatTransferSaldoPersonel extends Model
{
    use HasFactory;

    protected $table = 'riwayat_transfer_saldo_personels';

    protected $fillable = [
        'satker_id',
        'kendaraan_id',
        'personel_id',
        'jumlah',
        'jenis_bbm',
        'keterangan',
    ];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function personel()
    {
        return $this->belongsTo(Personel::class);
    }
}
