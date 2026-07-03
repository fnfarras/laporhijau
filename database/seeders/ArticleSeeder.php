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
                'content'  => '<p>Sampah plastik sekali pakai telah menjadi krisis global yang mengancam keberlangsungan ekosistem bumi, tidak terkecuali di Indonesia di mana jutaan ton sampah berakhir di tempat pembuangan akhir dan mencemari ekosistem perairan setiap tahunnya. Akumulasi sampah plastik yang sulit terurai secara alami membutuhkan ratusan tahun untuk terfragmentasi menjadi mikroplastik yang berbahaya bagi kesehatan. Oleh karena itu, kesadaran dan tindakan nyata untuk mengurangi konsumsi plastik harus segera dimulai dari lingkup terkecil, yaitu dari kebiasaan harian kita di dalam rumah.</p>

<p>Salah satu langkah awal yang sangat efektif adalah dengan selalu membawa kantong belanja ramah lingkungan yang terbuat dari kain atau bahan daur ulang ketika bepergian. Dengan menolak penggunaan kantong plastik kresek dari toko ritel atau pasar tradisional, kita secara langsung memutus rantai pasokan sampah plastik harian. Menyimpan beberapa kantong kain cadangan di dalam kendaraan atau tas kerja akan memastikan kita selalu siap berbelanja tanpa perlu meminta kantong plastik baru.</p>

<p>Selanjutnya, beralihlah ke botol minum (tumbler) dan wadah makanan isi ulang yang tahan lama. Penggunaan botol plastik sekali pakai saat bekerja, sekolah, atau berolahraga menyumbang persentase limbah yang sangat besar. Memilih tumbler berbahan stainless steel atau kaca yang aman tidak hanya ramah lingkungan tetapi juga lebih higienis dan ekonomis dalam jangka panjang karena kita tidak perlu terus-menerus membeli air kemasan sekali pakai.</p>

<p>Langkah keempat yang tidak kalah penting adalah memilah sampah rumah tangga secara konsisten antara sampah organik, anorganik, dan sampah berbahaya. Sampah dapur seperti sisa makanan dan kulit buah dapat diolah menjadi kompos mandiri yang sangat baik untuk nutrisi tanaman di pekarangan rumah. Sementara itu, sampah plastik yang bersih dan kering dapat dikumpulkan untuk disalurkan ke bank sampah terdekat atau industri daur ulang setempat guna memberikan nilai ekonomi baru.</p>

<p>Terakhir, kita perlu aktif mengedukasi anggota keluarga, tetangga, dan lingkungan sosial terdekat mengenai bahaya nyata dari pencemaran plastik. Mengajak mereka menolak penggunaan sedotan plastik, alat makan sekali pakai, serta memilih produk dengan kemasan yang minimal merupakan kontribusi kolektif yang berdampak besar. Dengan konsistensi melakukan langkah-langkah kecil ini secara bersama-sama, kita dapat menciptakan masa depan lingkungan yang lebih bersih, hijau, dan lestari untuk generasi mendatang.</p>',
            ],
            [
                'author' => $pemerintah,
                'title'    => 'Mengenal UU No. 32 Tahun 2009 tentang Perlindungan dan Pengelolaan Lingkungan Hidup',
                'category' => 'Regulasi',
                'content'  => '<p>Undang-Undang Nomor 32 Tahun 2009 tentang Perlindungan dan Pengelolaan Lingkungan Hidup (UU PPLH) merupakan payung hukum utama yang komprehensif dalam mengawal kelestarian lingkungan di wilayah Negara Kesatuan Republik Indonesia. Undang-undang ini dirancang untuk menjawab tantangan pemanasan global, penurunan kualitas lingkungan, serta eksploitasi sumber daya alam secara berlebihan yang mengancam daya dukung lingkungan. UU PPLH secara tegas mengamanatkan bahwa pembangunan nasional harus diselenggarakan berdasarkan prinsip berkelanjutan dan berwawasan lingkungan.</p>

<p>Salah satu aspek terpenting dari regulasi ini adalah penguatan instrumen pencegahan pencemaran dan perusakan lingkungan hidup. Pemerintah diwajibkan menyusun Kajian Lingkungan Hidup Strategis (KLHS) serta menerapkan Analisis Mengenai Dampak Lingkungan (AMDAL) secara ketat bagi setiap rencana usaha atau kegiatan yang berpotensi menimbulkan dampak penting. Melalui instrumen-instrumen evaluasi preventif ini, kelayakan lingkungan dari suatu proyek industri dapat dipantau dan dipertanggungjawabkan sebelum perizinan resmi diterbitkan.</p>

<p>UU PPLH juga memberikan jaminan perlindungan hukum yang kuat bagi setiap warga negara yang memperjuangkan hak atas lingkungan hidup yang baik dan sehat. Pasal 66 undang-undang ini secara khusus mengatur bahwa setiap orang yang memperjuangkan hak atas lingkungan hidup yang baik dan sehat tidak dapat dituntut secara pidana maupun digugat secara perdata. Hal ini memberikan ruang aman bagi masyarakat, aktivis, maupun jurnalis lingkungan untuk berpartisipasi aktif dalam melakukan pengawasan dan melaporkan dugaan pencemaran tanpa takut akan intimidasi hukum.</p>

<p>Di samping hak perlindungan, undang-undang ini juga mengatur sanksi pidana dan denda administratif yang berat bagi korporasi maupun individu yang terbukti melakukan pencemaran atau perusakan lingkungan secara sengaja maupun karena kelalaian. Pelaku pembakaran lahan hutan secara ilegal, pembuangan limbah beracun ke sungai, atau perusakan kawasan lindung dapat dijerat hukuman penjara hingga belasan tahun serta denda miliaran rupiah. Penegakan hukum yang tegas ini bertujuan untuk memberikan efek jera yang nyata serta menuntut pertanggungjawaban pemulihan lingkungan dari pihak yang merusak.</p>

<p>Secara keseluruhan, UU No. 32 Tahun 2009 menekankan pentingnya sinergi antara pemerintah pusat, pemerintah daerah, pelaku usaha, dan seluruh elemen masyarakat dalam menjaga kelestarian bumi. Pengelolaan lingkungan hidup bukanlah tugas tunggal instansi pemerintah, melainkan tanggung jawab bersama. Dengan memahami hak, kewajiban, dan regulasi yang ada, masyarakat diharapkan dapat menjadi garda terdepan dalam melaporkan setiap tindakan ilegal yang merusak alam demi keberlanjutan masa depan bangsa.</p>',
            ],
            [
                'author' => $admin,
                'title'    => 'Kompos dari Sampah Dapur: Panduan Lengkap untuk Pemula',
                'category' => 'Daur Ulang',
                'content'  => '<p>Membuat kompos secara mandiri dari sisa-sisa bahan dapur merupakan salah satu kontribusi nyata yang paling mudah dilakukan oleh setiap rumah tangga untuk menekan volume sampah yang dibuang ke tempat pembuangan akhir (TPA). Hampir separuh dari total produksi sampah harian rumah tangga kita merupakan material organik yang sebenarnya memiliki potensi nutrisi tinggi bagi tanah jika diolah dengan tepat. Dengan merubah sampah dapur menjadi pupuk kompos, kita tidak hanya mengurangi emisi gas metana dari penumpukan limbah basah, tetapi juga memperoleh pasokan pupuk organik gratis yang berkualitas tinggi.</p>

<p>Langkah pertama dalam pembuatan kompos adalah menyiapkan wadah komposter yang memadai, seperti ember plastik besar yang telah dilubangi kecil-kecil di bagian bawah dan samping untuk sirkulasi udara, atau lubang khusus di pekarangan rumah jika lahan mencukupi. Penempatan komposter di area yang teduh namun memiliki ventilasi udara yang baik sangat penting agar suhu di dalam wadah tetap terjaga selama proses dekomposisi berlangsung. Penggunaan alas berupa tanah gembur atau kompos lama pada bagian terbawah wadah akan membantu mempercepat aktivitas mikroba pengurai.</p>

<p>Selanjutnya, penting bagi pemula untuk memahami klasifikasi bahan organik yang dimasukkan ke dalam komposter, yaitu perbandingan antara bahan hijau (sumber nitrogen) dan bahan cokelat (sumber karbon). Bahan hijau meliputi sisa sayuran, kulit buah, potongan rumput segar, serta ampas kopi atau teh, sementara bahan cokelat mencakup daun kering, ranting kecil, kertas koran bekas yang dicacah, serta serbuk gergaji. Rasio ideal yang direkomendasikan adalah tiga bagian bahan cokelat untuk setiap satu bagian bahan hijau guna mencegah kelembaban berlebih yang dapat memicu aroma tidak sedap.</p>

<p>Proses pemeliharaan kompos relatif sederhana namun memerlukan konsistensi, di antaranya menjaga kelembaban tumpukan bahan agar tetap stabil seperti spons basah yang telah diperas airnya. Jika tumpukan terlalu kering, percikkan sedikit air secara merata; sebaliknya, jika terlalu basah atau berbau, tambahkan lebih banyak bahan cokelat kering lalu aduk secara merata. Pengadukan tumpukan kompos secara berkala setiap satu atau dua minggu sekali sangat dianjurkan untuk memasukkan oksigen baru yang dibutuhkan oleh bakteri pengurai aerobik.</p>

<p>Setelah jangka waktu sekitar enam hingga delapan minggu, bahan-bahan organik di dalam wadah akan berubah bentuk menjadi material berwarna cokelat gelap kehitaman dengan tekstur menyerupai tanah gembur dan berbau alami seperti humus hutan. Hasil akhir inilah yang disebut sebagai pupuk kompos matang yang siap diaplikasikan langsung pada tanaman hias, pot sayuran, maupun kebun buah. Menggunakan kompos buatan sendiri tidak hanya menyuburkan tanaman tetapi juga memberikan kepuasan tersendiri karena kita telah berhasil menutup siklus daur ulang alami langsung dari dapur rumah kita.</p>',
            ],
            [
                'author' => $pemerintah,
                'title'    => 'Bahaya Limbah B3 dan Cara Melaporkannya ke Pihak Berwenang',
                'category' => 'Edukasi',
                'content'  => '<p>Limbah Bahan Berbahaya dan Beracun (B3) merupakan sisa suatu usaha atau kegiatan yang mengandung zat, energi, atau komponen lain yang karena sifat, konsentrasi, dan jumlahnya dapat mencemari lingkungan hidup serta membahayakan kesehatan manusia. Karakteristik limbah B3 yang meliputi sifat mudah meledak, mudah terbakar, reaktif, beracun, korosif, dan infeksius menuntut pengelolaan khusus sejak dihasilkan hingga tahap pemusnahan akhir. Kecerobohan dalam menangani limbah jenis ini dapat berakibat fatal bagi kelangsungan hidup ekosistem di sekitarnya.</p>

<p>Dalam kehidupan sehari-hari, masyarakat tanpa sadar sering menghasilkan limbah kategori B3 dari peralatan rumah tangga yang rusak atau habis pakai. Contoh nyata di antaranya adalah baterai bekas yang mengandung logam berat merkuri, lampu neon yang mengandung uap merkuri beracun, botol aerosol kosmetik yang mudah meledak, obat-obatan kedaluwarsa, serta sisa pelarut cat atau oli kendaraan bekas. Membuang barang-barang berbahaya ini secara sembarangan bersama dengan sampah domestik biasa ke TPA umum dapat memicu kebocoran zat kimia berbahaya yang meresap ke dalam air tanah dan mencemari sumur warga sekitar.</p>

<p>Paparan zat berbahaya dari limbah B3 yang tidak terkelola dengan baik dapat masuk ke dalam tubuh manusia melalui saluran pernapasan, kontak kulit, maupun rantai makanan yang terkontaminasi. Akumulasi logam berat seperti timbal, merkuri, dan kadmium di dalam tubuh dalam jangka panjang berpotensi merusak sistem saraf pusat, memicu gagal ginjal akut, merusak organ reproduksi, hingga menyebabkan kanker serta cacat lahir pada bayi. Oleh karena itu, edukasi mengenai bahaya limbah B3 dan pemisahan mutlak di tingkat rumah tangga harus menjadi prioritas utama komunitas.</p>

<p>Apabila masyarakat menemukan adanya tindakan pembuangan limbah industri atau B3 secara ilegal di lahan kosong, selokan, maupun daerah aliran sungai, tindakan cepat untuk melapor sangat diperlukan. Langkah pertama adalah mendokumentasikan temuan tersebut berupa foto atau video yang jelas mengenai tumpukan limbah dan plat nomor kendaraan pengangkut jika memungkinkan, serta mencatat koordinat lokasi kejadian secara presisi. Selanjutnya, laporan resmi dapat dikirimkan kepada Dinas Lingkungan Hidup setempat atau melalui portal pengaduan resmi Kementerian Lingkungan Hidup dan Kehutanan (KLHK).</p>

<p>Penggunaan platform pengaduan digital seperti LaporHijau juga sangat disarankan karena sistemnya yang terintegrasi secara langsung dengan peta interaktif dan notifikasi instan kepada instansi berwenang serta relawan setempat. Dengan melaporkan indikasi pelanggaran pengelolaan limbah secara cepat dan akurat, kita membantu mencegah terjadinya bencana pencemaran lingkungan yang lebih luas dan melindungi keselamatan ratusan jiwa di sekitar lokasi pembuangan. Peran aktif masyarakat dalam pengawasan adalah kunci utama terciptanya lingkungan yang bersih, aman, dan bebas dari bahaya racun limbah B3.</p>',
            ],
            [
                'author' => $admin,
                'title'    => 'Gerakan Zero Waste: Kisah Sukses Komunitas Peduli Lingkungan',
                'category' => 'Inspirasi',
                'content'  => '<p>Gerakan gaya hidup bebas sampah atau dikenal dengan sebutan "Zero Waste" kini bukan lagi sekadar tren gaya hidup melainkan gerakan perubahan sosial yang masif di berbagai penjuru dunia, termasuk di Indonesia. Gerakan ini menekankan prinsip desain ulang siklus hidup sumber daya agar semua produk dapat digunakan kembali secara optimal tanpa menyisakan sampah yang dibuang ke alam. Melalui pemahaman yang mendalam mengenai pentingnya pengurangan konsumsi barang sekali pakai, komunitas-komunitas lokal mulai membuktikan bahwa perubahan besar dapat diwujudkan melalui komitmen bersama.</p>

<p>Kisah inspiratif datang dari komunitas Bank Sampah di salah satu kelurahan padat penduduk di Pekanbaru, yang diinisiasi oleh sekelompok ibu rumah tangga pada awal tahun 2021. Berawal dari keprihatinan mendalam atas menumpuknya sampah rumah tangga di sepanjang gang pemukiman dan sering tersumbatnya saluran drainase akibat sampah plastik, mereka mendirikan wadah edukasi pilah sampah mandiri. Dengan tekad yang kuat, mereka melatih warga sekitar untuk mulai memisahkan sampah bernilai ekonomi langsung dari sumbernya di dalam rumah.</p>

<p>Dalam kurun waktu dua tahun, inisiatif lokal ini berhasil berkembang pesat dengan merangkul lebih dari tiga ratus kepala keluarga sebagai nasabah aktif bank sampah. Sampah anorganik seperti botol plastik, kertas, kardus, dan logam yang terkumpul ditimbang secara berkala dan dikonversi menjadi tabungan rupiah yang dapat dicairkan oleh warga untuk membantu kebutuhan pokok harian. Lebih dari itu, sampah organik rumah tangga diolah bersama-sama menggunakan metode lubang biopori dan pembuatan pupuk cair organik untuk menyuburkan tanaman hijau di sepanjang koridor pemukiman warga.</p>

<p>Dampak ekologis dari gerakan kolektif ini sangat signifikan, di antaranya penurunan volume sampah yang diangkut ke TPA regional hingga mencapai tiga puluh lima persen setiap bulannya. Lingkungan pemukiman yang dulunya kumuh kini berubah menjadi asri, bersih, dan bebas dari genangan air banjir saat musim hujan tiba karena saluran air yang terjaga bebas dari sumbatan plastik. Keberhasilan ini menarik perhatian pemerintah kota setempat yang kemudian menetapkan wilayah kelurahan tersebut sebagai percontohan kawasan Kampung Iklim bebas sampah tingkat provinsi.</p>

<p>Kisah sukses komunitas ini membuktikan bahwa keberhasilan gerakan penyelamatan lingkungan tidak bergantung pada segelintir orang yang melakukannya secara sempurna, melainkan jutaan orang yang bersedia memulainya dengan tidak sempurna namun konsisten. Langkah kecil seperti membatasi konsumsi plastik sekali pakai, membuat kompos dari sisa dapur, serta aktif memilah sampah jika dilakukan bersama-sama akan melipatgandakan dampak positif bagi kelestarian bumi. Semoga kisah perjuangan komunitas ini mampu menginspirasi wilayah lain untuk segera bergerak demi kelestarian alam dan lingkungan hidup kita bersama.</p>',
            ],
        ];

        foreach ($articles as $data) {
            $title = $data['title'];
            $slug  = Str::slug($title);
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

        $this->command->info('✅ ArticleSeeder: 5 artikel berkualitas tinggi selesai.');
    }
}
