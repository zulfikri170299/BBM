<?php

namespace App\Models;

use App\Traits\FilterableByYear;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMeterReading extends Model
{
    use HasFactory, FilterableByYear;

    protected $fillable = [
        'tanggal',
        'jenis_bbm',
        'meter_awal',
        'meter_akhir',
        'keterangan',
    ];
}
