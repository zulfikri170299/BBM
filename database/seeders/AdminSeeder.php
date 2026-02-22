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
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );
        echo "Admin seeded successfully.\n";
    }
}
