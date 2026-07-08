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

        $eventsData = [
            [
                'title'            => 'Aksi Bersih Sungai Batang Arau',
                'description'      => 'Aksi kolaboratif membersihkan aliran Sungai Batang Arau dari tumpukan sampah plastik, kayu hanyut, dan limbah rumah tangga lainnya. Kegiatan ini bertujuan mengembalikan fungsi ekologis kawasan Muaro Padang yang bersejarah dan meningkatkan kepedulian masyarakat pesisir. Seluruh perlengkapan kebersihan dan konsumsi disediakan oleh panitia.',
                'location'         => 'Kawasan Jembatan Siti Nurbaya, Muaro, Padang',
                'latitude'         => -0.9575,
                'longitude'        => 100.3541,
                'category'         => 'Bersih-bersih',
                'event_date'       => now()->addWeeks(2)->setTime(7, 30),
                'max_participants' => 200,
                'status'           => 'registered',
                'participants'     => 47,
            ],
            [
                'title'            => 'Tanam 1000 Pohon untuk Pantai Padang Hijau',
                'description'      => 'Gerakan penghijauan massal dengan menanam 1.000 bibit pohon ketapang dan cemara udang di sepanjang garis Pantai Padang. Program ini diinisiasi untuk mereduksi dampak abrasi air laut, menciptakan ruang terbuka hijau (RTH) yang asri, serta menekan polusi udara perkotaan. Terbuka untuk umum, mahasiswa, dan komunitas peduli alam.',
                'location'         => 'Kawasan Pesisir Pantai Padang, Kec. Padang Barat, Padang',
                'latitude'         => -0.9392,
                'longitude'        => 100.3524,
                'category'         => 'Tanam Pohon',
                'event_date'       => now()->addWeeks(3)->setTime(6, 30),
                'max_participants' => 500,
                'status'           => 'registered',
                'participants'     => 123,
            ],
            [
                'title'            => 'Gotong Royong Bersih Pantai Gandoriah',
                'description'      => 'Aksi gotong royong sukarela untuk membersihkan pesisir Pantai Gandoriah Pariaman dari tumpukan sampah plastik bawaan ombak pasang. Terima kasih kepada seluruh relawan, masyarakat lokal, dan dinas pariwisata yang telah hadir berkontribusi menyukseskan pembersihan area pantai.',
                'location'         => 'Pesisir Pantai Gandoriah, Kota Pariaman',
                'latitude'         => -0.6264,
                'longitude'        => 100.1172,
                'category'         => 'Gotong Royong',
                'event_date'       => now()->subWeeks(2)->setTime(8, 0),
                'max_participants' => 100,
                'status'           => 'attended',
                'participants'     => 89,
            ],
            [
                'title'            => 'Edukasi Pengolahan Kompos Mandiri di Kuranji',
                'description'      => 'Pelatihan langsung teknik pembuatan pupuk kompos ramah lingkungan menggunakan sampah dapur sisa rumah tangga. Peserta akan diajarkan metode komposting takakura dan biopori sederhana yang cocok diaplikasikan di pekarangan rumah tangga perkotaan.',
                'location'         => 'Aula Kantor Camat Kuranji, Kota Padang',
                'latitude'         => -0.9287,
                'longitude'        => 100.3923,
                'category'         => 'Edukasi',
                'event_date'       => now()->addDays(5)->setTime(9, 0),
                'max_participants' => 50,
                'status'           => 'registered',
                'participants'     => 34,
            ],
            [
                'title'            => 'Aksi Cabut Paku di Pohon Pelindung Jalan Khatib Sulaiman',
                'description'      => 'Gerakan bersama penyelamatan pohon peneduh kota dari aksi paku liar papan iklan dan baliho di sepanjang Jalan Khatib Sulaiman. Kami mengajak mahasiswa, komunitas, dan warga Padang membawa linggis kecil atau tang untuk membersihkan pohon pelindung agar kembali sehat.',
                'location'         => 'Pedestrian sepanjang Jalan Khatib Sulaiman, Padang',
                'latitude'         => -0.9168,
                'longitude'        => 100.3602,
                'category'         => 'Gotong Royong',
                'event_date'       => now()->addDays(12)->setTime(8, 0),
                'max_participants' => 100,
                'status'           => 'registered',
                'participants'     => 56,
            ],
            [
                'title'            => 'Sosialisasi Zero Waste di Pasar Raya Padang',
                'description'      => 'Sosialisasi tatap muka kepada para pedagang dan pengunjung Pasar Raya mengenai pentingnya meminimalkan penggunaan kantong plastik sekali pakai dan beralih ke kantong ramah lingkungan. Dilakukan pembagian 200 tas kain gratis hasil donasi CSR.',
                'location'         => 'Kawasan Blok III Pasar Raya Padang, Kota Padang',
                'latitude'         => -0.9452,
                'longitude'        => 100.3688,
                'category'         => 'Edukasi',
                'event_date'       => now()->subDays(10)->setTime(9, 30),
                'max_participants' => 75,
                'status'           => 'attended',
                'participants'     => 62,
            ],
            [
                'title'            => 'Gerakan Pungut Sampah di Car Free Day Padang',
                'description'      => 'Aksi jalan sehat sambil memungut sampah plastik di sepanjang koridor Car Free Day (CFD) kota Padang. Sambil berolahraga pagi, mari kita bersihkan botol dan sedotan plastik sisa jajanan pagi hari demi menjaga keasrian jalan protokol kota.',
                'location'         => 'Jl. Jenderal Sudirman (Kawasan CFD), Padang Barat, Padang',
                'latitude'         => -0.9419,
                'longitude'        => 100.3692,
                'category'         => 'Bersih-bersih',
                'event_date'       => now()->addDays(4)->setTime(6, 0),
                'max_participants' => 300,
                'status'           => 'registered',
                'participants'     => 142,
            ],
            [
                'title'            => 'Aksi Bersih Sampah Ngarai Sianok Bukittinggi',
                'description'      => 'Kegiatan gotong royong pembersihan sampah plastik liar yang dibuang oknum tidak bertanggung jawab di lereng tebing hijau Ngarai Sianok. Aksi ini didukung oleh komunitas pencinta alam Sumatera Barat untuk melestarikan keindahan geopark nasional.',
                'location'         => 'Lembah Ngarai Sianok, Kota Bukittinggi',
                'latitude'         => -0.3068,
                'longitude'        => 100.3601,
                'category'         => 'Gotong Royong',
                'event_date'       => now()->subWeeks(3)->setTime(7, 30),
                'max_participants' => 150,
                'status'           => 'attended',
                'participants'     => 115,
            ],
        ];

        foreach ($eventsData as $data) {
            $event = Event::updateOrCreate(
                ['title' => $data['title']],
                [
                    'organizer_id'     => rand(0, 1) === 0 ? $relawan->id : $pemerintah->id,
                    'description'      => $data['description'],
                    'location'         => $data['location'],
                    'latitude'         => $data['latitude'],
                    'longitude'        => $data['longitude'],
                    'category'         => $data['category'],
                    'event_date'       => $data['event_date'],
                    'max_participants' => $data['max_participants'],
                ]
            );

            // Seed participants
            $users = User::factory()->count($data['participants'])->create();
            foreach ($users as $u) {
                $u->assignRole('masyarakat');
                EventParticipant::create([
                    'event_id' => $event->id,
                    'user_id'  => $u->id,
                    'status'   => $data['status'],
                ]);
            }
        }

        $this->command->info('✅ EventSeeder: 8 event di Sumatera Barat dengan peserta realistis selesai.');
    }
}
