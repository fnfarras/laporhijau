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

        // 1. Event 1: Aksi Bersih Sungai Batang Arau (Upcoming, 2 weeks from now)
        $event1 = Event::updateOrCreate(
            ['title' => 'Aksi Bersih Sungai Batang Arau'],
            [
                'organizer_id'     => $relawan->id,
                'description'      => 'Aksi kolaboratif membersihkan aliran Sungai Batang Arau dari tumpukan sampah plastik, kayu hanyut, dan limbah rumah tangga lainnya. Kegiatan ini bertujuan mengembalikan fungsi ekologis kawasan Muaro Padang yang bersejarah dan meningkatkan kepedulian masyarakat pesisir. Seluruh perlengkapan kebersihan dan konsumsi disediakan oleh panitia.',
                'location'         => 'Kawasan Jembatan Siti Nurbaya, Muaro, Padang',
                'latitude'         => -0.9575,
                'longitude'        => 100.3541,
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

        // 2. Event 2: Tanam 1000 Pohon untuk Pantai Padang Hijau (Upcoming, 3 weeks from now)
        $event2 = Event::updateOrCreate(
            ['title' => 'Tanam 1000 Pohon untuk Pantai Padang Hijau'],
            [
                'organizer_id'     => $pemerintah->id,
                'description'      => 'Gerakan penghijauan massal dengan menanam 1.000 bibit pohon ketapang dan cemara udang di sepanjang garis Pantai Padang. Program ini diinisiasi untuk mereduksi dampak abrasi air laut, menciptakan ruang terbuka hijau (RTH) yang asri, serta menekan polusi udara perkotaan. Terbuka untuk umum, mahasiswa, dan komunitas peduli alam.',
                'location'         => 'Kawasan Pesisir Pantai Padang, Kec. Padang Barat, Padang',
                'latitude'         => -0.9392,
                'longitude'        => 100.3524,
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

        // 3. Event 3: Gotong Royong Bersih Pantai Gandoriah (Past, 2 weeks ago)
        $event3 = Event::updateOrCreate(
            ['title' => 'Gotong Royong Bersih Pantai Gandoriah'],
            [
                'organizer_id'     => $relawan->id,
                'description'      => 'Aksi gotong royong sukarela untuk membersihkan pesisir Pantai Gandoriah Pariaman dari tumpukan sampah plastik bawaan ombak pasang. Terima kasih kepada seluruh relawan, masyarakat lokal, dan dinas pariwisata yang telah hadir berkontribusi menyukseskan pembersihan area pantai.',
                'location'         => 'Pesisir Pantai Gandoriah, Kota Pariaman',
                'latitude'         => -0.6264,
                'longitude'        => 100.1172,
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

        $this->command->info('✅ EventSeeder: 3 event di wilayah Padang-Sumbar berhasil di-seed.');
    }
}
