<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'saldo' => 'integer',
        'pending_changes' => 'array',
        'disetujui_pada' => 'datetime',
    ];

    public function diajukanOleh()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status_approval', 'disetujui');
    }

    public function scopePending($query)
    {
        return $query->where('status_approval', 'pending');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_approval', 'ditolak');
    }

    protected static function booted()
    {
        static::deleting(function ($kendaraan) {
            // Pertahankan riwayat transaksi BBM agar tidak ikut terhapus
            \App\Models\TransaksiBbm::where('kendaraan_id', $kendaraan->id)->each(function ($t) use ($kendaraan) {
                $t->update([
                    'nopol' => $t->nopol ?: $kendaraan->no_polisi,
                    'kendaraan_id' => null,
                ]);
            });

            // Pertahankan riwayat topup
            \App\Models\RiwayatTopup::where('kendaraan_id', $kendaraan->id)->update([
                'kendaraan_id' => null,
            ]);
        });
    }

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function hutangs()
    {
        return $this->hasMany(Hutang::class, 'satker_id', 'satker_id')
            ->where('nopol', $this->no_polisi);
    }

    public function hutangBelumLunas()
    {
        $cleanNopol = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($this->no_polisi));
        return Hutang::where('satker_id', $this->satker_id)
            ->where('status', 'belum_dibayar')
            ->where(function ($q) use ($cleanNopol) {
                $q->where('nopol', $this->no_polisi)
                  ->orWhereRaw("TRIM(UPPER(nopol)) = ?", [trim(strtoupper($this->no_polisi))])
                  ->orWhereRaw("REPLACE(REPLACE(REPLACE(UPPER(nopol), ' ', ''), '-', ''), '.', '') = ?", [$cleanNopol]);
            });
    }

    public function hasUnpaidBon(): bool
    {
        if (array_key_exists('has_unpaid_bon', $this->attributes)) {
            return (bool) $this->attributes['has_unpaid_bon'];
        }
        return $this->hutangBelumLunas()->exists();
    }

    public function totalUnpaidBon(): int
    {
        if (array_key_exists('unpaid_bon_total', $this->attributes)) {
            return (int) $this->attributes['unpaid_bon_total'];
        }
        return (int) $this->hutangBelumLunas()->sum('jumlah_bon');
    }

    public function getIsLockedAttribute(): bool
    {
        return $this->hasUnpaidBon();
    }

    public function getUnpaidBonTotalAttribute(): int
    {
        return $this->totalUnpaidBon();
    }

    /**
     * Melunasi BON / hutang secara otomatis jika saldo kendaraan mencukupi
     * Dipotong berurutan (FIFO: tanggal_bon terlama / id terlama)
     *
     * @param int|null $userId User pemroses / pemotong
     * @return array Data bon yang berhasil dilunasi
     */
    public function autoSettleBon($userId = null): array
    {
        $cleanNopol = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($this->no_polisi));
        $unpaidHutangs = Hutang::where('satker_id', $this->satker_id)
            ->where('status', 'belum_dibayar')
            ->where(function ($q) use ($cleanNopol) {
                $q->where('nopol', $this->no_polisi)
                  ->orWhereRaw("TRIM(UPPER(nopol)) = ?", [trim(strtoupper($this->no_polisi))])
                  ->orWhereRaw("REPLACE(REPLACE(REPLACE(UPPER(nopol), ' ', ''), '-', ''), '.', '') = ?", [$cleanNopol]);
            })
            ->orderBy('tanggal_bon', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($unpaidHutangs->isEmpty()) {
            return [];
        }

        $adminUser = $userId ? User::find($userId) : (auth()->user() ?? User::where('role', 'super_admin')->first());
        $adminUserId = $adminUser?->id;

        $settled = [];

        foreach ($unpaidHutangs as $hutang) {
            $this->refresh();
            if ($this->saldo < $hutang->jumlah_bon) {
                continue;
            }

            \Illuminate\Support\Facades\DB::transaction(function () use ($hutang, $adminUserId, &$settled) {
                // 1. Potong saldo kendaraan
                $this->decrement('saldo', $hutang->jumlah_bon);

                // 2. Tandai hutang sebagai sudah dibayar
                $hutang->update([
                    'status' => 'sudah_dibayar',
                    'admin_bayar_id' => $adminUserId,
                    'tanggal_bayar' => now(),
                ]);

                // 3. Catat di RiwayatTopup untuk laporan mutasi dan riwayat pengurangan saldo
                RiwayatTopup::create([
                    'satker_id' => $hutang->satker_id,
                    'kendaraan_id' => $this->id,
                    'user_id' => $adminUserId,
                    'jumlah' => $hutang->jumlah_bon,
                    'tipe' => 'keluar',
                    'metode' => 'Potong Saldo',
                    'status' => 'success',
                    'jenis_bbm' => $this->jenis_bbm ?: $hutang->jenis_bbm,
                    'keterangan' => "Pelunasan otomatis bon hutang (Nopol: {$hutang->nopol}, Jenis BBM: {$hutang->jenis_bbm})",
                ]);

                // 4. Catat ke Log Aktivitas
                LogAktivitas::create([
                    'user_id' => $adminUserId,
                    'aktivitas' => "Sistem otomatis memotong saldo kendaraan {$this->no_polisi} sebesar {$hutang->jumlah_bon} L untuk pelunasan BON (ID: {$hutang->id})"
                ]);

                $settled[] = [
                    'hutang_id' => $hutang->id,
                    'jumlah_bon' => $hutang->jumlah_bon,
                    'nopol' => $hutang->nopol,
                ];
            });
        }

        return $settled;
    }

    /**
     * Memproses pelunasan otomatis untuk seluruh kendaraan yang memenuhi syarat
     *
     * @param int|null $satkerId Batasi ke satker tertentu jika diisi
     * @return int Jumlah total BON yang berhasil dilunasi
     */
    public static function autoSettleAllEligible($satkerId = null): int
    {
        $query = Hutang::where('status', 'belum_dibayar');
        if ($satkerId) {
            $query->where('satker_id', $satkerId);
        }
        $unpaidList = $query->select('satker_id', 'nopol')->distinct()->get();

        $totalSettled = 0;
        foreach ($unpaidList as $item) {
            $cleanNopol = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($item->nopol));
            $kendaraan = self::where('satker_id', $item->satker_id)
                ->where(function ($q) use ($item, $cleanNopol) {
                    $q->where('no_polisi', $item->nopol)
                      ->orWhereRaw("TRIM(UPPER(no_polisi)) = ?", [trim(strtoupper($item->nopol))])
                      ->orWhereRaw("REPLACE(REPLACE(REPLACE(UPPER(no_polisi), ' ', ''), '-', ''), '.', '') = ?", [$cleanNopol]);
                })
                ->first();

            if ($kendaraan && $kendaraan->saldo > 0) {
                $settled = $kendaraan->autoSettleBon();
                $totalSettled += count($settled);
            }
        }

        return $totalSettled;
    }

    public static function generateUniquePin()
    {
        do {
            $pin = sprintf("%06d", mt_rand(0, 999999));
        } while (self::where('pin', $pin)->exists());

        return $pin;
    }
}
