<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->is_developer) {
                return false; // Cancel creation if user is developer
            }
        });

        static::addGlobalScope('hide_developer_activity', function (Builder $builder) {
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
