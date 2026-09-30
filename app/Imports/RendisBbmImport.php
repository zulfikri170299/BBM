<?php

namespace App\Imports;

use App\Models\Kendaraan;
use App\Models\RendisBbm;
use App\Models\RendisKendaraan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Exception;
use App\Exceptions\ImportConflictException;

class RendisBbmImport implements ToCollection
{
    protected $actionType;

    public function __construct($actionType = null)
    {
        $this->actionType = $actionType;
    }

    public function collection(Collection $rows)
    {
        // Pengecekan dasar struktur file
        if (count($rows) < 10) {
            throw new Exception("Format Excel tidak valid. Pastikan Anda menggunakan Template yang benar.");
        }

        // Ambil data Umum dari baris yang ditentukan (indeks mulai 0)
        // Row 3 (Index 2): Triwulan di B (1), Tahun di C (2)
        // Row 3 (Index 2): Triwulan di B (1)
        $triwulan = trim($rows[2][1] ?? '');
        $tahun = date('Y');

        // Row 6 (Index 5): Beli Ptx B (1), Hari B1 Staff I (8), Ops J (9)
        $pembelianPertamax = floatval($rows[5][1] ?? 0);
        $b1Staff = intval($rows[5][8] ?? 0);
        $b1Ops = intval($rows[5][9] ?? 0);
        $b1Pim = 0; // Legacy

        // Row 7 (Index 6): Beli Dex B (1), Hari B2 Staff I (8), Ops J (9)
        $pembelianDex = floatval($rows[6][1] ?? 0);
        $b2Staff = intval($rows[6][8] ?? 0);
        $b2Ops = intval($rows[6][9] ?? 0);
        $b2Pim = 0;

        // Row 8 (Index 7): Susut B (1), Hari B3 Staff I (8), Ops J (9)
        $susut = floatval($rows[7][1] ?? 1.5);
        $b3Staff = intval($rows[7][8] ?? 0);
        $b3Ops = intval($rows[7][9] ?? 0);
        $b3Pim = 0;

        if (!in_array($triwulan, ['TW I', 'TW II', 'TW III', 'TW IV'])) {
            throw new Exception("Triwulan tidak valid. Harus TW I / TW II / TW III / TW IV");
        }
        if (!$tahun) {
            throw new Exception("Tahun tidak boleh kosong.");
        }

        DB::transaction(function () use ($triwulan, $tahun, $pembelianPertamax, $pembelianDex, $susut, $b1Ops, $b1Staff, $b1Pim, $b2Ops, $b2Staff, $b2Pim, $b3Ops, $b3Staff, $b3Pim, $rows) {
            // Cek jika rendis sudah ada, bisa dilewati atau dilempar error (karena harus unique per TW + Tahun)
            $existing = RendisBbm::where('triwulan', $triwulan)->where('tahun', $tahun)->first();
            if ($existing) {
                if ($this->actionType === 'replace') {
                    // Hapus data lama beserta kendaraannya
                    $existing->delete();
                    $rendisBbm = null; // Akan dibuat baru di bawah
                } elseif ($this->actionType === 'update') {
                    // Update data induk
                    $existing->update([
                        'pembelian_pertamax' => $pembelianPertamax,
                        'pembelian_pertamina_dex' => $pembelianDex,
                        'susut_persen' => $susut,
                        'bulan1_hari_operasional' => $b1Ops,
                        'bulan1_hari_staff' => $b1Staff,
                        'bulan1_hari_pimpinan' => $b1Pim,
                        'bulan2_hari_operasional' => $b2Ops,
                        'bulan2_hari_staff' => $b2Staff,
                        'bulan2_hari_pimpinan' => $b2Pim,
                        'bulan3_hari_operasional' => $b3Ops,
                        'bulan3_hari_staff' => $b3Staff,
                        'bulan3_hari_pimpinan' => $b3Pim,
                    ]);
                    $rendisBbm = $existing;
                } else {
                    throw new ImportConflictException("Data Rendis BBM untuk {$triwulan} {$tahun} sudah ada.");
                }
            }

            if (!isset($rendisBbm)) {
                // Buat Rendis Induk Baru
                $rendisBbm = RendisBbm::create([
                    'triwulan' => $triwulan,
                    'tahun' => $tahun,
                    'pembelian_pertamax' => $pembelianPertamax,
                    'pembelian_pertamina_dex' => $pembelianDex,
                    'susut_persen' => $susut,
                    'bulan1_hari_operasional' => $b1Ops,
                    'bulan1_hari_staff' => $b1Staff,
                    'bulan1_hari_pimpinan' => $b1Pim,
                    'bulan2_hari_operasional' => $b2Ops,
                    'bulan2_hari_staff' => $b2Staff,
                    'bulan2_hari_pimpinan' => $b2Pim,
                    'bulan3_hari_operasional' => $b3Ops,
                    'bulan3_hari_staff' => $b3Staff,
                    'bulan3_hari_pimpinan' => $b3Pim,
                    'is_topup_b1' => false,
                    'is_topup_b2' => false,
                    'is_topup_b3' => false,
                ]);
            }

            $now = now();
            $bulkData = [];

            // Proses Kendaraan mulai dari Row 12 (Index 11)
            // A=0: ID Kendaraan
            // G=6: LPH B1
            // K=10: LPH B2
            // O=14: LPH B3
            
            $kendaraanIds = [];
            for ($i = 11; $i < count($rows); $i++) {
                $row = $rows[$i];
                $id = trim($row[0] ?? '');
                if ($id && is_numeric($id)) {
                    $kendaraanIds[] = $id;
                }
            }
            $kendaraansMap = Kendaraan::whereIn('id', $kendaraanIds)->get()->keyBy('id');

            for ($i = 11; $i < count($rows); $i++) {
                $row = $rows[$i];
                $id = trim($row[0] ?? '');
                if (!$id || !is_numeric($id)) continue;

                $kendaraan = $kendaraansMap->get($id);
                if (!$kendaraan) continue; // Skip if ID doesn't exist

                $lph1 = floatval($row[6] ?? 0);
                $lph2 = floatval($row[10] ?? 0);
                $lph3 = floatval($row[14] ?? 0);

                // Hitung total berdasar hari
                $uraianExcel = trim($row[2] ?? '');
                $uraianFinal = $uraianExcel ?: ($kendaraan->kategori_kendaraan ?? 'Ran Ops');
                $kat = strtolower($uraianFinal);
                
                $h1 = ($kat == 'ran staff' || $kat == 'staff') ? $b1Staff : $b1Ops;
                $h2 = ($kat == 'ran staff' || $kat == 'staff') ? $b2Staff : $b2Ops;
                $h3 = ($kat == 'ran staff' || $kat == 'staff') ? $b3Staff : $b3Ops;

                $b1Total = round($lph1 * $h1);
                $b2Total = round($lph2 * $h2);
                $b3Total = round($lph3 * $h3);
                $jenisBbm = strtolower(str_replace(' ', '_', $kendaraan->jenis_bbm ?? 'pertamax'));

                $bulkData[] = [
                    'rendis_bbm_id' => $rendisBbm->id,
                    'kendaraan_id' => $id,
                    'uraian' => $uraianFinal,
                    'liter_per_hari' => $lph1, // legacy field
                    'liter_per_hari_b2' => $lph2,
                    'liter_per_hari_b3' => $lph3,
                    'bulan1_total' => $b1Total,
                    'bulan2_total' => $b2Total,
                    'bulan3_total' => $b3Total,
                    'total_liter' => $b1Total + $b2Total + $b3Total,
                    'jenis_bbm' => $jenisBbm,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if ($this->actionType === 'update') {
                $importedKendaraanIds = array_column($bulkData, 'kendaraan_id');
                RendisKendaraan::where('rendis_bbm_id', $rendisBbm->id)
                    ->whereNotIn('kendaraan_id', $importedKendaraanIds)
                    ->delete();

                foreach ($bulkData as $data) {
                    RendisKendaraan::updateOrCreate(
                        [
                            'rendis_bbm_id' => $rendisBbm->id,
                            'kendaraan_id' => $data['kendaraan_id']
                        ],
                        $data
                    );
                }
            } else {
                foreach (array_chunk($bulkData, 100) as $chunk) {
                    RendisKendaraan::insert($chunk);
                }
            }
        });
    }
}
