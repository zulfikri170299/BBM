<?php

namespace App\Models;

use App\Traits\FilterableByYear;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Catatan extends Model
{
    use HasFactory, FilterableByYear;

    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'warna',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
