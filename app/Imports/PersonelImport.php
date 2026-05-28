<?php

namespace App\Imports;

use App\Models\Personel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Illuminate\Support\Facades\Log;

class PersonelImport implements ToCollection, WithHeadingRow, WithCustomCsvSettings
{
    public $imported = 0;
    public $skipped = []; // Store details of skipped rows

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        $currentUser = auth()->user();
        $defaultSatkerId = $currentUser->satker_id;

        foreach ($rows as $row) {
            $nama = $row->get('nama');
            $nrp = $row->get('nrp_nip') ?? $row->get('nrpnip') ?? $row->get('nrp');
            $namaSatker = $row->get('satker');
            $jenisBbm = 'Pertamax'; // Default value as it's removed from import
            
            if (!$nama || !$nrp) {
                continue;
            }

            $satkerId = $defaultSatkerId;

            // If super admin or satker column is provided, try to find satker by name
            if ($namaSatker) {
                $satker = \App\Models\Satker::where('nama_satker', 'like', "%{$namaSatker}%")->first();
                if ($satker) {
                    $satkerId = $satker->id;
                }
            }

            if (!$satkerId) {
                $this->skipped[] = [
                    'nama' => $nama, 
                    'nrp' => $nrp,
                    'reason' => 'Satker tidak ditemukan'
                ];
                continue;
            }

            // Check if Personel already exists for this Satker
            $existingPersonel = Personel::where('nrp', $nrp)
                                      ->where('satker_id', $satkerId)
                                      ->first();

            if ($existingPersonel) {
                // Update existing data instead of skipping? No, prompt says "fix format", 
                // but usually users want updates. However, for now let's keep it as skip or update.
                // Re-reading code: it creates User updateOrCreate based on NRP (global).
                // Let's allow updating the Personel record if it exists?
                // The prompt just said "fix format". I'll keep the current logic but ensure all fields are captured.
                $existingPersonel->update([
                    'nama' => $nama,
                    'jenis_bbm' => $jenisBbm,
                ]);
                $this->imported++;
                continue;
            }

            $barcode = $row->get('barcode') ?? $nrp;

            $pin = $row->get('pin');
            if (!$pin || $pin == '123456') {
                $pin = Personel::generateUniquePin();
            }

            // Create user account if not exists
            $user = \App\Models\User::updateOrCreate(
                ['username' => $nrp],
                [
                    'name' => $nama,
                    'email' => $nrp,
                    'password' => \Illuminate\Support\Facades\Hash::make($nrp),
                    'role' => 'personel',
                    'satker_id' => $satkerId,
                ]
            );

            // Create personel record
            Personel::create([
                'nrp' => $nrp, 
                'satker_id' => $satkerId,
                'user_id' => $user->id,
                'nama' => $nama,
                'jenis_bbm' => $jenisBbm,
                'pin' => $pin,
                'barcode' => $barcode,
            ]);

            $this->imported++;
        }
    }
}
