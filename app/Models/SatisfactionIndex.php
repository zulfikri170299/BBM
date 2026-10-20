<?php

namespace App\Models;

use App\Traits\FilterableByYear;

use Illuminate\Database\Eloquent\Model;

class SatisfactionIndex extends Model
{
    use FilterableByYear;

    protected $fillable = [
        'user_id',
        'rating',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::saved(function ($model) {
            $cacheKey = 'user_sat_' . $model->user_id . '_' . now()->format('Ym');
            \Illuminate\Support\Facades\Cache::forget($cacheKey);
            \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->endOfMonth()->addDay());
        });

        static::deleted(function ($model) {
            $cacheKey = 'user_sat_' . $model->user_id . '_' . now()->format('Ym');
            \Illuminate\Support\Facades\Cache::forget($cacheKey);
        });
    }
}
