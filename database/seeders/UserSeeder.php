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
                'name'              => 'Ahmad Fauzi',
                'email'             => 'masyarakat@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 170,
                'email_verified_at' => now(),
                'role'              => 'masyarakat',
            ],
            [
                'name'              => 'Siti Rahayu',
                'email'             => 'relawan@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 420,
                'email_verified_at' => now(),
                'role'              => 'relawan',
            ],
            [
                'name'              => 'Dinas Lingkungan Hidup Pekanbaru',
                'email'             => 'pemerintah@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 0,
                'email_verified_at' => now(),
                'role'              => 'pemerintah',
            ],
            [
                'name'              => 'Admin LaporHijau',
                'email'             => 'admin@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 0,
                'email_verified_at' => now(),
                'role'              => 'admin',
            ],
            [
                'name'              => 'Rizky Pratama',
                'email'             => 'rizky@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 380,
                'email_verified_at' => now(),
                'role'              => 'masyarakat',
            ],
            [
                'name'              => 'Dewi Anggraini',
                'email'             => 'dewi@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 290,
                'email_verified_at' => now(),
                'role'              => 'masyarakat',
            ],
            [
                'name'              => 'Budi Raharjo',
                'email'             => 'budi@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 245,
                'email_verified_at' => now(),
                'role'              => 'masyarakat',
            ],
            [
                'name'              => 'Nur Hidayah',
                'email'             => 'nur@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 210,
                'email_verified_at' => now(),
                'role'              => 'masyarakat',
            ],
            [
                'name'              => 'Fajar Setiawan',
                'email'             => 'fajar@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 185,
                'email_verified_at' => now(),
                'role'              => 'masyarakat',
            ],
            [
                'name'              => 'Rani Kusuma',
                'email'             => 'rani@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 155,
                'email_verified_at' => now(),
                'role'              => 'masyarakat',
            ],
            [
                'name'              => 'Doni Saputra',
                'email'             => 'doni@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 120,
                'email_verified_at' => now(),
                'role'              => 'masyarakat',
            ],
            [
                'name'              => 'Mega Putri',
                'email'             => 'mega@laporhijau.test',
                'password'          => Hash::make('password'),
                'points'            => 95,
                'email_verified_at' => now(),
                'role'              => 'masyarakat',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign role via Spatie (hapus role lama dulu agar idempotent)
            $user->syncRoles([$role]);

            $this->command->info("✅ User [{$user->email}] → role: {$role}");
        }
    }
}
