<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user berdasarkan email
        $relawan    = User::where('email', 'relawan@laporhijau.test')->first();
        $pemerintah = User::where('email', 'pemerintah@laporhijau.test')->first();
        $allUsers   = User::all();

        $events = [
            [
                'organizer_id'    => $relawan->id,
                'title'           => 'Bersih-Bersih Sungai Kampar Riau',
                'description'     => 'Mari bersama-sama membersihkan Sungai Kampar dari sampah plastik dan limbah rumah tangga. Kegiatan ini merupakan bagian dari gerakan peduli lingkungan masyarakat Riau. Peserta akan mendapatkan peralatan kebersihan dan snack. Diharapkan hadir 15 menit sebelum acara dimulai.',
                'location'        => 'Tepi Sungai Kampar, Pekanbaru, Riau',
                'latitude'        => 0.5073,
                'longitude'       => 101.4478,
                'category'        => 'Bersih-bersih',
                'event_date'      => now()->addDays(10)->setTime(7, 0),
                'max_participants' => 50,
            ],
            [
                'organizer_id'    => $pemerintah->id,
                'title'           => 'Tanam 100 Pohon di Taman Kota Pekanbaru',
                'description'     => 'Program penanaman 100 pohon trembesi dan mahoni di kawasan Taman Kota Pekanbaru. Kegiatan ini bertujuan mengurangi polusi udara dan meningkatkan area hijau kota. Bibit pohon disediakan oleh Dinas Lingkungan Hidup. Semua peserta akan mendapatkan sertifikat partisipasi.',
                'location'        => 'Taman Kota Pekanbaru, Jl. Sudirman No.1',
                'latitude'        => 0.5096,
                'longitude'       => 101.4506,
                'category'        => 'Tanam Pohon',
                'event_date'      => now()->addDays(20)->setTime(6, 30),
                'max_participants' => 100,
            ],
            [
                'organizer_id'    => $relawan->id,
                'title'           => 'Gotong Royong Bersihkan Pantai Rupat',
                'description'     => 'Kegiatan gotong royong membersihkan Pantai Rupat yang terkena dampak pencemaran. Kami mengumpulkan lebih dari 200 kg sampah dalam satu hari kegiatan. Terima kasih kepada semua peserta yang telah berpartisipasi menjaga kebersihan pantai kita.',
                'location'        => 'Pantai Rupat, Bengkalis, Riau',
                'latitude'        => 1.6860,
                'longitude'       => 101.5322,
                'category'        => 'Gotong Royong',
                'event_date'      => now()->subDays(15)->setTime(8, 0),
                'max_participants' => 80,
            ],
        ];

        foreach ($events as $eventData) {
            $event = Event::updateOrCreate(
                ['title' => $eventData['title']],
                $eventData
            );

            // Tambah peserta dummy (hindari duplikat)
            $participantCount = rand(5, 8);
            $participants = $allUsers->random(min($participantCount, $allUsers->count()));

            foreach ($participants as $user) {
                EventParticipant::updateOrCreate(
                    ['event_id' => $event->id, 'user_id' => $user->id],
                    ['status' => $event->isUpcoming() ? 'registered' : 'attended']
                );
            }
        }

        $this->command->info('✅ EventSeeder: 3 event dengan peserta dummy selesai.');
    }
}
