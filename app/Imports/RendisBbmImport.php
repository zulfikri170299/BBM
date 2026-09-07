<?php

namespace App\Imports;

use App\Models\Kendaraan;
use App\Models\RendisBbm;
use App\Models\RendisKendaraan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Exception;

class RendisBbmImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Pengecekan dasar struktur file
        if (count($rows) < 10) {
            throw new Exception("Format Excel tidak valid. Pastikan Anda menggunakan Template yang benar.");
        }

        // Ambil data Umum dari baris yang ditentukan (indeks mulai 0)
        // Row 3 (Index 2): Triwulan di B (1), Tahun di D (3)
        $triwulan = trim($rows[2][1] ?? '');
        $tahun = trim($rows[2][3] ?? '');

        // Row 4 (Index 3): Beli Ptx B (1), Beli Dex D (3), Susut F (5)
        $pembelianPertamax = floatval($rows[3][1] ?? 0);
        $pembelianDex = floatval($rows[3][3] ?? 0);
        $susut = floatval($rows[3][5] ?? 1.5);

        // Row 5 (Index 4): Hari B1 Ops C (2), Staff E (4), Pimpinan G (6)
        $b1Ops = intval($rows[4][2] ?? 0);
        $b1Staff = intval($rows[4][4] ?? 0);
        $b1Pim = intval($rows[4][6] ?? 0);

        // Row 6 (Index 5): Hari B2 Ops C (2), Staff E (4), Pimpinan G (6)
        $b2Ops = intval($rows[5][2] ?? 0);
        $b2Staff = intval($rows[5][4] ?? 0);
        $b2Pim = intval($rows[5][6] ?? 0);

        // Row 7 (Index 6): Hari B3 Ops C (2), Staff E (4), Pimpinan G (6)
        $b3Ops = intval($rows[6][2] ?? 0);
        $b3Staff = intval($rows[6][4] ?? 0);
        $b3Pim = intval($rows[6][6] ?? 0);

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
                throw new Exception("Data Rendis BBM untuk {$triwulan} {$tahun} sudah ada. Silakan gunakan fitur Edit atau Hapus data yang sudah ada terlebih dahulu.");
            }

            // Buat Rendis Induk
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

            $now = now();
            $bulkData = [];

            // Proses Kendaraan mulai dari Row 10 (Index 9)
            // A=0: ID Kendaraan
            // G=6: LPH B1
            // J=9: LPH B2
            // M=12: LPH B3
            
            $kendaraanIds = [];
            for ($i = 9; $i < count($rows); $i++) {
                $row = $rows[$i];
                $id = trim($row[0] ?? '');
                if ($id && is_numeric($id)) {
                    $kendaraanIds[] = $id;
                }
            }
            $kendaraansMap = Kendaraan::whereIn('id', $kendaraanIds)->get()->keyBy('id');

            for ($i = 9; $i < count($rows); $i++) {
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
                $uraianFinal = $uraianExcel ?: ($kendaraan->kategori_kendaraan ?? 'Operasional');
                $kat = strtolower($uraianFinal);
                
                $h1 = $kat == 'pimpinan' ? $b1Pim : ($kat == 'staff' ? $b1Staff : $b1Ops);
                $h2 = $kat == 'pimpinan' ? $b2Pim : ($kat == 'staff' ? $b2Staff : $b2Ops);
                $h3 = $kat == 'pimpinan' ? $b3Pim : ($kat == 'staff' ? $b3Staff : $b3Ops);

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

            foreach (array_chunk($bulkData, 100) as $chunk) {
                RendisKendaraan::insert($chunk);
            }
        });
    }
}
