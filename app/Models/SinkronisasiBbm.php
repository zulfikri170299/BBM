<?php

namespace App\Models;

use App\Traits\FilterableByYear;

use Illuminate\Database\Eloquent\Model;

class SinkronisasiBbm extends Model
{
    use FilterableByYear;

    protected $fillable = [
        'petugas_id',
        'stok_awal_pertamax',
        'stok_awal_dex',
    ];

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
