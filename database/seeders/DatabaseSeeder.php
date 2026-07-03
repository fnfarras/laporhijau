<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Urutan penting:
     * 1. RoleSeeder & UserSeeder
     * 2. CategorySeeder & BadgeSeeder
     * 3. ReportSeeder (butuh user & category)
     * 4. EventSeeder & ArticleSeeder
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            BadgeSeeder::class,
            ReportSeeder::class,
            EventSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
