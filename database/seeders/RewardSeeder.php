<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'name' => 'Sertifikat Pelopor Hijau',
                'description' => 'Sertifikat digital resmi LaporHijau sebagai pengakuan kontribusi awal kamu menjaga lingkungan',
                'points_required' => 100,
                'type' => 'sertifikat',
                'icon' => '🏅',
            ],
            [
                'name' => 'Sertifikat Relawan Lingkungan',
                'description' => 'Sertifikat digital untuk kontributor aktif yang telah membantu verifikasi laporan lingkungan',
                'points_required' => 300,
                'type' => 'sertifikat',
                'icon' => '🌿',
            ],
            [
                'name' => 'Gelar Pahlawan Hijau',
                'description' => 'Gelar kehormatan yang ditampilkan di profil publik kamu sebagai Pahlawan Hijau LaporHijau',
                'points_required' => 500,
                'type' => 'title',
                'icon' => '👑',
            ],
            [
                'name' => 'Badge Spesial Penjaga Sungai',
                'description' => 'Badge eksklusif untuk kontributor yang aktif melaporkan masalah pencemaran air',
                'points_required' => 200,
                'type' => 'badge_spesial',
                'icon' => '💧',
            ],
            [
                'name' => 'Sertifikat Kontributor Terbaik',
                'description' => 'Sertifikat digital bergengsi untuk kontributor dengan poin tertinggi di komunitasnya',
                'points_required' => 750,
                'type' => 'sertifikat',
                'icon' => '🏆',
            ],
        ];

        foreach ($rewards as $r) {
            Reward::updateOrCreate(
                ['name' => $r['name']],
                $r
            );
        }
    }
}
