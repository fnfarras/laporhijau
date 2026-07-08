# LaporHijau 🌿 — Platform Civic Tech Kolaborasi Aksi Lingkungan Nasional

> **Solusi Digital untuk Keberlanjutan Lingkungan Hidup di Indonesia.**  
> Dikembangkan untuk **Lomba Web Development NIFC 5.0** dengan tema *"Empowering Communities Through Digital Solutions for Sustainability"*.

---

## 🔑 Akun Uji Coba (Demo Accounts)

Gunakan akun-akun berikut untuk menguji fitur berdasarkan masing-masing peran (Password: `password`):

| 👤 Peran (Role) | 📧 Email Login | ⚡ Fitur Utama untuk Diuji |
| :--- | :--- | :--- |
| **Masyarakat** | `masyarakat@laporhijau.test` | Membuat laporan baru, RSVP event aksi, dan tukar poin hadiah + sertifikat. |
| **Relawan** | `relawan@laporhijau.test` | Verifikasi/tolak laporan warga, unggah foto bukti lapangan, buat event aksi. |
| **Pemerintah** | `pemerintah@laporhijau.test` | Pantau dashboard analitik (Chart.js), proses laporan (`in_progress` & `resolved`). |
| **Admin** | `admin@laporhijau.test` | Manajemen penuh kategori, data pengguna, data hadiah, dan artikel edukasi. |

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

* **Backend Framework:** Laravel 11 (PHP 8.2+)
* **Database:** MySQL 8.0
* **Frontend UI:** Tailwind CSS v4.0 & Alpine.js (Font: *Plus Jakarta Sans*)
* **Peta Spasial:** Leaflet.js (Pelacakan koordinat lokasi laporan)
* **Grafik Analitik:** Chart.js (Visualisasi statistik di dashboard pemerintah)
* **Otorisasi Role:** Spatie Laravel-Permission
* **Cloud Storage:** Cloudinary Integration (Penyimpanan foto laporan di cloud)

---

## ⚙️ Panduan Instalasi Lokal (Quick Setup)

Pastikan **PHP >= 8.2**, **Composer**, **Node.js (NPM)**, dan **MySQL** sudah terinstal di komputer Anda.

### Langkah 1: Kloning & Install Dependensi
Jalankan perintah ini berurutan di terminal Anda:
```bash
# Kloning repository & masuk ke direktori
git clone https://github.com/username/laporhijau.git
cd laporhijau

# Install library backend & frontend
composer install
npm install
```

### Langkah 2: Konfigurasi Environment & Database
1. Buat database baru bernama **`laporhijau`** di MySQL Anda.
2. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
3. Buka file `.env` dan sesuaikan koneksi database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laporhijau
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Langkah 3: Setup Key & Data Demo (Seeder)
Jalankan perintah berikut untuk mengisi database dengan data demo otomatis:
```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

### Langkah 4: Jalankan Aplikasi
Jalankan perintah berikut di **dua terminal terpisah**:

* **Terminal 1 (Laravel Server):**
  ```bash
  php artisan serve
  ```
* **Terminal 2 (Compiler Frontend):**
  ```bash
  npm run dev
  ```

Buka **`http://127.0.0.1:8000`** di browser Anda.

---

## ✨ Fitur Unggulan LaporHijau

1. **Peta Pelaporan Real-Time:** Visualisasi lokasi dan kategori masalah lingkungan menggunakan Leaflet.js.
2. **Laporan Anonim:** Warga dapat mengirim laporan tanpa login dan memantau status via kode unik.
3. **Gamifikasi Kontribusi:** Perolehan poin otomatis (Event/Listener) & badge penghargaan bagi pelapor aktif.
4. **Toko Hadiah Poin:** Penukaran poin kontribusi dengan hadiah fisik & sertifikat penghargaan digital.
5. **Open Data Dashboard:** Dashboard statistik publik yang dapat diekspor langsung ke format CSV, Excel, dan GeoJSON.

---
*LaporHijau 🌿 — Bersama menjaga kelestarian alam Indonesia.*
