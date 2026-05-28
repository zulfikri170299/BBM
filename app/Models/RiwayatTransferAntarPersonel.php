<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatTransferAntarPersonel extends Model
{
    use HasFactory;

    protected $table = 'riwayat_transfer_antar_personels';

    protected $fillable = [
        'satker_id',
        'sender_id',
        'receiver_id',
        'target_kendaraan_id',
        'jumlah',
        'jenis_bbm',
        'keterangan',
    ];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function sender()
    {
        return $this->belongsTo(Personel::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Personel::class, 'receiver_id');
    }

    public function targetKendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'target_kendaraan_id');
    }
}
