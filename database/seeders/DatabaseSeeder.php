<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Urutan penting: Role → User (butuh role) → Category
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,     // 1. Buat 4 role terlebih dahulu
            UserSeeder::class,     // 2. Buat user & assign role
            CategorySeeder::class, // 3. Isi kategori laporan lingkungan
        ]);
    }
}
