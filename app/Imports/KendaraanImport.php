<?php

namespace App\Imports;

use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class KendaraanImport implements ToCollection, WithHeadingRow
{
    public $successCount = 0;
    public $updatedCount = 0;
    public $skippedCount = 0;
    public $errors = [];
    public $duplicates = [];
    
    private $satkerId;
    private $duplicateAction; // 'skip', 'update', or 'preview'

    public function __construct($satkerId, $duplicateAction = 'skip')
    {
        $this->satkerId = $satkerId;
        $this->duplicateAction = $duplicateAction;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            // Read columns (support multiple naming conventions)
            $nopol = $row['no_polisi'] ?? $row['nopol'] ?? $row['no polisi'] ?? $row['nomor_polisi'] ?? null;
            $jenisKendaraan = $row['jenis_kendaraan'] ?? $row['jenis'] ?? $row['tipe'] ?? $row['tipe_kendaraan'] ?? null;
            $jenisBbm = $row['jenis_bbm'] ?? $row['bbm'] ?? $row['bahan_bakar'] ?? null;

            // Trim whitespace
            $nopol = $nopol ? trim($nopol) : null;
            $jenisKendaraan = $jenisKendaraan ? trim($jenisKendaraan) : null;
            $jenisBbm = $jenisBbm ? trim($jenisBbm) : null;

            // Skip empty rows
            if (empty($nopol) && empty($jenisKendaraan) && empty($jenisBbm)) {
                continue;
            }

            // Validate required fields
            if (empty($nopol)) {
                $this->errors[] = "Baris {$rowNum}: Kolom NO POLISI kosong.";
                continue;
            }

            if (empty($jenisKendaraan)) {
                $this->errors[] = "Baris {$rowNum}: Kolom JENIS KENDARAAN kosong.";
                continue;
            }

            if (empty($jenisBbm)) {
                $this->errors[] = "Baris {$rowNum}: Kolom JENIS BBM kosong.";
                continue;
            }

            // Validate jenis BBM
            $allowedBbm = ['Pertamax', 'Pertamina Dex'];
            $matched = false;
            foreach ($allowedBbm as $bbm) {
                if (strtolower($jenisBbm) === strtolower($bbm)) {
                    $jenisBbm = $bbm;
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                $this->errors[] = "Baris {$rowNum}: Jenis BBM '{$jenisBbm}' tidak valid. Pilih: Pertamax atau Pertamina Dex.";
                continue;
            }

            // Check for duplicate no_polisi
            $existing = Kendaraan::where('no_polisi', $nopol)->first();
            if ($existing) {
                if ($this->duplicateAction === 'preview') {
                    // Preview mode: just collect duplicate info
                    $this->duplicates[] = [
                        'row' => $rowNum,
                        'no_polisi' => $nopol,
                        'old_jenis_kendaraan' => $existing->jenis_kendaraan,
                        'old_jenis_bbm' => $existing->jenis_bbm,
                        'new_jenis_kendaraan' => $jenisKendaraan,
                        'new_jenis_bbm' => $jenisBbm,
                    ];
                    continue;
                } elseif ($this->duplicateAction === 'update') {
                    // Update the existing record
                    $existing->update([
                        'jenis_kendaraan' => $jenisKendaraan,
                        'jenis_bbm' => $jenisBbm,
                    ]);
                    $this->updatedCount++;
                    continue;
                } else {
                    // Skip
                    $this->skippedCount++;
                    continue;
                }
            }

            // Auto-generate kode_kendaraan
            $lastId = Kendaraan::max('id') ?? 0;
            $kodeKendaraan = 'KND-' . str_pad($lastId + 1 + $this->successCount, 5, '0', STR_PAD_LEFT);

            // Auto-generate barcode
            $barcode = strtoupper(Str::random(10));
            while (Kendaraan::where('barcode', $barcode)->exists()) {
                $barcode = strtoupper(Str::random(10));
            }

            // Auto-generate PIN
            $pin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            Kendaraan::create([
                'satker_id' => $this->satkerId,
                'kode_kendaraan' => $kodeKendaraan,
                'no_polisi' => $nopol,
                'jenis_kendaraan' => $jenisKendaraan,
                'jenis_bbm' => $jenisBbm,
                'barcode' => $barcode,
                'pin' => $pin,
                'saldo' => 0,
            ]);

            $this->successCount++;
        }

        // Log activity (not in preview mode)
        if ($this->duplicateAction !== 'preview' && ($this->successCount > 0 || $this->updatedCount > 0)) {
            $msg = "Import Excel kendaraan:";
            if ($this->successCount > 0) $msg .= " {$this->successCount} baru";
            if ($this->updatedCount > 0) $msg .= " {$this->updatedCount} diperbarui";
            
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => $msg,
            ]);
        }
    }
}
