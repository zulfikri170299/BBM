<?php

namespace App\Imports;

use App\Models\Kendaraan;
use App\Models\RiwayatTopup;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Notifications\TopupNotification;

class TopupSaldoImport implements ToCollection, WithHeadingRow, WithStartRow
{
    public $results = [];
    public $errors = [];
    public $successCount = 0;
    public $satkerSummary = []; // [satker_id => ['Pertamax' => 0, 'Pertamina Dex' => 0]]

    /**
     * Header ada di row 2 (row 1 kosong)
     */
    public function headingRow(): int
    {
        return 2;
    }

    /**
     * Data mulai dari row 3
     */
    public function startRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 3; // data mulai dari row 3

            // DEBUG: Log actual keys untuk troubleshooting
            if ($index === 0) {
                \Log::info('TopupSaldoImport KEYS: ' . json_encode($row->keys()->toArray()));
                \Log::info('TopupSaldoImport ROW DATA: ' . json_encode($row->toArray()));
            }

            // Ambil nopol dari kolom NOPOL atau no_polisi
            $nopol = $row['nopol'] ?? $row['no_polisi'] ?? $row['no polisi'] ?? null;
            // Ambil kode dari kolom KODE KENDARAAN
            $kode = $row['kode_kendaraan'] ?? $row['kode'] ?? null;
            // Ambil jumlah dari kolom JUMLAH LITER atau jumlah
            $jumlah = $row['jumlah_liter'] ?? $row['jumlah liter'] ?? $row['jumlah'] ?? $row['liter'] ?? $row['saldo'] ?? null;

            if ((empty($nopol) && empty($kode)) || empty($jumlah)) {
                $this->errors[] = "Baris {$rowNum}: Kolom NOPOL/KODE KENDARAAN atau JUMLAH LITER kosong.";
                continue;
            }

            if (!is_numeric($jumlah) || $jumlah <= 0) {
                $this->errors[] = "Baris {$rowNum}: Jumlah harus angka positif (dapat: {$jumlah}).";
                continue;
            }

            if ($jumlah > 10000) {
                $this->errors[] = "Baris {$rowNum}: Jumlah maksimal 10.000 liter (dapat: {$jumlah}).";
                continue;
            }

            $kendaraan = null;
            if (!empty($nopol)) {
                $kendaraan = Kendaraan::where('no_polisi', trim($nopol))->first();
            }

            if (!$kendaraan && !empty($kode)) {
                $kendaraan = Kendaraan::where('kode_kendaraan', trim($kode))->first();
            }

            if (!$kendaraan) {
                $identifier = $nopol ?: $kode;
                $this->errors[] = "Baris {$rowNum}: Kendaraan '{$identifier}' tidak ditemukan.";
                continue;
            }

            // --- CHECK & DEDUCT ADMIN STOCK ---
            $adminStock = \App\Models\AdminBbmStock::where('jenis_bbm', $kendaraan->jenis_bbm)->first();
            if (!$adminStock || $adminStock->saldo < $jumlah) {
                $this->errors[] = "Baris {$rowNum}: Stok Pusat untuk {$kendaraan->jenis_bbm} tidak cukup. Tersedia: " . ($adminStock ? $adminStock->saldo : 0) . " L.";
                continue;
            }

            // Potong Stok Admin
            $adminStock->decrement('saldo', $jumlah);

            // Riwayat Stok Admin
            \App\Models\RiwayatStokAdmin::create([
                'user_id' => auth()->id(),
                'jenis_bbm' => $kendaraan->jenis_bbm,
                'jumlah' => $jumlah,
                'tipe' => 'keluar',
                'keterangan' => "Top-up via Import untuk kendaraan {$kendaraan->no_polisi}",
            ]);
            // ----------------------------------

            $kendaraan->increment('saldo', $jumlah);

            // Log Riwayat Topup
            RiwayatTopup::create([
                'satker_id' => $kendaraan->satker_id,
                'kendaraan_id' => $kendaraan->id,
                'user_id' => auth()->id(),
                'jumlah' => $jumlah,
                'jenis_bbm' => $kendaraan->jenis_bbm,
                'tipe' => 'masuk',
                'metode' => 'IMPORT',
            ]);

            // Kirim Notifikasi ke Admin Satker terkait
            $adminSatkers = User::where('satker_id', $kendaraan->satker_id)
                ->where('role', 'admin_satker')
                ->get();

            foreach ($adminSatkers as $admin) {
                $admin->notify(new TopupNotification([
                    'title' => 'Penerimaan Saldo (Import)',
                    'message' => "Super Admin telah melakukan top-up saldo via Import untuk kendaraan {$kendaraan->no_polisi}.",
                    'amount' => $jumlah,
                    'no_polisi' => $kendaraan->no_polisi,
                ]));
            }

            $this->successCount++;

            $satkerId = $kendaraan->satker_id;
            $jenisBbm = $kendaraan->jenis_bbm;

            if (!isset($this->satkerSummary[$satkerId])) {
                $this->satkerSummary[$satkerId] = [
                    'Pertamax' => 0,
                    'Pertamina Dex' => 0,
                ];
            }

            // Normalize fuel type name if necessary
            if (stripos($jenisBbm, 'Pertamax') !== false) {
                $this->satkerSummary[$satkerId]['Pertamax'] += $jumlah;
            } elseif (stripos($jenisBbm, 'Dex') !== false) {
                $this->satkerSummary[$satkerId]['Pertamina Dex'] += $jumlah;
            }
            

            $this->results[] = [
                'nopol' => $kendaraan->no_polisi,
                'jumlah' => $jumlah,
                'saldo_baru' => $kendaraan->fresh()->saldo,
            ];
        }
    }
}
