<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\ReportPhoto;
use App\Models\ReportStatusLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $masyarakat = User::where('email', 'masyarakat@laporhijau.test')->first();
        $relawan    = User::where('email', 'relawan@laporhijau.test')->first();
        $pemerintah = User::where('email', 'pemerintah@laporhijau.test')->first();

        // Ambil kategori dari database
        $categories = ReportCategory::all()->pluck('id', 'name')->toArray();

        $reportsData = [
            [
                'title'       => 'Tumpukan Sampah Plastik di Pantai Padang (Purus)',
                'description' => 'Tumpukan sampah plastik sekali pakai, sterofoam bekas makanan, dan kelapa muda sisa dagangan menumpuk di area pasir Pantai Padang kawasan Purus. Selain mengotori keindahan tempat wisata unggulan kota Padang, sampah ini berpotensi terseret ombak dan merusak ekosistem laut.',
                'address'     => 'Pantai Padang, Kel. Purus, Kec. Padang Barat, Kota Padang',
                'latitude'    => -0.9312000,
                'longitude'   => 100.3496000,
                'status'      => 'resolved',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Pencemaran Sampah dan Limbah Cair di Muaro Batang Arau',
                'description' => 'Aliran Sungai Batang Arau di kawasan dekat Jembatan Siti Nurbaya tampak sangat kotor dengan tumpukan sampah terapung dan permukaan air berminyak hitam. Limbah rumah tangga dan sisa oli kapal kayu nelayan dibuang langsung tanpa pengolahan, menimbulkan bau busuk menyengat saat air surut.',
                'address'     => 'Muara Batang Arau, Kel. Batang Arau, Kec. Padang Selatan, Kota Padang',
                'latitude'    => -0.9582000,
                'longitude'   => 100.3538000,
                'status'      => 'in_progress',
                'category'    => 'Pencemaran Air',
                'photo'       => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Sumbatan Parit Utama Jalan Khatib Sulaiman Padang',
                'description' => 'Saluran drainase utama di sepanjang Jalan Khatib Sulaiman tersumbat endapan lumpur tebal dan sampah botol plastik. Setiap kali hujan deras turun lebih dari 45 menit, air langsung meluap ke badan jalan raya setinggi 20-30 cm, mengganggu lalu lintas kendaraan.',
                'address'     => 'Jl. Khatib Sulaiman, Kel. Lolong Belanti, Kec. Padang Utara, Kota Padang',
                'latitude'    => -0.9168000,
                'longitude'   => 100.3602000,
                'status'      => 'verified',
                'category'    => 'Banjir & Drainase',
                'photo'       => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Asap Pembakaran Lahan Dekat Kampus UNAND Limau Manis',
                'description' => 'Aktivitas pembakaran semak belukar untuk pembukaan lahan perkebunan baru di sekitar lereng bukit dekat kawasan kampus Universitas Andalas (UNAND). Asap putih tebal terbawa angin hingga ke area perumahan mahasiswa dan mengganggu kualitas udara serta pernapasan warga.',
                'address'     => 'Limau Manis, Kec. Pauh, Kota Padang',
                'latitude'    => -0.9184000,
                'longitude'   => 100.4632000,
                'status'      => 'pending',
                'category'    => 'Pencemaran Udara',
                'photo'       => 'https://images.unsplash.com/photo-1532601224476-15c79f2f7a51?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Pembuangan Limbah Oli Bengkel ke Parit Kuranji',
                'description' => 'Sebuah bengkel kendaraan membuang limbah oli bekas langsung ke dalam selokan umum yang mengalir ke sungai terdekat di daerah Kuranji. Air selokan menjadi hitam mengkilap, berminyak, dan mematikan ekosistem air lokal. Warga cemas limbah meresap ke air sumur.',
                'address'     => 'Jl. Raya Kuranji, Kel. Kuranji, Kec. Kuranji, Kota Padang',
                'latitude'    => -0.9256000,
                'longitude'   => 100.4101000,
                'status'      => 'pending',
                'category'    => 'Pencemaran Tanah',
                'photo'       => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Sampah Menumpuk di Kawasan Pasar Raya Padang',
                'description' => 'Limbah sayuran busuk, kardus basah, dan plastik sisa aktivitas dagang menumpuk di salah satu sudut trotoar Pasar Raya Padang dekat Blok III. Penumpukan ini mengundang lalat, mengeluarkan bau tidak sedap bagi pejalan kaki, dan mengganggu ketertiban estetika kota.',
                'address'     => 'Pasar Raya Padang, Kec. Padang Barat, Kota Padang',
                'latitude'    => -0.9452000,
                'longitude'   => 100.3688000,
                'status'      => 'resolved',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Limbah Oli Kapal Nelayan di Pantai Pasir Jambak',
                'description' => 'Ditemukan bercak minyak hitam pekat menyerupai oli bekas yang terbawa ombak dan terdampar di pesisir Pantai Pasir Jambak. Cairan berminyak ini sangat mengganggu kenyamanan pengunjung pantai dan diduga dibuang secara sengaja oleh kapal nelayan besar di tengah laut.',
                'address'     => 'Pantai Pasir Jambak, Kec. Koto Tangah, Kota Padang',
                'latitude'    => -0.8245000,
                'longitude'   => 100.3204000,
                'status'      => 'verified',
                'category'    => 'Pencemaran Air',
                'photo'       => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Penebangan Pohon Pelindung Jalan Hamka (Air Tawar)',
                'description' => 'Aktivitas pemotongan pohon pelindung jalan berukuran besar tanpa izin resmi di kawasan Air Tawar. Penebangan ini menyisakan tunggul kayu yang merusak estetika trotoar dan mengurangi kerindangan jalur hijau utama yang sering digunakan mahasiswa pejalan kaki.',
                'address'     => 'Jl. Prof. Dr. Hamka, Air Tawar, Kec. Padang Utara, Kota Padang',
                'latitude'    => -0.8984000,
                'longitude'   => 100.3526000,
                'status'      => 'resolved',
                'category'    => 'Kerusakan Pohon & RTH',
                'photo'       => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Banjir Luapan Sungai Batang Kuranji di Siteba',
                'description' => 'Curah hujan tinggi menyebabkan debit air Sungai Batang Kuranji meluap dan menggenangi area pemukiman warga di sekitar Siteba, Nanggalo. Air luapan masuk ke garasi rumah warga setinggi 40 cm dan membawa tumpukan lumpur serta ranting kayu besar.',
                'address'     => 'Perumnas Siteba, Kec. Nanggalo, Kota Padang',
                'latitude'    => -0.9022000,
                'longitude'   => 100.3842000,
                'status'      => 'in_progress',
                'category'    => 'Banjir & Drainase',
                'photo'       => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Polusi Debu Semen Pabrik di Kawasan Indarung',
                'description' => 'Konsentrasi debu halus berwarna abu-abu pekat menyelimuti pemukiman warga terdekat di sekitar pabrik Indarung. Debu tebal menempel di atap rumah, daun tanaman, dan mengganggu jarak pandang serta memicu penyakit infeksi saluran pernapasan akut (ISPA) bagi anak-anak.',
                'address'     => 'Kec. Luki (Lubuk Kilangan), Indarung, Kota Padang',
                'latitude'    => -0.9572000,
                'longitude'   => 100.4651000,
                'status'      => 'pending',
                'category'    => 'Pencemaran Udara',
                'photo'       => 'https://images.unsplash.com/photo-1532601224476-15c79f2f7a51?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Sampah Berserakan di Objek Wisata Pantai Air Manis',
                'description' => 'Tumpukan sampah plastik botol minum kemasan dan bungkus makanan ringan berserakan di sekitar patung Batu Malin Kundang. Banyaknya sampah menurunkan nilai estetika pariwisata sejarah unggulan daerah Padang ini. Dibutuhkan tempat sampah yang memadai dan petugas reguler.',
                'address'     => 'Pantai Air Manis, Kec. Padang Selatan, Kota Padang',
                'latitude'    => -0.9856000,
                'longitude'   => 100.3512000,
                'status'      => 'in_progress',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Pencemaran Air Danau Singkarak di Sisi Solok',
                'description' => 'Adanya aktivitas pembuangan sampah rumah tangga dan sisa pakan ikan keramba jaring apung secara berlebihan di pinggiran Danau Singkarak wilayah Solok. Air danau di pinggir pantai mengalami eutrofikasi, berlumut tebal, dan menyebabkan banyak ikan kecil mati.',
                'address'     => 'Pinggiran Danau Singkarak, Kec. X Koto Singkarak, Kabupaten Solok',
                'latitude'    => -0.6124000,
                'longitude'   => 100.5212000,
                'status'      => 'resolved',
                'category'    => 'Pencemaran Air',
                'photo'       => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Tumpukan Sampah Liar di Pinggir Ngarai Sianok Bukittinggi',
                'description' => 'Terdapat tempat pembuangan sampah liar tak berizin di pinggiran tebing Ngarai Sianok. Sampah rumah tangga berceceran di lereng bukit hijau dan menimbulkan bau busuk menyengat hingga ke gardu pandang wisata terdekat. Warga meminta pemasangan papan larangan buang sampah.',
                'address'     => 'Kawasan Ngarai Sianok, Kota Bukittinggi',
                'latitude'    => -0.3068000,
                'longitude'   => 100.3601000,
                'status'      => 'pending',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Kerusakan Hutan Lindung di Lereng Gunung Talang Solok',
                'description' => 'Adanya dugaan aksi perambahan hutan lindung liar di lereng Gunung Talang oleh pihak tidak bertanggung jawab untuk alih fungsi lahan perkebunan. Beberapa hektar pohon besar tampak gundul ditebang secara sengaja, berpotensi memicu bencana tanah longsor saat curah hujan tinggi.',
                'address'     => 'Lereng Gunung Talang, Kabupaten Solok',
                'latitude'    => -0.9782000,
                'longitude'   => 100.6723000,
                'status'      => 'verified',
                'category'    => 'Kerusakan Pohon & RTH',
                'photo'       => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Pohon Tumbang Penghalang Jalan Raya Padang-Pariaman',
                'description' => 'Sebuah pohon pelindung tua berukuran besar tumbang melintang di tengah jalan lintas Sumatera arah Padang-Pariaman kawasan Lubuk Alung akibat badai kemarin malam. Kejadian ini melumpuhkan arus transportasi dua arah sebelum akhirnya dipotong bersama warga.',
                'address'     => 'Jl. Lintas Padang-Bukittinggi, Lubuk Alung, Kabupaten Padang Pariaman',
                'latitude'    => -0.7123000,
                'longitude'   => 100.2234000,
                'status'      => 'resolved',
                'category'    => 'Kerusakan Pohon & RTH',
                'photo'       => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($reportsData as $rpt) {
            $catId = $categories[$rpt['category']] ?? 1;

            $report = Report::create([
                'user_id'      => $masyarakat->id,
                'category_id'  => $catId,
                'volunteer_id' => $rpt['status'] === 'resolved' ? $relawan->id : null,
                'title'        => $rpt['title'],
                'description'  => $rpt['description'],
                'address'      => $rpt['address'],
                'latitude'     => $rpt['latitude'],
                'longitude'    => $rpt['longitude'],
                'status'       => $rpt['status'],
                'created_at'   => now()->subDays(rand(5, 30)),
            ]);

            // Seed photo
            ReportPhoto::create([
                'report_id' => $report->id,
                'photo_url' => $rpt['photo'],
            ]);

            // Seed status log
            // Log awal: pending
            ReportStatusLog::create([
                'report_id'  => $report->id,
                'old_status' => null,
                'new_status' => 'pending',
                'changed_by' => $masyarakat->id,
                'notes'      => 'Laporan pertama kali dibuat oleh masyarakat.',
                'created_at' => $report->created_at,
            ]);

            // Jika statusnya lebih tinggi dari pending
            if ($rpt['status'] !== 'pending') {
                // Log verified
                $verifiedAt = $report->created_at->addHours(rand(2, 24));
                ReportStatusLog::create([
                    'report_id'  => $report->id,
                    'old_status' => 'pending',
                    'new_status' => 'verified',
                    'changed_by' => $relawan->id,
                    'notes'      => 'Laporan telah diverifikasi oleh relawan di lapangan.',
                    'created_at' => $verifiedAt,
                ]);

                if ($rpt['status'] === 'in_progress' || $rpt['status'] === 'resolved') {
                    // Log in_progress
                    $inProgressAt = $verifiedAt->addDays(rand(1, 3));
                    ReportStatusLog::create([
                        'report_id'  => $report->id,
                        'old_status' => 'verified',
                        'new_status' => 'in_progress',
                        'changed_by' => $pemerintah->id,
                        'notes'      => 'Laporan diterima dan sedang ditangani oleh instansi terkait.',
                        'created_at' => $inProgressAt,
                    ]);

                    if ($rpt['status'] === 'resolved') {
                        // Log resolved
                        $resolvedAt = $inProgressAt->addDays(rand(2, 5));
                        ReportStatusLog::create([
                            'report_id'  => $report->id,
                            'old_status' => 'in_progress',
                            'new_status' => 'resolved',
                            'changed_by' => $pemerintah->id,
                            'notes'      => 'Masalah lingkungan telah selesai ditangani dengan sukses.',
                            'created_at' => $resolvedAt,
                        ]);
                    }
                }
            }

            $this->command->info("✅ Laporan [{$report->title}] (status: {$report->status}) seeded.");
        }
    }
}
