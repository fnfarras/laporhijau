<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Buat 4 role sesuai aturan project LaporHijau.
     */
    public function run(): void
    {
        $roles = ['masyarakat', 'relawan', 'pemerintah', 'admin'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $this->command->info('✅ 4 role berhasil dibuat: ' . implode(', ', $roles));
    }
}
