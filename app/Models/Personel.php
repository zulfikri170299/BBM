<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personel extends Model
{
    use HasFactory;

    protected $fillable = [
        'satker_id',
        'user_id',
        'nama',
        'nrp',
        'saldo',
        'jenis_bbm',
        'pin',
        'barcode',
    ];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateUniquePin()
    {
        do {
            $pin = sprintf("%06d", mt_rand(0, 999999));
        } while (self::where('pin', $pin)->exists());

        return $pin;
    }
    public function sentTransfers()
    {
        return $this->hasMany(RiwayatTransferAntarPersonel::class, 'sender_id');
    }

    public function receivedTransfers()
    {
        return $this->hasMany(RiwayatTransferAntarPersonel::class, 'receiver_id');
    }

    protected static function booted()
    {
        static::saving(function ($personel) {
            // Jangan kosongkan jenis_bbm meskipun saldo <= 0
            // agar riwayat jenis BBM tetap dapat ditampilkan
            // if ($personel->saldo <= 0) {
            //     $personel->jenis_bbm = null;
            // }
        });
    }
}
