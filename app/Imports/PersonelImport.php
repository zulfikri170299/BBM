<?php

namespace App\Imports;

use App\Models\Personel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class PersonelImport implements ToCollection, WithHeadingRow
{
    public $imported = 0;
    public $skipped = []; // Store details of skipped rows

    public function headingRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows)
    {
        $satkerId = auth()->user()->satker_id;

        foreach ($rows as $row) {
            // Debugging: Log row keys to ensure we match the right headers
            // \Illuminate\Support\Facades\Log::info($row->keys());
            
            // Map columns based on user's format: NO, SATKER, NAMA, NRP/NIP
            // Laravel Excel slugs headers: "NRP/NIP" -> "nrp_nip" or "nrpnip" usually.
            // "NAMA" -> "nama"
            
            $nama = $row->get('nama');
            // Try distinct slug possibilities for NRP/NIP
            $nrp = $row->get('nrp_nip') ?? $row->get('nrpnip') ?? $row->get('nrp');
            
            if (!$nama || !$nrp) {
                continue;
            }

            // Check if Personel already exists for this Satker
            $existingPersonel = Personel::where('nrp', $nrp)
                                      ->where('satker_id', $satkerId)
                                      ->first();

            if ($existingPersonel) {
                $this->skipped[] = [
                    'nama' => $nama, 
                    'nrp' => $nrp
                ];
                continue; // Skip this row
            }

            // Defaults for missing columns in user's format
            $jenisBbm = $row->get('jenis_bbm') ?? 'Pertamax';
            $saldo = $row->get('saldo') ?? 0;
            $barcode = $row->get('barcode') ?? $nrp;

            // Generate unique PIN since it's not in the file
            $pin = $row->get('pin');
            if (!$pin || $pin == '123456') {
                $pin = Personel::generateUniquePin();
            }

            // Create user account if not exists (preserve old data)
            $user = \App\Models\User::firstOrCreate(
                ['username' => $nrp],
                [
                    'name' => $nama,
                    'email' => $nrp, // NRP as email placeholder
                    'password' => \Illuminate\Support\Facades\Hash::make($nrp),
                    'role' => 'personel',
                    'satker_id' => $satkerId,
                ]
            );

            // Create personel record
            Personel::create(
                [
                    'nrp' => $nrp, 
                    'satker_id' => $satkerId,
                    'user_id' => $user->id,
                    'nama' => $nama,
                    'jenis_bbm' => $jenisBbm,
                    'saldo' => $saldo,
                    'pin' => $pin,
                    'barcode' => $barcode,
                ]
            );

            $this->imported++;
        }
    }
}
