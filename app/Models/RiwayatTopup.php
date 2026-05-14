<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class RiwayatTopup extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('hide_developer_topup_history', function (Builder $builder) {
            $builder->whereHas('user', function ($query) {
                $query->where(function ($q) {
                    $q->where('is_developer', false);
                    if (auth()->check()) {
                        $q->orWhere('id', auth()->id());
                    }
                });
            });
        });
    }
    protected $fillable = [
        'satker_id',
        'kendaraan_id',
        'personel_id',
        'user_id',
        'jumlah',
        'tipe',
        'metode',
        'status',
        'jenis_bbm',
        'keterangan',
        'created_at',
        'updated_at',
    ];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function personel()
    {
        return $this->belongsTo(Personel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
