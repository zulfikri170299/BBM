<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendisKendaraan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function rendisBbm()
    {
        return $this->belongsTo(RendisBbm::class);
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}
