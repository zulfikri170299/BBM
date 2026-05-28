<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasSpbp extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'pangkat_nrp',
        'urutan',
    ];
}
