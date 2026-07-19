<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminBbmStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_bbm',
        'saldo',
    ];

    protected $casts = [
        'saldo' => 'integer',
    ];
}
