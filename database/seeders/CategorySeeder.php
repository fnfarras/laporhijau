<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Seed 7 kategori laporan lingkungan LaporHijau.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Sampah & Kebersihan',   'icon' => '🗑️'],
            ['name' => 'Pencemaran Air',          'icon' => '💧'],
            ['name' => 'Pencemaran Udara',        'icon' => '🌫️'],
            ['name' => 'Kerusakan Pohon & RTH',  'icon' => '🌳'],
            ['name' => 'Banjir & Drainase',       'icon' => '🌊'],
            ['name' => 'Pencemaran Tanah',        'icon' => '🏭'],
            ['name' => 'Lainnya',                 'icon' => '📋'],
        ];

        foreach ($categories as $category) {
            DB::table('report_categories')->insertOrIgnore([
                'name'       => $category['name'],
                'icon'       => $category['icon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("✅ Kategori [{$category['name']}] ditambahkan.");
        }
    }
}
