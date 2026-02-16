<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatTopup extends Model
{
    protected $fillable = [
        'kendaraan_id',
        'user_id',
        'jumlah',
        'metode',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
