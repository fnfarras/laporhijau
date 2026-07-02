<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin     = User::where('email', 'admin@laporhijau.test')->first();
        $pemerintah = User::where('email', 'pemerintah@laporhijau.test')->first();

        $articles = [
            [
                'author' => $admin,
                'title'    => '7 Cara Mudah Mengurangi Sampah Plastik di Rumah',
                'category' => 'Tips Lingkungan',
                'content'  => '<p>Sampah plastik menjadi salah satu masalah lingkungan terbesar yang dihadapi Indonesia saat ini. Setiap tahun, jutaan ton sampah plastik berakhir di lautan dan mengganggu ekosistem laut. Namun, kita bisa mulai dari hal-hal kecil di rumah untuk menguranginya.</p>

<h2>1. Bawa Tas Belanja Sendiri</h2>
<p>Kebiasaan membawa tas belanja reusable adalah langkah pertama yang paling mudah. Simpan tas kain di dalam tas atau mobil agar selalu siap digunakan saat berbelanja.</p>

<h2>2. Gunakan Botol Minum Isi Ulang</h2>
<p>Mengganti botol plastik sekali pakai dengan botol stainless steel atau kaca dapat menghemat ratusan botol plastik per tahun. Investasi kecil dengan dampak besar bagi lingkungan.</p>

<h2>3. Pilih Produk dengan Kemasan Minimal</h2>
<p>Saat berbelanja, pilih produk yang menggunakan kemasan lebih sedikit atau kemasan yang dapat didaur ulang. Beberapa produk sabun dan shampo kini tersedia dalam bentuk batang yang tidak memerlukan kemasan plastik.</p>

<h2>4. Kompos dari Sampah Organik</h2>
<p>Pisahkan sampah organik dari sampah anorganik. Sampah dapur seperti kulit buah dan sayuran dapat diolah menjadi kompos yang bermanfaat untuk tanaman.</p>

<h2>5. Tolak Sedotan Plastik</h2>
<p>Saat memesan minuman di kafe atau restoran, biasakan untuk menolak sedotan plastik. Tersedia alternatif ramah lingkungan seperti sedotan bambu, stainless steel, atau kertas.</p>

<h2>6. Daur Ulang dengan Benar</h2>
<p>Pelajari cara memilah sampah yang benar di daerahmu. Plastik jenis PET (biasanya untuk botol air minum) dan HDPE (galon, botol sabun) adalah yang paling mudah didaur ulang.</p>

<h2>7. Edukasi Keluarga dan Tetangga</h2>
<p>Perubahan dimulai dari diri sendiri dan keluarga. Ajak anggota keluarga dan tetangga untuk sadar akan pentingnya mengurangi sampah plastik. Bersama-sama, dampaknya akan jauh lebih besar.</p>

<p><strong>Kesimpulan:</strong> Mengurangi sampah plastik bukan berarti harus zero waste dalam semalam. Mulailah dengan satu atau dua perubahan kecil, lalu tingkatkan secara bertahap. Setiap tindakan kecil kita berkontribusi pada lingkungan yang lebih sehat untuk generasi mendatang.</p>',
            ],
            [
                'author' => $pemerintah,
                'title'    => 'Mengenal UU No. 32 Tahun 2009 tentang Perlindungan dan Pengelolaan Lingkungan Hidup',
                'category' => 'Regulasi',
                'content'  => '<p>Undang-Undang Nomor 32 Tahun 2009 tentang Perlindungan dan Pengelolaan Lingkungan Hidup (UUPPLH) merupakan payung hukum utama perlindungan lingkungan di Indonesia. Undang-undang ini menggantikan UU No. 23 Tahun 1997 dan memberikan kerangka hukum yang lebih komprehensif.</p>

<h2>Pokok-Pokok Pengaturan</h2>
<p>UUPPLH mengatur berbagai aspek penting dalam pengelolaan lingkungan hidup, antara lain:</p>
<ul>
<li><strong>Perencanaan</strong>: Inventarisasi lingkungan hidup, RPPLH, dan KLHS</li>
<li><strong>Pemanfaatan</strong>: Eksploitasi sumber daya alam yang berkelanjutan</li>
<li><strong>Pengendalian</strong>: AMDAL, UKL-UPL, dan izin lingkungan</li>
<li><strong>Pemeliharaan</strong>: Konservasi dan pencegahan kerusakan</li>
<li><strong>Pengawasan</strong>: Pejabat pengawas lingkungan hidup</li>
<li><strong>Penegakan hukum</strong>: Sanksi administratif, perdata, dan pidana</li>
</ul>

<h2>Hak dan Kewajiban Warga</h2>
<p>Setiap orang berhak atas lingkungan hidup yang baik dan sehat. Sebaliknya, setiap orang juga berkewajiban memelihara kelestarian lingkungan dan mencegah kerusakan.</p>

<h2>Sanksi Pidana</h2>
<p>UUPPLH memberikan sanksi pidana yang cukup berat bagi pelaku pencemaran dan perusakan lingkungan, termasuk penjara hingga 15 tahun dan denda hingga Rp 15 miliar.</p>

<p>Pemahaman terhadap regulasi ini penting bagi masyarakat agar dapat berpartisipasi aktif dalam pengawasan lingkungan dan melaporkan pelanggaran yang terjadi di sekitar mereka.</p>',
            ],
            [
                'author' => $admin,
                'title'    => 'Kompos dari Sampah Dapur: Panduan Lengkap untuk Pemula',
                'category' => 'Daur Ulang',
                'content'  => '<p>Membuat kompos dari sampah dapur adalah salah satu cara paling efektif untuk mengurangi sampah rumah tangga sekaligus menghasilkan pupuk organik berkualitas tinggi. Proses ini mudah dilakukan bahkan di rumah dengan lahan terbatas.</p>

<h2>Mengapa Membuat Kompos?</h2>
<p>Sekitar 60% sampah rumah tangga di Indonesia adalah sampah organik yang sebenarnya bisa diolah menjadi kompos. Dengan membuat kompos, kita mengurangi volume sampah ke TPA, menghemat biaya pembelian pupuk, dan membantu tanaman tumbuh lebih sehat.</p>

<h2>Bahan yang Bisa Dikompos</h2>
<p><strong>Bahan hijau (nitrogen tinggi):</strong></p>
<ul>
<li>Sisa sayuran dan buah</li>
<li>Kulit telur</li>
<li>Ampas kopi dan teh</li>
<li>Potongan rumput segar</li>
</ul>

<p><strong>Bahan coklat (karbon tinggi):</strong></p>
<ul>
<li>Dedaunan kering</li>
<li>Kardus dan kertas bekas</li>
<li>Ranting kecil</li>
<li>Serbuk gergaji</li>
</ul>

<h2>Cara Membuat Kompos Sederhana</h2>
<ol>
<li>Siapkan wadah kompos (bisa ember bekas atau buat dari kayu)</li>
<li>Campur bahan hijau dan coklat dengan rasio 1:3</li>
<li>Tambahkan sedikit air agar lembab (tidak becek)</li>
<li>Aduk setiap 1-2 minggu untuk sirkulasi udara</li>
<li>Setelah 6-8 minggu, kompos siap digunakan</li>
</ol>

<h2>Tips Sukses Kompos</h2>
<p>Pastikan tumpukan kompos selalu lembab seperti spons yang diperas. Hindari menambahkan daging, produk susu, atau minyak karena bisa menarik hama dan menimbulkan bau tidak sedap.</p>

<p>Dengan konsistensi dan sedikit kesabaran, kamu akan memiliki "emas hijau" untuk tanamanmu dalam waktu singkat!</p>',
            ],
            [
                'author' => $pemerintah,
                'title'    => 'Bahaya Limbah B3 dan Cara Melaporkannya ke Pihak Berwenang',
                'category' => 'Edukasi',
                'content'  => '<p>Limbah Bahan Berbahaya dan Beracun (B3) merupakan salah satu ancaman serius bagi kesehatan manusia dan lingkungan hidup. Mengenal jenis, bahaya, dan cara melaporkannya adalah langkah penting dalam menjaga lingkungan kita.</p>

<h2>Apa itu Limbah B3?</h2>
<p>Limbah B3 adalah sisa suatu usaha yang mengandung zat berbahaya atau beracun yang karena sifat, konsentrasi, dan jumlahnya dapat mencemari dan membahayakan lingkungan hidup serta kesehatan manusia.</p>

<h2>Contoh Limbah B3 Sehari-hari</h2>
<ul>
<li>Baterai bekas (mengandung merkuri dan kadmium)</li>
<li>Cat dan pelarut bekas</li>
<li>Oli motor bekas</li>
<li>Lampu neon bekas (mengandung merkuri)</li>
<li>Obat-obatan kedaluwarsa</li>
<li>Produk elektronik rusak (e-waste)</li>
</ul>

<h2>Dampak Limbah B3 terhadap Kesehatan</h2>
<p>Paparan limbah B3 dapat menyebabkan berbagai penyakit serius, mulai dari gangguan sistem saraf, kerusakan organ, hingga kanker. Anak-anak dan ibu hamil adalah kelompok paling rentan.</p>

<h2>Cara Melaporkan Pembuangan Limbah B3 Ilegal</h2>
<ol>
<li><strong>Dokumentasikan bukti</strong>: Foto atau video lokasi pembuangan</li>
<li><strong>Catat lokasi</strong>: Koordinat GPS atau alamat lengkap</li>
<li><strong>Lapor ke KLHK</strong>: Melalui aplikasi SIMPONI atau call center 1500-940</li>
<li><strong>Lapor ke Dinas LH</strong>: Dinas Lingkungan Hidup setempat</li>
<li><strong>Gunakan LaporHijau</strong>: Platform ini memudahkan pelaporan dengan foto dan GPS</li>
</ol>

<p>Jangan ragu untuk melapor! Pelaporan yang cepat dapat mencegah kerusakan lingkungan yang lebih parah dan melindungi kesehatan masyarakat sekitar.</p>',
            ],
            [
                'author' => $admin,
                'title'    => 'Gerakan Zero Waste: Kisah Sukses Komunitas Peduli Lingkungan',
                'category' => 'Inspirasi',
                'content'  => '<p>Di tengah krisis sampah yang melanda berbagai kota di Indonesia, muncul cahaya harapan dari komunitas-komunitas kecil yang bergerak dengan filosofi zero waste. Mereka membuktikan bahwa perubahan nyata dimulai dari tingkat lokal.</p>

<h2>Apa itu Zero Waste?</h2>
<p>Zero waste bukan berarti benar-benar tidak menghasilkan sampah sama sekali, melainkan upaya maksimal untuk mengurangi sampah yang berakhir di TPA melalui strategi Refuse, Reduce, Reuse, Recycle, dan Rot (5R).</p>

<h2>Kisah Inspiratif: Bank Sampah Melati</h2>
<p>Di sebuah kelurahan di Pekanbaru, sekelompok ibu rumah tangga mendirikan Bank Sampah Melati pada 2019. Dimulai dengan 20 anggota, kini mereka telah berkembang menjadi lebih dari 200 anggota aktif yang berhasil mengurangi sampah ke TPA hingga 40%.</p>

<p>Sampah yang dikumpulkan dipilah dan dijual ke pengepul, menghasilkan pendapatan tambahan rata-rata Rp 150.000 per bulan per anggota. Surplus dana digunakan untuk kegiatan sosial dan penghijauan lingkungan.</p>

<h2>Langkah Memulai Gerakan Zero Waste di Komunitas</h2>
<ol>
<li><strong>Mulai dari diri sendiri</strong>: Audit sampah pribadi selama seminggu</li>
<li><strong>Edukasi keluarga</strong>: Libatkan seluruh anggota keluarga</li>
<li><strong>Bentuk kelompok kecil</strong>: Ajak 5-10 tetangga yang tertarik</li>
<li><strong>Buat sistem pemilahan</strong>: Organik, anorganik, dan B3</li>
<li><strong>Cari mitra</strong>: Hubungi pengepul daur ulang atau bank sampah terdekat</li>
<li><strong>Dokumentasikan progress</strong>: Catat dan bagikan di media sosial</li>
</ol>

<h2>Dampak Nyata yang Bisa Dirasakan</h2>
<p>Komunitas yang telah menerapkan zero waste melaporkan berkurangnya biaya retribusi sampah, lingkungan yang lebih bersih dan sehat, serta meningkatnya rasa kebersamaan antarwarga.</p>

<p>Seperti kata para pegiat lingkungan: "We don\'t need a handful of people doing zero waste perfectly. We need millions of people doing it imperfectly." Mari mulai dari sekarang!</p>',
            ],
        ];

        foreach ($articles as $data) {
            $title = $data['title'];
            $slug  = \Illuminate\Support\Str::slug($title);
            $base  = $slug;
            $count = 0;
            while (Article::where('slug', $slug)->exists()) {
                $slug = $base . '-' . ++$count;
            }

            Article::updateOrCreate(
                ['title' => $title],
                [
                    'author_id'    => $data['author']->id,
                    'slug'         => $slug,
                    'category'     => $data['category'],
                    'content'      => $data['content'],
                    'published_at' => now()->subDays(rand(1, 30)),
                ]
            );
        }

        $this->command->info('✅ ArticleSeeder: 5 artikel selesai.');
    }
}
