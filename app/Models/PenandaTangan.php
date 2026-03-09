<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenandaTangan extends Model
{
    use HasFactory;

    protected $table = 'penanda_tangans';

    protected $fillable = [
        'user_id',
        'satker_id',
        'nama',
        'jabatan',
        'jabatan2',
        'pangkat',
        'nrp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    /**
     * Get the active signer for a given satker (or global for super admin).
     * Priority: satker-specific > global
     */
    public static function getForPdf($satkerId = null)
    {
        if ($satkerId) {
            $satkerSigner = static::where('satker_id', $satkerId)->latest()->first();
            if ($satkerSigner) return $satkerSigner;
        }

        // Fallback to global signer (super admin)
        return static::whereNull('satker_id')->latest()->first();
    }
}
