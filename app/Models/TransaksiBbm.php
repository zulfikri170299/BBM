<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBbm extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function personel()
    {
        return $this->belongsTo(Personel::class);
    }
}
