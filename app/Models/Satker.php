<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kendaraans()
    {
        return $this->hasMany(Kendaraan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function getOrderedForRendis()
    {
        $order = [
            'SPRIPIM',
            'ITWASDA',
            'BIRO OPS',
            'BIRO RENA',
            'BIRO SDM',
            'BIRO LOGISTIK',
            'DIT INTELKAM',
            'DIT RESKRIMUM',
            'DIT RESKRIMSUS',
            'DIT RES PPA PPO',
            'DIT RESNARKOBA',
            'DIT TAHTI',
            'DIT BINMAS',
            'BID PROPAM',
            'BID HUMAS',
            'BID TIK',
            'BID KEU',
            'BID KUM',
            'BID DOKKES',
            'RUMKIT',
            'YANMA',
            'SETUM',
            'SPKT'
        ];

        return self::where('nama_satker', '!=', 'SPN')
            ->get()
            ->sortBy(function($satker) use ($order) {
                $pos = array_search(strtoupper(trim($satker->nama_satker)), $order);
                return $pos === false ? 999 : $pos;
            })
            ->values();
    }

    public static function sortKendaraansBySatker($kendaraansBySatker)
    {
        $orderedSatkers = self::getOrderedForRendis();
        
        $sorted = collect();
        foreach ($orderedSatkers as $satker) {
            if ($kendaraansBySatker->has($satker->id)) {
                $sorted->put($satker->id, $kendaraansBySatker->get($satker->id));
            } elseif ($kendaraansBySatker instanceof \Illuminate\Support\Collection && isset($kendaraansBySatker[$satker->id])) {
                $sorted->put($satker->id, $kendaraansBySatker[$satker->id]);
            }
        }
        
        return $sorted;
    }
}
