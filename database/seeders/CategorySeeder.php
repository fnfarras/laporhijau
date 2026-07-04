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

        // 1. Seed or update the categories uniquely
        $seededIds = [];
        foreach ($categories as $cat) {
            $record = \App\Models\ReportCategory::updateOrCreate(
                ['name' => $cat['name']],
                ['icon' => $cat['icon']]
            );
            $seededIds[$cat['name']] = $record->id;
            $this->command->info("✅ Kategori [{$cat['name']}] ditambahkan/diperbarui.");
        }

        // 2. Clean up any other duplicates in the database
        $allDbCategories = \App\Models\ReportCategory::all();
        foreach ($allDbCategories as $dbCat) {
            if (isset($seededIds[$dbCat->name]) && $dbCat->id != $seededIds[$dbCat->name]) {
                // Re-associate reports to the correct seeded ID before deleting to prevent cascading deletion
                \App\Models\Report::where('category_id', $dbCat->id)
                    ->update(['category_id' => $seededIds[$dbCat->name]]);
                
                // Safe to delete duplicate now
                $dbCat->delete();
                $this->command->warn("⚠️ Menghapus duplikat kategori [{$dbCat->name}] dengan ID {$dbCat->id}");
            }
        }
    }
}
