<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendisBbm extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static $twBulanMap = [
        'TW I'   => ['Januari', 'Februari', 'Maret'],
        'TW II'  => ['April', 'Mei', 'Juni'],
        'TW III' => ['Juli', 'Agustus', 'September'],
        'TW IV'  => ['Oktober', 'November', 'Desember'],
    ];

    public function getNamaBulanAttribute()
    {
        return self::$twBulanMap[$this->triwulan] ?? ['Bulan 1', 'Bulan 2', 'Bulan 3'];
    }

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function rendisKendaraans()
    {
        return $this->hasMany(RendisKendaraan::class);
    }
}
