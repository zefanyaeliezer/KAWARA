<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $existingAdmin = User::where('username', 'admin')->first();
        $legacySuperAdmin = User::where('username', 'superadmin')->first();

        if (! $existingAdmin && $legacySuperAdmin) {
            $legacySuperAdmin->update([
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@kawara.local',
                'role' => 'admin',
                'password' => 'admin123',
                'email_verified_at' => $legacySuperAdmin->email_verified_at ?? now(),
            ]);

            return;
        }

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'email' => 'admin@kawara.local',
                'role' => 'admin',
                'password' => 'admin123',
                'email_verified_at' => now(),
            ]
        );
    }
}
