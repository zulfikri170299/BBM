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
}
