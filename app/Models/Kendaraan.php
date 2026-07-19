<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'saldo' => 'integer',
    ];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public static function generateUniquePin()
    {
        do {
            $pin = sprintf("%06d", mt_rand(0, 999999));
        } while (self::where('pin', $pin)->exists());

        return $pin;
    }
}
