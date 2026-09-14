<?php

namespace App\Models;

use App\Traits\FilterableByYear;

use Illuminate\Database\Eloquent\Model;

class PembelianBbm extends Model
{
    use FilterableByYear;

    protected $fillable = [
        'tanggal',
        'jenis_bbm',
        'jumlah',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
