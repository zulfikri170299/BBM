<?php

namespace App\Models;

use App\Traits\FilterableByYear;

use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    use FilterableByYear;

    protected $guarded = ['id'];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function adminBayar()
    {
        return $this->belongsTo(User::class, 'admin_bayar_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'satker_id', 'satker_id')->where('no_polisi', $this->nopol);
    }

    public function getKendaraanAttribute()
    {
        return Kendaraan::where('satker_id', $this->satker_id)
            ->where('no_polisi', $this->nopol)
            ->first();
    }
}
