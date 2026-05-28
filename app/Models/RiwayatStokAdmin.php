<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class RiwayatStokAdmin extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('hide_developer_stock_history', function (Builder $builder) {
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
        'user_id',
        'jenis_bbm',
        'jumlah',
        'tipe',
        'keterangan',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
