<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sounding extends Model
{
    protected $fillable = [
        'tanggal',
        'jenis_bbm',
        'stok_awal',
        'stok_akhir',
        'pengeluaran_aplikasi',
        'susut',
        'petugas_id',
        'dokumentasi',
    ];

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->susut = $model->stok_awal - $model->pengeluaran_aplikasi - $model->stok_akhir;
        });
    }
}
