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
                'title'       => 'Tumpukan Sampah Liar di Jalan Sudirman Pekanbaru',
                'description' => 'Tumpukan sampah liar menumpuk di pinggir Jalan Sudirman Pekanbaru dan menimbulkan bau yang menyengat bagi pengendara yang melintas. Sampah ini didominasi oleh sampah plastik rumah tangga dan limbah basah sisa makanan pasar. Jika dibiarkan, sampah ini berpotensi menyumbat saluran parit di tepi jalan utama.',
                'address'     => 'Jl. Jenderal Sudirman, Pekanbaru',
                'latitude'    => 0.5103000,
                'longitude'   => 101.4477000,
                'status'      => 'resolved',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Air Sungai Siak Berbau dan Berubah Warna Kehijauan',
                'description' => 'Aliran Sungai Siak di sekitar kawasan Rumbai terindikasi tercemar limbah karena airnya mengeluarkan bau menyengat serta berubah warna menjadi kehijauan pekat. Banyak ikan-ikan kecil mati terapung di tepian sungai yang biasanya digunakan warga untuk memancing. Warga khawatir pencemaran ini mengganggu ekosistem air dan kesehatan penduduk yang tinggal di bantaran sungai.',
                'address'     => 'Tepian Sungai Siak, Rumbai, Pekanbaru',
                'latitude'    => 0.5522000,
                'longitude'   => 101.4234000,
                'status'      => 'in_progress',
                'category'    => 'Pencemaran Air',
                'photo'       => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Drainase Tersumbat Sebabkan Banjir di Perumahan Tampan',
                'description' => 'Saluran drainase utama di Perumahan Griya Permai tersumbat oleh tumpukan sampah plastik dan endapan lumpur tebal yang telah mengeras. Akibatnya, setiap kali hujan deras turun selama lebih dari 30 menit, jalan perumahan dan beberapa teras rumah warga langsung tergenang air banjir setinggi 30 cm. Diperlukan tindakan pembersihan dan pengerukan lumpur secara menyeluruh.',
                'address'     => 'Perumahan Griya Permai, Tampan, Pekanbaru',
                'latitude'    => 0.4831000,
                'longitude'   => 101.3892000,
                'status'      => 'verified',
                'category'    => 'Banjir & Drainase',
                'photo'       => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Asap Pembakaran Sampah Ganggu Warga RT 05 Panam',
                'description' => 'Pembakaran sampah liar secara terbuka dilakukan hampir setiap sore di dekat pemukiman padat penduduk RT 05 Panam. Asap tebal dan bau plastik terbakar yang dihasilkan sangat mengganggu pernapasan warga, terutama anak-anak dan lansia yang menderita asma. Warga telah berulang kali mengingatkan pelaku namun pembakaran tetap terus dilakukan.',
                'address'     => 'Jl. Garuda Sakti KM 2, Panam, Pekanbaru',
                'latitude'    => 0.4656000,
                'longitude'   => 101.3712000,
                'status'      => 'pending',
                'category'    => 'Pencemaran Udara',
                'photo'       => 'https://images.unsplash.com/photo-1532601224476-15c79f2f7a51?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Limbah Oli Bekas Dibuang ke Selokan Jalan Nangka',
                'description' => 'Sebuah bengkel motor lokal terindikasi membuang limbah oli bekas langsung ke dalam selokan umum di Jalan Nangka secara berkala. Air selokan kini tampak hitam pekat dan berminyak serta mengeluarkan aroma khas bahan kimia yang sangat kuat. Pencemaran oli ini dikhawatirkan dapat meresap ke dalam sumur-sumur air bersih milik warga sekitar.',
                'address'     => 'Jl. Nangka, Tenayan Raya, Pekanbaru',
                'latitude'    => 0.5089000,
                'longitude'   => 101.4823000,
                'status'      => 'pending',
                'category'    => 'Pencemaran Tanah',
                'photo'       => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Sampah Plastik Menumpuk di Tepi Danau Buatan Rumbai',
                'description' => 'Kawasan wisata Danau Buatan Rumbai mengalami penumpukan sampah plastik yang ditinggalkan oleh pengunjung yang kurang bertanggung jawab. Berbagai jenis botol plastik, kemasan makanan ringan, dan kantong kresek berserakan di area hijau dekat danau. Hal ini merusak keindahan tempat wisata alam dan berpotensi mencemari ekosistem air danau.',
                'address'     => 'Danau Buatan, Rumbai, Pekanbaru',
                'latitude'    => 0.5634000,
                'longitude'   => 101.4012000,
                'status'      => 'resolved',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Tumpukan Sampah Elektronik di Lahan Kosong Arengka',
                'description' => 'Sebuah lahan kosong di kawasan Arengka dijadikan tempat pembuangan sampah elektronik seperti televisi tabung rusak, komponen komputer, dan kabel-kabel bekas. Kandungan logam berat dari komponen elektronik tersebut dikhawatirkan akan merembes ke tanah saat musim hujan tiba. Warga berharap pihak berwenang segera mengangkut dan membersihkan lahan ini.',
                'address'     => 'Jl. Arengka, Marpoyan Damai, Pekanbaru',
                'latitude'    => 0.4912000,
                'longitude'   => 101.4156000,
                'status'      => 'verified',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1604187351574-c75ca79f5a07?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Genangan Air Permanen di Jalan HR Soebrantas',
                'description' => 'Terjadi genangan air hujan yang tidak kunjung surut di tepi Jalan HR Soebrantas Panam akibat tidak berfungsinya saluran pembuangan air utama. Genangan air ini telah berlangsung selama berminggu-minggu dan kini mulai ditumbuhi jentik nyamuk serta berlumut tebal. Kondisi ini membahayakan keselamatan pengendara sepeda motor dan merusak struktur aspal jalan.',
                'address'     => 'Jl. HR Soebrantas, Panam, Pekanbaru',
                'latitude'    => 0.4701000,
                'longitude'   => 101.3856000,
                'status'      => 'in_progress',
                'category'    => 'Banjir & Drainase',
                'photo'       => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Pencemaran Udara dari Cerobong Pabrik Tenayan Raya',
                'description' => 'Sebuah pabrik pengolahan di Kawasan Industri Tenayan Raya mengeluarkan asap hitam pekat dari cerobongnya secara terus-menerus tanpa penyaringan yang memadai. Asap pekat tersebut terbang terbawa angin ke arah pemukiman terdekat dan menyebabkan iritasi mata serta batuk-batuk bagi warga sekitar. Warga meminta dinas terkait memeriksa kelayakan izin lingkungan pabrik tersebut.',
                'address'     => 'Kawasan Industri Tenayan Raya, Pekanbaru',
                'latitude'    => 0.5156000,
                'longitude'   => 101.5023000,
                'status'      => 'pending',
                'category'    => 'Pencemaran Udara',
                'photo'       => 'https://images.unsplash.com/photo-1532601224476-15c79f2f7a51?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Bau Busuk TPS Sementara Dekat SDN 001 Pekanbaru',
                'description' => 'Tempat pembuangan sampah sementara yang terletak tidak jauh dari gerbang masuk SDN 001 Pekanbaru menimbulkan bau busuk menyengat dan lalat yang bertebaran. Sampah sering terlambat diangkut oleh truk kebersihan sehingga menumpuk hingga memakan bahu jalan sekolah. Hal ini sangat mengganggu kenyamanan dan konsentrasi belajar para siswa di sekolah tersebut.',
                'address'     => 'Jl. Arifin Ahmad, Marpoyan Damai, Pekanbaru',
                'latitude'    => 0.5012000,
                'longitude'   => 101.4389000,
                'status'      => 'in_progress',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Aliran Irigasi Sawah Tercemar Limbah Rumah Tangga',
                'description' => 'Saluran irigasi pertanian di wilayah Rumbai Pesisir tersumbat oleh sampah plastik dan tercemar air detergen buangan dari saluran rumah tangga di sekitarnya. Air irigasi yang biasanya jernih kini menjadi keruh dan berbusa, menyebabkan tanaman padi warga mulai layu dan terancam gagal panen. Petani berharap ada edukasi bagi warga agar tidak membuang limbah langsung ke irigasi sawah.',
                'address'     => 'Jl. Paus, Rumbai Pesisir, Pekanbaru',
                'latitude'    => 0.5789000,
                'longitude'   => 101.4567000,
                'status'      => 'resolved',
                'category'    => 'Pencemaran Air',
                'photo'       => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Kebakaran Lahan Gambut Pinggiran Kota Pekanbaru',
                'description' => 'Sebuah lahan gambut kering di wilayah pinggiran Rumbai terbakar secara perlahan dan menimbulkan asap putih tebal yang menutup jarak pandang pengendara. Kebakaran diduga dipicu oleh cuaca ekstrem dan aktivitas pembukaan lahan pertanian baru dengan cara dibakar. Tim pemadam kebakaran hutan dan lahan sudah dihubungi untuk melakukan pemadaman darurat.',
                'address'     => 'Jl. Yos Sudarso KM 15, Rumbai, Pekanbaru',
                'latitude'    => 0.5923000,
                'longitude'   => 101.3678000,
                'status'      => 'pending',
                'category'    => 'Lainnya',
                'photo'       => 'https://images.unsplash.com/photo-1532601224476-15c79f2f7a51?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Drum Limbah Kimia Tidak Berlabel di Tenayan Raya',
                'description' => 'Warga menemukan tiga buah drum besar berisi cairan kimia berbau menusuk yang diletakkan secara ilegal di lahan belukar dekat pemukiman Tenayan Raya. Drum tersebut dalam kondisi sedikit berkarat dan tanpa label pengaman sehingga warga khawatir akan bocor dan mencemari tanah pekarangan sekitarnya. Kejadian ini memerlukan penanganan segera dari tim ahli B3 Dinas Lingkungan Hidup.',
                'address'     => 'Jl. Industri, Tenayan Raya, Pekanbaru',
                'latitude'    => 0.5234000,
                'longitude'   => 101.5123000,
                'status'      => 'pending',
                'category'    => 'Pencemaran Tanah',
                'photo'       => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Hutan Kota Ditebang Ilegal di Bukit Raya',
                'description' => 'Sebagian kecil area paru-paru kota di Bukit Raya Pekanbaru terindikasi mengalami penebangan pohon liar secara berkala. Pohon-pohon pelindung berukuran besar tampak telah ditebang menggunakan gergaji mesin dan kayu gelondongannya telah diangkut oleh oknum tidak dikenal. Hal ini sangat mengkhawatirkan karena dapat mengurangi luasan zona resapan air hijau perkotaan Pekanbaru.',
                'address'     => 'Kawasan Hijau Kota, Bukit Raya, Pekanbaru',
                'latitude'    => 0.4867000,
                'longitude'   => 101.4678000,
                'status'      => 'resolved',
                'category'    => 'Kerusakan Pohon & RTH',
                'photo'       => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Pohon Tua Tumbang Tutup Akses Jalan Sukajadi',
                'description' => 'Sebuah pohon pelindung jenis angsana berukuran besar di kawasan Sukajadi Pekanbaru tumbang akibat diterjang angin kencang disertai hujan deras kemarin sore. Batang pohon menutupi seluruh badan jalan sehingga kendaraan roda empat tidak dapat melintas sama sekali dan harus memutar jauh. Diharapkan dinas terkait segera melakukan pemotongan dan evakuasi kayu tumbang tersebut.',
                'address'     => 'Jl. Melur, Sukajadi, Pekanbaru',
                'latitude'    => 0.5234000,
                'longitude'   => 101.4312000,
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
