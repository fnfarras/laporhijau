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
            // DATA BARU TAMBAHAN
            [
                'title'       => 'Tumpukan Sampah Plastik di Pantai Purus dekat Danau Cimpago',
                'description' => 'Sampah botol plastik, kantong kresek, dan sisa jajanan menumpuk di bebatuan pemecah ombak Pantai Purus tepat di depan kawasan Danau Cimpago. Bau tidak sedap mulai menyengat di sekitar area jogging track yang biasa ramai dikunjungi warga pada sore hari.',
                'address'     => 'Jl. Samudera, Purus, Kec. Padang Barat, Kota Padang',
                'latitude'    => -0.9284000,
                'longitude'   => 100.3512000,
                'status'      => 'resolved',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Limbah Pasar Higienis Koto Lalang Mencemari Parit',
                'description' => 'Air sisa pencucian daging dan sayuran dari pasar tradisional Koto Lalang dialirkan langsung ke parit warga tanpa melalui bak penyaringan. Air parit berwarna abu-abu keruh dengan sisa lemak membeku, memicu bau busuk yang mengganggu warga perumahan sekitar.',
                'address'     => 'Koto Lalang, Kec. Lubuk Kilangan, Kota Padang',
                'latitude'    => -0.9512000,
                'longitude'   => 100.4321000,
                'status'      => 'pending',
                'category'    => 'Pencemaran Air',
                'photo'       => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Pembakaran Jerami Padi Skala Besar di Sawah Kuranji',
                'description' => 'Petani melakukan pembakaran jerami sisa panen padi secara bersamaan dalam volume yang sangat besar di area persawahan Kuranji. Asap tebal meluas ke jalan raya utama, mengurangi jarak pandang berkendara dan membuat napas terasa sesak.',
                'address'     => 'Kawasan Sawah Kuranji, Kec. Kuranji, Kota Padang',
                'latitude'    => -0.9315000,
                'longitude'   => 100.4156000,
                'status'      => 'verified',
                'category'    => 'Pencemaran Udara',
                'photo'       => 'https://images.unsplash.com/photo-1532601224476-15c79f2f7a51?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Kerusakan Pohon Pelindung Akibat Pemasangan Baliho Iklan di Jl. Sudirman',
                'description' => 'Beberapa pohon pelindung jalan di Jalan Sudirman sengaja dirusak dengan cara dipaku besar-besar untuk memasang papan reklame komersial. Ada pula dahan pohon yang dipangkas secara liar agar papan iklan tidak tertutup daun.',
                'address'     => 'Jl. Jenderal Sudirman, Kec. Padang Barat, Kota Padang',
                'latitude'    => -0.9412000,
                'longitude'   => 100.3685000,
                'status'      => 'in_progress',
                'category'    => 'Kerusakan Pohon & RTH',
                'photo'       => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Saluran Parit Tersumbat di Depan RSUP M. Djamil Padang',
                'description' => 'Drainase di jalan raya utama depan Rumah Sakit M. Djamil tersumbat oleh tumpukan endapan lumpur semen sisa proyek dan sampah plastik. Ketika hujan, air cepat meluap ke gerbang rumah sakit menghambat akses masuk mobil ambulans.',
                'address'     => 'Jl. Perintis Kemerdekaan, Sawahan, Kec. Padang Timur, Kota Padang',
                'latitude'    => -0.9405000,
                'longitude'   => 100.3698000,
                'status'      => 'resolved',
                'category'    => 'Banjir & Drainase',
                'photo'       => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Ceceran Tanah Clay Proyek Jalan By Pass Km 12',
                'description' => 'Truk pengangkut tanah timbunan untuk proyek pembangunan jalan tidak menutup muatan dengan terpal, menyebabkan tanah clay basah tercecer di sepanjang jalan By Pass. Saat kering, ceceran tanah ini memicu polusi debu tebal, dan saat hujan berubah menjadi lumpur licin yang membahayakan pengendara motor.',
                'address'     => 'Jl. By Pass KM 12, Kec. Kuranji, Kota Padang',
                'latitude'    => -0.9015000,
                'longitude'   => 100.4012000,
                'status'      => 'pending',
                'category'    => 'Pencemaran Tanah',
                'photo'       => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Sampah Menumpuk di Kolong Jembatan Purus',
                'description' => 'Kolong jembatan dekat muara Pantai Purus dijadikan tempat pembuangan sampah liar oleh oknum warga sekitar. Sampah kantong plastik hitam, kasur bekas, dan sampah plastik makanan menumpuk dan hanyut ke laut saat air sungai pasang.',
                'address'     => 'Jembatan Purus, Kec. Padang Barat, Kota Padang',
                'latitude'    => -0.9252000,
                'longitude'   => 100.3508000,
                'status'      => 'resolved',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Pencemaran Deterjen di Sungai Batang Jirak Lubuk Begalung',
                'description' => 'Aliran sungai kecil Batang Jirak di kawasan pemukiman Lubuk Begalung tertutup busa putih tebal akibat pembuangan limbah deterjen usaha laundry komersial skala menengah. Air berbau kimia menyengat dan mengancam biota air tawar setempat.',
                'address'     => 'Lubuk Begalung, Kec. Lubuk Begalung, Kota Padang',
                'latitude'    => -0.9712000,
                'longitude'   => 100.3989000,
                'status'      => 'verified',
                'category'    => 'Pencemaran Air',
                'photo'       => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Penebangan Liar Pohon Mahoni di Jalur Hijau By Pass',
                'description' => 'Ditemukan aksi pemotongan pohon mahoni pelindung jalan di sepanjang jalur hijau By Pass Padang. Pohon yang ditebang secara liar ini dipotong pada malam hari, menyisakan batang yang tidak rapi dan merusak kerindangan area.',
                'address'     => 'Jl. By Pass, Kel. Pisang, Kec. Pauh, Kota Padang',
                'latitude'    => -0.9521000,
                'longitude'   => 100.4005000,
                'status'      => 'in_progress',
                'category'    => 'Kerusakan Pohon & RTH',
                'photo'       => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Banjir Genangan di Simpang Haru Akibat Drainase Rusak',
                'description' => 'Setiap terjadi hujan dengan intensitas sedang, kawasan Simpang Haru Padang selalu terendam banjir setinggi lutut orang dewasa. Hal ini disebabkan karena dinding saluran air drainase di bawah jalan runtuh dan menyumbat total aliran air pembuangan.',
                'address'     => 'Simpang Haru, Kec. Padang Timur, Kota Padang',
                'latitude'    => -0.9489000,
                'longitude'   => 100.3756000,
                'status'      => 'resolved',
                'category'    => 'Banjir & Drainase',
                'photo'       => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Polusi Asap Hitam dari Bengkel Bubut di Kampung Pondok',
                'description' => 'Sebuah bengkel bubut besi di kawasan Kampung Pondok mengeluarkan asap hitam pekat berbau logam terbakar dari ventilasinya. Bengkel ini berada sangat dekat dengan rumah warga, sehingga asap masuk ke ruang tamu dan mengganggu kenyamanan keluarga.',
                'address'     => 'Kampung Pondok, Kec. Padang Barat, Kota Padang',
                'latitude'    => -0.9525000,
                'longitude'   => 100.3592000,
                'status'      => 'pending',
                'category'    => 'Pencemaran Udara',
                'photo'       => 'https://images.unsplash.com/photo-1532601224476-15c79f2f7a51?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Pembuangan Sampah Rumah Tangga di Pinggir Rel Kereta Api Alai',
                'description' => 'Masyarakat membuang sampah rumah tangga secara liar di sepanjang bantaran rel kereta api kawasan Alai. Selain mengganggu keindahan estetika pemukiman, tumpukan sampah plastik yang berserakan berpotensi menjadi sarang penyakit dan mengundang tikus.',
                'address'     => 'Alai Parak Kopi, Kec. Padang Utara, Kota Padang',
                'latitude'    => -0.9312000,
                'longitude'   => 100.3745000,
                'status'      => 'resolved',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Air Sumur Warga Berbau Minyak di Sekitar SPBU Lolong',
                'description' => 'Beberapa sumur gali milik warga di dekat kawasan Lolong mengeluarkan bau bahan bakar minyak (BBM) yang cukup menyengat. Air sumur juga tampak sedikit berminyak saat ditampung. Warga menduga terjadi kebocoran tangki pendam SPBU di dekat lokasi pemukiman.',
                'address'     => 'Lolong Belanti, Kec. Padang Utara, Kota Padang',
                'latitude'    => -0.9204000,
                'longitude'   => 100.3556000,
                'status'      => 'in_progress',
                'category'    => 'Pencemaran Tanah',
                'photo'       => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Dahan Pohon Lapuk Menggantung di Dekat Kampus UPI YPTK',
                'description' => 'Terdapat dahan pohon besar yang telah lapuk dan hampir patah menggantung di atas jalan raya padat dekat gerbang masuk Universitas Putra Indonesia (UPI YPTK). Kondisi ini sangat membahayakan keselamatan para mahasiswa pengendara motor yang sering melintas.',
                'address'     => 'Jl. Raya Lubuk Begalung, Kec. Lubuk Begalung, Kota Padang',
                'latitude'    => -0.9745000,
                'longitude'   => 100.3995000,
                'status'      => 'verified',
                'category'    => 'Kerusakan Pohon & RTH',
                'photo'       => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Sampah Plastik Menumpuk di Wisata Jembatan Siti Nurbaya',
                'description' => 'Botol plastik, kemasan gelas air mineral, dan kantong plastik makanan menumpuk di tangga-tangga pedestrian Jembatan Siti Nurbaya. Banyaknya pedagang makanan ringan malam hari yang tidak membersihkan lapaknya memperparah kondisi jembatan bersejarah ini.',
                'address'     => 'Jembatan Siti Nurbaya, Kel. Batang Arau, Padang Selatan, Kota Padang',
                'latitude'    => -0.9568000,
                'longitude'   => 100.3545000,
                'status'      => 'resolved',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Limbah Rumah Potong Hewan di Lubuk Buaya',
                'description' => 'Adanya indikasi pembuangan limbah darah dan sisa kotoran dari tempat penjagalan hewan langsung dialirkan ke dalam sungai kecil di dekat wilayah Lubuk Buaya. Air sungai berubah warna menjadi kemerahan dan berbau amis menyengat.',
                'address'     => 'Lubuk Buaya, Kec. Koto Tangah, Kota Padang',
                'latitude'    => -0.8123000,
                'longitude'   => 100.3345000,
                'status'      => 'pending',
                'category'    => 'Pencemaran Air',
                'photo'       => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Asap Kendaraan Umum Bermesin Diesel di Terminal Anak Air',
                'description' => 'Banyak angkutan umum bermesin diesel tua mengeluarkan asap knalpot hitam pekat yang luar biasa tebal di dalam area Terminal Anak Air. Asap hitam menyelimuti halte ruang tunggu penumpang dan menyebabkan polusi udara yang buruk.',
                'address'     => 'Terminal Anak Air, Kec. Koto Tangah, Kota Padang',
                'latitude'    => -0.8012000,
                'longitude'   => 100.3456000,
                'status'      => 'verified',
                'category'    => 'Pencemaran Udara',
                'photo'       => 'https://images.unsplash.com/photo-1532601224476-15c79f2f7a51?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Tanah Longsor Skala Kecil Menutupi RTH di Panorama II Bungus',
                'description' => 'Terjadi reruntuhan tanah tebing bukit akibat hujan deras yang menimbun area pejalan kaki di jalur Ruang Terbuka Hijau Panorama II jalur Bungus. Material lumpur setinggi 30 cm menutup sebagian akses pejalan kaki.',
                'address'     => 'Panorama II, Bungus Teluk Kabung, Kota Padang',
                'latitude'    => -1.0543000,
                'longitude'   => 100.3982000,
                'status'      => 'in_progress',
                'category'    => 'Lainnya',
                'photo'       => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Tumpukan Sampah di Pesisir Pantai Nirwana Padang',
                'description' => 'Sisa-sisa kemasan makanan, kayu lapuk, dan botol kemasan menumpuk di pesisir Pantai Nirwana. Sampah tersebut terbawa arus laut pasang dan terdampar mengotori area berpasir yang menjadi ikon pariwisata Padang Selatan.',
                'address'     => 'Pantai Nirwana, Kec. Padang Selatan, Kota Padang',
                'latitude'    => -0.9995000,
                'longitude'   => 100.3698000,
                'status'      => 'resolved',
                'category'    => 'Sampah & Kebersihan',
                'photo'       => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title'       => 'Drainase Mampet Total di Simpang Kalawi',
                'description' => 'Saluran drainase besar di perempatan Simpang Kalawi tersumbat endapan lumpur tebal yang telah mengeras serta tumpukan kayu bekas proyek. Air selokan tergenang hitam, berlumut, dan menjadi tempat perkembangbiakan jentik nyamuk.',
                'address'     => 'Simpang Kalawi, Kec. Kuranji, Kota Padang',
                'latitude'    => -0.9287000,
                'longitude'   => 100.3923000,
                'status'      => 'resolved',
                'category'    => 'Banjir & Drainase',
                'photo'       => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=800&q=80',
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
