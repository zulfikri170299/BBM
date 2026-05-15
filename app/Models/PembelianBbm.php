<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianBbm extends Model
{
    protected $fillable = [
        'tanggal',
        'jenis_bbm',
        'jumlah',
    ];
}
