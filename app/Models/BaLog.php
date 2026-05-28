<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaLog extends Model
{
    protected $fillable = [
        'satker_id',
        'bulan',
        'tahun',
        'total_pertamax',
        'total_dex',
        'file_path',
    ];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }
}
