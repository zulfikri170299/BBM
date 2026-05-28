<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBbm extends Model
{
    use HasFactory;

    protected $fillable = [
        'satker_id',
        'kendaraan_id',
        'personel_id',
        'petugas_id',
        'nama_driver',
        'tanggal',
        'liter',
        'harga_per_liter',
        'total',
        'jenis_bbm',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function personel()
    {
        return $this->belongsTo(Personel::class);
    }
}
