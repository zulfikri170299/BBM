<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );
        echo "Admin seeded successfully.\n";
    }
}
