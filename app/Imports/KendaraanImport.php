<?php

namespace App\Imports;

use App\Models\Kendaraan;
use App\Models\Satker;
use App\Models\LogAktivitas;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class KendaraanImport implements ToCollection, WithHeadingRow
{
    public $successCount = 0;
    public $updatedCount = 0;
    public $skippedCount = 0;
    public $errors = [];
    public $duplicates = [];
    public $newEntries = [];
    
    private $satkerId;
    private $duplicateAction; // 'skip', 'update', or 'preview'
    private $useSatkerColumn; // whether to read satker from Excel column

    public function __construct($satkerId = null, $duplicateAction = 'skip', $useSatkerColumn = false)
    {
        $this->satkerId = $satkerId;
        $this->duplicateAction = $duplicateAction;
        $this->useSatkerColumn = $useSatkerColumn;
    }

    /**
     * Header row is at row 2 (row 1 may be empty/title)
     */
    public function headingRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        // Debug: log the headers and first row
        Log::info('KendaraanImport: Total rows received: ' . $rows->count());
        if ($rows->count() > 0) {
            Log::info('KendaraanImport: First row keys: ' . implode(', ', array_keys($rows->first()->toArray())));
            Log::info('KendaraanImport: First row values: ' . json_encode($rows->first()->toArray()));
        }

        foreach ($rows as $index => $row) {
            $rowNum = $index + 3; // headingRow=2, so data starts at row 3

            // Read columns (support multiple naming conventions)
            $nopol = $row['nopol'] ?? $row['no_polisi'] ?? $row['no polisi'] ?? $row['nomor_polisi'] ?? null;
            $jenisKendaraan = $row['jenis_kendaraan'] ?? $row['jenis kendaraan'] ?? $row['jenis'] ?? $row['tipe'] ?? $row['tipe_kendaraan'] ?? null;
            $jenisBbm = $row['jenis_bbm'] ?? $row['jenis bbm'] ?? $row['bbm'] ?? $row['bahan_bakar'] ?? null;
            
            // SATKER column (for admin import)
            $satkerName = null;
            if ($this->useSatkerColumn) {
                $satkerName = $row['satker'] ?? $row['satuan_kerja'] ?? $row['satuan kerja'] ?? $row['nama_satker'] ?? null;
                $satkerName = $satkerName ? trim($satkerName) : null;
            }

            // Jumlah liter (for topup import - backward compatibility)
            $jumlahLiter = $row['jumlah_liter'] ?? $row['jumlah liter'] ?? $row['liter'] ?? null;

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
                $this->errors[] = "Baris {$rowNum}: Kolom NOPOL kosong.";
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

            // Resolve satker_id
            $resolvedSatkerId = $this->satkerId;
            if ($this->useSatkerColumn) {
                if (empty($satkerName)) {
                    $this->errors[] = "Baris {$rowNum}: Kolom SATKER kosong.";
                    continue;
                }
                $satker = Satker::where('nama_satker', 'LIKE', $satkerName)->first();
                if (!$satker) {
                    $this->errors[] = "Baris {$rowNum}: Satker '{$satkerName}' tidak ditemukan.";
                    continue;
                }
                $resolvedSatkerId = $satker->id;
            }

            if (!$resolvedSatkerId) {
                $this->errors[] = "Baris {$rowNum}: Satker tidak ditemukan.";
                continue;
            }

            // Check for duplicate no_polisi
            $existing = Kendaraan::where('no_polisi', $nopol)->first();
            if ($existing) {
                $changes = [];
                if ($existing->jenis_kendaraan !== $jenisKendaraan) {
                    $changes[] = [
                        'field' => 'Jenis Kendaraan',
                        'old' => $existing->jenis_kendaraan,
                        'new' => $jenisKendaraan,
                    ];
                }
                if ($existing->jenis_bbm !== $jenisBbm) {
                    $changes[] = [
                        'field' => 'Jenis BBM',
                        'old' => $existing->jenis_bbm,
                        'new' => $jenisBbm,
                    ];
                }
                if ($existing->satker_id != $resolvedSatkerId) {
                    $oldSatker = $existing->satker ? $existing->satker->nama_satker : '-';
                    $newSatker = Satker::find($resolvedSatkerId)?->nama_satker ?? '-';
                    $changes[] = [
                        'field' => 'Satker',
                        'old' => $oldSatker,
                        'new' => $newSatker,
                    ];
                }

                if ($this->duplicateAction === 'preview') {
                    $this->duplicates[] = [
                        'row' => $rowNum,
                        'no_polisi' => $nopol,
                        'old_jenis_kendaraan' => $existing->jenis_kendaraan,
                        'old_jenis_bbm' => $existing->jenis_bbm,
                        'old_satker' => $existing->satker ? $existing->satker->nama_satker : '-',
                        'new_jenis_kendaraan' => $jenisKendaraan,
                        'new_jenis_bbm' => $jenisBbm,
                        'new_satker' => $this->useSatkerColumn ? $satkerName : ($existing->satker ? $existing->satker->nama_satker : '-'),
                        'changes' => $changes,
                        'has_changes' => count($changes) > 0,
                    ];
                    continue;
                } elseif ($this->duplicateAction === 'update') {
                    $updateData = [
                        'jenis_kendaraan' => $jenisKendaraan,
                        'jenis_bbm' => $jenisBbm,
                    ];
                    if ($this->useSatkerColumn) {
                        $updateData['satker_id'] = $resolvedSatkerId;
                    }
                    $existing->update($updateData);
                    $this->updatedCount++;
                    continue;
                } else {
                    // Skip
                    $this->skippedCount++;
                    continue;
                }
            }

            if ($this->duplicateAction === 'preview') {
                // For preview mode, collect new entries data
                $satkerNama = $this->useSatkerColumn ? $satkerName : (Satker::find($resolvedSatkerId)?->nama_satker ?? '-');
                $this->newEntries[] = [
                    'row' => $rowNum,
                    'no_polisi' => $nopol,
                    'jenis_kendaraan' => $jenisKendaraan,
                    'jenis_bbm' => $jenisBbm,
                    'satker' => $satkerNama,
                ];
                $this->successCount++;
                continue;
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
                'satker_id' => $resolvedSatkerId,
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
            if ($this->skippedCount > 0) $msg .= " {$this->skippedCount} dilewati";
            
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => $msg,
            ]);
        }
    }
}
