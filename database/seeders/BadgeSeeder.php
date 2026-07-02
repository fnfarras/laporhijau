<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name'           => 'Pelopor Hijau',
                'icon'           => '🌱',
                'description'    => 'Berhasil membuat laporan pertamamu! Terima kasih telah peduli lingkungan.',
                'criteria_type'  => 'report_count',
                'criteria_value' => 1,
            ],
            [
                'name'           => 'Penjaga Lingkungan',
                'icon'           => '🛡️',
                'description'    => 'Memiliki 10 laporan yang berhasil diverifikasi. Konsistensi yang luar biasa!',
                'criteria_type'  => 'verified_report_count',
                'criteria_value' => 10,
            ],
            [
                'name'           => 'Relawan Handal',
                'icon'           => '⭐',
                'description'    => 'Telah memverifikasi 20 laporan sebagai relawan. Dedikasi tanpa henti!',
                'criteria_type'  => 'verification_count',
                'criteria_value' => 20,
            ],
            [
                'name'           => 'Penggiat Komunitas',
                'icon'           => '🤝',
                'description'    => 'Berpartisipasi dalam 5 event lingkungan. Jiwa komunitas sejati!',
                'criteria_type'  => 'event_participation',
                'criteria_value' => 5,
            ],
            [
                'name'           => 'Pahlawan Hijau',
                'icon'           => '🏆',
                'description'    => 'Mengumpulkan 500 poin! Kamu adalah pahlawan sejati lingkungan hidup.',
                'criteria_type'  => 'total_points',
                'criteria_value' => 500,
            ],
        ];

        foreach ($badges as $badge) {
            // Upsert berdasarkan name agar seeder bisa dijalankan ulang
            Badge::updateOrCreate(
                ['name' => $badge['name']],
                $badge
            );
        }

        $this->command->info('✅ BadgeSeeder: 5 badge seeded successfully.');
    }
}
