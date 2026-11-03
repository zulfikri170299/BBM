<?php

namespace App\Services;

use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use App\Models\RiwayatTransferSaldoPersonel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmartTransferSyncService
{
    /**
     * Memeriksa dan memulihkan secara otomatis seluruh saldo transfer
     * dari kendaraan yang sudah dihapus berdasarkan riwayat log sistem,
     * memastikan tanggal transfer (created_at) SESUAI DENGAN BULAN & TANGGAL KEJADIAN ASLI.
     *
     * @param int|null $satkerId Jika diisi, prioritaskan satker tertentu. Jika null, proses seluruh satker.
     * @return array Ringkasan pemulihan ['restored' => int, 'fixed_dates' => int, 'details' => array]
     */
    public static function autoHealTransfers(?int $satkerId = null): array
    {
        $restoredCount = 0;
        $fixedDateCount = 0;
        $details = [];

        try {
            // Ambil semua log transfer antar kendaraan
            $query = LogAktivitas::where('aktivitas', 'like', '%Transfer saldo BBM%dari Kendaraan%ke Kendaraan%')
                ->orderBy('created_at', 'asc');

            $logs = $query->get();

            if ($logs->isEmpty()) {
                return ['restored' => 0, 'fixed_dates' => 0, 'details' => []];
            }

            // Cache kendaraan aktif
            $allKendaraans = Kendaraan::all();

            foreach ($logs as $log) {
                // Regex pola: Transfer saldo BBM: 40 L dari Kendaraan (G9 1) ke Kendaraan (POL 903 XXI)
                if (!preg_match('/Transfer saldo BBM:\s*([\d\.]+)\s*L\s*dari\s*Kendaraan\s*\(([^)]+)\)\s*ke\s*Kendaraan\s*\(([^)]+)\)/i', $log->aktivitas, $m)) {
                    continue;
                }

                $liter = (float) $m[1];
                $dariNopol = trim($m[2]);
                $keNopol = trim($m[3]);
                
                // Waktu kejadian asli dari log
                $waktuAsli = $log->created_at ? Carbon::parse($log->created_at) : null;
                if (!$waktuAsli) {
                    continue;
                }

                $normKe = strtoupper(str_replace(' ', '', $keNopol));

                // Cari kendaraan tujuan
                $targetKendaraan = $allKendaraans->first(function ($k) use ($keNopol, $normKe) {
                    $normK = strtoupper(str_replace(' ', '', $k->no_polisi));
                    return $normK === $normKe || $k->no_polisi === $keNopol;
                });

                if (!$targetKendaraan) {
                    continue;
                }

                // Jika filter satker aktif, lewati yang tidak cocok
                if ($satkerId !== null && $targetKendaraan->satker_id != $satkerId) {
                    continue;
                }

                // Cek apakah transfer masuk ini sudah ada di database
                $existingTransfer = RiwayatTransferSaldoPersonel::where('tujuan_kendaraan_id', $targetKendaraan->id)
                    ->where('jumlah', $liter)
                    ->where(function ($q) use ($waktuAsli, $dariNopol) {
                        $startRange = $waktuAsli->copy()->subDays(2)->startOfDay();
                        $endRange = $waktuAsli->copy()->addDays(2)->endOfDay();
                        $q->whereBetween('created_at', [$startRange, $endRange])
                          ->orWhere('keterangan', 'like', "%{$dariNopol}%");
                    })
                    ->first();

                if ($existingTransfer) {
                    // Cek apakah tanggal created_at di record database berbeda bulannya dengan tanggal kejadian asli
                    $currentCreated = Carbon::parse($existingTransfer->created_at);
                    if ($currentCreated->format('Y-m') !== $waktuAsli->format('Y-m')) {
                        // Perbaiki tanggal created_at agar TM jatuh tepat pada bulan terjadinya transfer!
                        DB::table('riwayat_transfer_saldo_personels')
                            ->where('id', $existingTransfer->id)
                            ->update([
                                'created_at' => $waktuAsli->format('Y-m-d H:i:s'),
                                'updated_at' => $waktuAsli->format('Y-m-d H:i:s'),
                            ]);
                        $fixedDateCount++;
                        $details[] = [
                            'action' => 'fixed_date',
                            'transfer_id' => $existingTransfer->id,
                            'dari' => $dariNopol,
                            'ke' => $targetKendaraan->no_polisi,
                            'jumlah' => $liter,
                            'old_date' => $currentCreated->toDateTimeString(),
                            'new_date' => $waktuAsli->toDateTimeString(),
                        ];
                    }
                } else {
                    // Transfer HILANG (karena kendaraan asal dihapus di database lama/backup).
                    // Pulihkan secara otomatis & permanen dengan tanggal kejadian asli!
                    $newId = DB::table('riwayat_transfer_saldo_personels')->insertGetId([
                        'satker_id' => $targetKendaraan->satker_id,
                        'kendaraan_id' => null, // Lepaskan dari foreign key agar permanen & kebal cascade delete
                        'tujuan_kendaraan_id' => $targetKendaraan->id,
                        'personel_id' => null,
                        'jumlah' => $liter,
                        'jenis_bbm' => $targetKendaraan->jenis_bbm ?: 'Pertamina Dex',
                        'keterangan' => "Transfer saldo BBM dari Kendaraan ({$dariNopol})",
                        'created_at' => $waktuAsli->format('Y-m-d H:i:s'),
                        'updated_at' => $waktuAsli->format('Y-m-d H:i:s'),
                    ]);

                    $restoredCount++;
                    $details[] = [
                        'action' => 'restored',
                        'log_id' => $log->id,
                        'transfer_id' => $newId,
                        'dari' => $dariNopol,
                        'ke' => $targetKendaraan->no_polisi,
                        'jumlah' => $liter,
                        'waktu' => $waktuAsli->toDateTimeString(),
                    ];

                    Log::info("SmartTransferSync: Berhasil memulihkan transfer saldo {$liter}L dari {$dariNopol} ke {$targetKendaraan->no_polisi} pada tanggal {$waktuAsli->toDateTimeString()}");
                }
            }
        } catch (\Throwable $e) {
            Log::error("SmartTransferSync Error: " . $e->getMessage());
        }

        return [
            'restored' => $restoredCount,
            'fixed_dates' => $fixedDateCount,
            'details' => $details,
        ];
    }
}
