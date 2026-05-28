<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'jenis_bbm',
        'meter_awal',
        'meter_akhir',
        'keterangan',
    ];
}
