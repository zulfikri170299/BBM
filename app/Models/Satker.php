<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kendaraans()
    {
        return $this->hasMany(Kendaraan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
