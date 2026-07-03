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
        $relawan    = User::where('email', 'relawan@laporhijau.test')->first();
        $pemerintah = User::where('email', 'pemerintah@laporhijau.test')->first();

        // 1. Event 1: Aksi Bersih Sungai Siak Bersama (Upcoming, 2 weeks from now)
        $event1 = Event::updateOrCreate(
            ['title' => 'Aksi Bersih Sungai Siak Bersama'],
            [
                'organizer_id'     => $relawan->id,
                'description'      => 'Aksi kolaboratif membersihkan aliran Sungai Siak dari tumpukan sampah plastik, eceng gondok, dan limbah domestik lainnya. Kegiatan ini bertujuan mengembalikan fungsi ekologis sungai sejarah Riau dan meningkatkan kepedulian masyarakat sekitar bantaran sungai. Seluruh perlengkapan kebersihan dan konsumsi disediakan oleh panitia.',
                'location'         => 'Taman Tepi Sungai Siak, Pekanbaru',
                'latitude'         => 0.5378,
                'longitude'        => 101.4443,
                'category'         => 'Bersih-bersih',
                'event_date'       => now()->addWeeks(2)->setTime(7, 30),
                'max_participants' => 200,
            ]
        );

        // Seed 47 participants
        $usersEvent1 = User::factory()->count(47)->create();
        foreach ($usersEvent1 as $u) {
            $u->assignRole('masyarakat');
            EventParticipant::create([
                'event_id' => $event1->id,
                'user_id'  => $u->id,
                'status'   => 'registered',
            ]);
        }

        // 2. Event 2: Tanam 1000 Pohon untuk Pekanbaru Hijau (Upcoming, 3 weeks from now)
        $event2 = Event::updateOrCreate(
            ['title' => 'Tanam 1000 Pohon untuk Pekanbaru Hijau'],
            [
                'organizer_id'     => $pemerintah->id,
                'description'      => 'Gerakan penghijauan massal dengan menanam 1.000 bibit pohon peneduh di kawasan sekitar Hutan Kota Pekanbaru. Program ini diinisiasi untuk mengatasi polusi udara perkotaan, menjaga daerah resapan air, dan memperbanyak ruang terbuka hijau. Terbuka untuk umum, komunitas, dan mahasiswa.',
                'location'         => 'Hutan Kota, Jl. Kaharuddin Nasution, Pekanbaru',
                'latitude'         => 0.4612,
                'longitude'        => 101.4503,
                'category'         => 'Tanam Pohon',
                'event_date'       => now()->addWeeks(3)->setTime(6, 30),
                'max_participants' => 500,
            ]
        );

        // Seed 123 participants
        $usersEvent2 = User::factory()->count(123)->create();
        foreach ($usersEvent2 as $u) {
            $u->assignRole('masyarakat');
            EventParticipant::create([
                'event_id' => $event2->id,
                'user_id'  => $u->id,
                'status'   => 'registered',
            ]);
        }

        // 3. Event 3: Gotong Royong Bersih Pantai Selatpanjang (Past, 2 weeks ago)
        $event3 = Event::updateOrCreate(
            ['title' => 'Gotong Royong Bersih Pantai Selatpanjang'],
            [
                'organizer_id'     => $relawan->id,
                'description'      => 'Aksi gotong royong pembersihan pesisir Pantai Selatpanjang dari tumpukan sampah hanyut dan limbah laut. Terima kasih kepada seluruh relawan, masyarakat lokal, dan komunitas yang telah hadir berkontribusi mengurangi lebih dari 500 kg sampah laut.',
                'location'         => 'Tepi Pantai Selatpanjang, Kepulauan Meranti',
                'latitude'         => 1.0123,
                'longitude'        => 102.7123,
                'category'         => 'Gotong Royong',
                'event_date'       => now()->subWeeks(2)->setTime(8, 0),
                'max_participants' => 100,
            ]
        );

        // Seed 89 participants
        $usersEvent3 = User::factory()->count(89)->create();
        foreach ($usersEvent3 as $u) {
            $u->assignRole('masyarakat');
            EventParticipant::create([
                'event_id' => $event3->id,
                'user_id'  => $u->id,
                'status'   => 'attended',
            ]);
        }

        $this->command->info('✅ EventSeeder: 3 event dengan peserta realistis selesai.');
    }
}
