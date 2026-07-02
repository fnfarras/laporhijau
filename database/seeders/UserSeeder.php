<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Buat 1 akun per role untuk keperluan testing & demo.
     */
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Masyarakat Demo',
                'email'    => 'masyarakat@laporhijau.test',
                'password' => Hash::make('password'),
                'points'   => 0,
                'role'     => 'masyarakat',
            ],
            [
                'name'     => 'Relawan Demo',
                'email'    => 'relawan@laporhijau.test',
                'password' => Hash::make('password'),
                'points'   => 0,
                'role'     => 'relawan',
            ],
            [
                'name'     => 'Pemerintah Demo',
                'email'    => 'pemerintah@laporhijau.test',
                'password' => Hash::make('password'),
                'points'   => 0,
                'role'     => 'pemerintah',
            ],
            [
                'name'     => 'Admin LaporHijau',
                'email'    => 'admin@laporhijau.test',
                'password' => Hash::make('password'),
                'points'   => 0,
                'role'     => 'admin',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign role via Spatie (hapus role lama dulu agar idempotent)
            $user->syncRoles([$role]);

            $this->command->info("✅ User [{$user->email}] → role: {$role}");
        }
    }
}
