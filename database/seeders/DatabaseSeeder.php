<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate tables to ensure a clean slate
        Schema::disableForeignKeyConstraints();
        \App\Models\TransaksiBbm::truncate();
        \App\Models\LogAktivitas::truncate();
        \App\Models\Kendaraan::truncate();
        \App\Models\Personel::truncate();
        \App\Models\User::truncate();
        \App\Models\Satker::truncate();
        Schema::enableForeignKeyConstraints();

        $faker = Faker::create('id_ID');

        // 1. Create Main Satker (Headquarters)
        $satkerPusat = \App\Models\Satker::create([
            'nama_satker' => 'Satker Mabes',
            'alamat' => 'Jl. Trunojoyo No. 3, Jakarta Selatan',
        ]);

        // 2. Create Super Admin
        \App\Models\User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@example.com',
            'username' => 'superadmin',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'satker_id' => null, // Super admin can be global or attached to pusat
        ]);

        // 3. Create Additional Satkers
        $sakterNames = ['Satker Polda Metro Jaya', 'Satker Polda Jabar', 'Satker Polda Jatim', 'Satker Polda Bali'];

        foreach ($sakterNames as $index => $satkerName) {
            $satker = \App\Models\Satker::create([
                'nama_satker' => $satkerName,
                'alamat' => $faker->address,
            ]);

            // Create Admin Satker
            \App\Models\User::create([
                'name' => 'Admin ' . $satker->nama_satker,
                'email' => 'admin.' . Str::slug($satker->nama_satker) . '@example.com',
                'username' => 'admin' . ($index + 1),
                'password' => Hash::make('password'),
                'role' => 'admin_satker',
                'satker_id' => $satker->id,
            ]);

            // Create Petugas BBM (2 per Satker)
            $petugasList = [];
            for ($i = 0; $i < 2; $i++) {
                $petugas = \App\Models\User::create([
                    'name' => 'Petugas ' . $faker->firstName,
                    'email' => 'petugas.' . Str::slug($satker->nama_satker) . '.' . $i . '@example.com',
                    'username' => 'petugas' . ($index + 1) . $i,
                    'password' => Hash::make('password'),
                    'role' => 'petugas_bbm',
                    'satker_id' => $satker->id,
                ]);
                $petugasList[] = $petugas;
            }

            // Create Vehicles (10 per Satker)
            $kendaraanList = [];
            $jenisKendaraan = ['Toyota Fortuner', 'Mitsubishi Pajero', 'Isuzu D-Max', 'Toyota Hilux', 'Yamaha NMAX', 'Honda CRF'];
            $jenisBbm = ['Pertamax', 'Solar', 'Pertalite', 'Dexlite'];

            for ($j = 0; $j < 10; $j++) {
                $kendaraan = \App\Models\Kendaraan::create([
                    'satker_id' => $satker->id,
                    'no_polisi' => $faker->bothify('B #### ??'),
                    'jenis_kendaraan' => $faker->randomElement($jenisKendaraan),
                    'jenis_bbm' => $faker->randomElement($jenisBbm),
                    'barcode' => strtoupper(Str::random(10)),
                    'pin' => str_pad($faker->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT),
                    'saldo' => $faker->randomFloat(1, 5, 200),
                ]);
                $kendaraanList[] = $kendaraan;
            }

            // Create Personnel (10 per Satker)
            $personelList = [];
            for ($k = 0; $k < 10; $k++) {
                $personelUser = \App\Models\User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'username' => $faker->unique()->userName,
                    'password' => Hash::make('password'),
                    'role' => 'personel',
                    'satker_id' => $satker->id,
                ]);

                $personel = \App\Models\Personel::create([
                    'satker_id' => $satker->id,
                    'user_id' => $personelUser->id,
                    'nama' => $personelUser->name,
                    'nrp' => $faker->unique()->numerify('########'),
                    'saldo' => $faker->numberBetween(100000, 1000000),
                ]);
                $personelList[] = $personel;
                
                // Also assign a driver to dashboard login example (Personel 1 of Satker 1)
                if ($index === 0 && $k === 0) {
                     // Keep track of this specific user for login info if needed
                }
            }

            // GENERATE TRANSACTIONS (50 per satker, distributed over last 30 days)
            for ($t = 0; $t < 50; $t++) {
                $kendaraan = $faker->randomElement($kendaraanList);
                $petugas = $faker->randomElement($petugasList);
                $liter = $faker->randomFloat(2, 5, 40);
                $hargaPerLiter = ($kendaraan->jenis_bbm == 'Solar' || $kendaraan->jenis_bbm == 'Dexlite') ? 6800 : 10000;
                $total = $liter * $hargaPerLiter;
                $date = Carbon::now()->subDays(rand(0, 30))->setTime(rand(6, 18), rand(0, 59));

                \App\Models\TransaksiBbm::create([
                    'kendaraan_id' => $kendaraan->id,
                    'petugas_id' => $petugas->id,
                    'personel_id' => null, // Optional, can be null or link to a personal account
                    'liter' => $liter,
                    'harga_per_liter' => $hargaPerLiter,
                    'total' => $total,
                    'tanggal' => $date, // Added missing field
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
        
        // Also ensure the specific demo accounts exist for ease of use
        
        // 1. Demo Admin Satker (First one created above in loop is dynamic, let's make a specific one or just inform user)
        // I'll create the standard demo accounts requested in previous turns if they don't exist, specifically attached to 'Satker Mabes'
        
        // Admin Satker Pusat
        \App\Models\User::create([
            'name' => 'Admin Satker Pusat',
            'email' => 'adminsatker@example.com', // Override duplicate email check or just use this one
            'username' => 'adminsatker',
            'password' => Hash::make('password'),
            'role' => 'admin_satker',
            'satker_id' => $satkerPusat->id,
        ]);
        
        // Petugas Pusat
        \App\Models\User::create([
            'name' => 'Petugas Pusat',
            'email' => 'petugas@example.com',
            'username' => 'petugasbbm',
            'password' => Hash::make('password'),
            'role' => 'petugas_bbm',
            'satker_id' => $satkerPusat->id,
        ]);
        
        // Personel Pusat
        $demoPersonelUser = \App\Models\User::create([
            'name' => 'Personel Demo',
            'email' => 'personel@example.com',
            'username' => 'personel',
            'password' => Hash::make('password'),
            'role' => 'personel',
            'satker_id' => $satkerPusat->id,
        ]);
        
        \App\Models\Personel::create([
            'satker_id' => $satkerPusat->id,
            'user_id' => $demoPersonelUser->id,
            'nama' => 'Personel Demo',
            'nrp' => '88888888',
            'saldo' => 750000,
        ]);
    }
}
