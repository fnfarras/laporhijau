# LaporHijau 🌿 — Platform Civic Tech Kolaborasi Aksi Lingkungan Nasional

> **Solusi Digital untuk Keberlanjutan Lingkungan Hidup di Indonesia.**  
> Dikembangkan untuk **Lomba Web Development NIFC 5.0** dengan tema *"Empowering Communities Through Digital Solutions for Sustainability"*.

---

## 🔑 Akun Uji Coba (Demo Accounts untuk Juri)

Untuk mempermudah pengujian alur sistem oleh Dewan Juri, gunakan akun-akun demo berikut (Password untuk semua akun: `password`):

| Peran (Role) | Alamat Email | Hak Akses / Fitur Utama untuk Diuji |
| :--- | :--- | :--- |
| **Masyarakat** | `masyarakat@laporhijau.test` | Membuat laporan baru, RSVP event komunitas, menukar poin dengan hadiah & sertifikat. |
| **Relawan** | `relawan@laporhijau.test` | Memverifikasi/menolak laporan masuk dari warga, mengunggah bukti lapangan, membuat event baru. |
| **Pemerintah** | `pemerintah@laporhijau.test` | Dashboard analitik (Chart.js), memproses penanganan laporan (`in_progress` $\rightarrow$ `resolved`). |
| **Admin** | `admin@laporhijau.test` | Mengelola kategori laporan, data pengguna, data hadiah, dan artikel edukasi. |

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

Aplikasi ini dikembangkan menggunakan teknologi modern, aman, dan berkinerja tinggi:

* **Backend Framework:** Laravel 11 (PHP 8.2+)
* **Database:** MySQL 8
* **Frontend Logic & Styling:** Tailwind CSS v4.0 & Alpine.js (Plus Jakarta Sans Font)
* **Peta Interaktif:** Leaflet.js (Pelacakan koordinat GPS laporan)
* **Visualisasi Grafik:** Chart.js (Dashboard analitik statistik pemerintah)
* **Manajemen Otorisasi:** Spatie Laravel-Permission (4 Role Sistem)
* **Penyimpanan Gambar:** Cloudinary Integration (Foto laporan disimpan di Cloud secara aman)

---

## ⚙️ Panduan Instalasi & Menjalankan Website Secara Lokal

Ikuti langkah-langkah mudah berikut untuk menjalankan proyek di komputer lokal Anda:

### 1. Kloning Proyek & Install Dependensi
Buka terminal Anda dan jalankan perintah berikut:
```bash
# Kloning repository
git clone https://github.com/username/laporhijau.git
cd laporhijau

# Install dependensi PHP (Composer)
composer install

# Install dependensi JavaScript (NPM)
npm install
```

### 2. Konfigurasi Environment (`.env`)
Salin file konfigurasi environment:
```bash
cp .env.example .env
```
Buka file `.env` baru tersebut, lalu sesuaikan koneksi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laporhijau
DB_USERNAME=root
DB_PASSWORD=
```
*(Opsional)* Tambahkan konfigurasi API Cloudinary untuk mendukung unggahan foto laporan:
```env
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
```

### 3. Migrasi & Seeder Database
Generate application key dan isi database dengan data demo otomatis:
```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

### 4. Jalankan Aplikasi
Jalankan server lokal Laravel dan compiler aset frontend:

* **Terminal 1** (Server Laravel):
  ```bash
  php artisan serve
  ```
* **Terminal 2** (Compiler Frontend):
  ```bash
  npm run dev
  ```

Buka **`http://127.0.0.1:8000`** pada browser Anda untuk mengakses aplikasi LaporHijau.

---

## ✨ Fitur Unggulan LaporHijau

* **Peta Pelaporan Real-Time:** Menampilkan sebaran masalah lingkungan secara spasial menggunakan Leaflet.js.
* **Laporan Anonim:** Masyarakat dapat melapor secara cepat tanpa login dan melacak progres laporan menggunakan kode lacak unik.
* **Gamifikasi Poin & Badge:** Sistem poin otomatis berbasis *Laravel Event & Listener* untuk memotivasi partisipasi aktif komunitas.
* **Toko Hadiah & Sertifikat Digital:** Tukarkan poin dengan hadiah ramah lingkungan dan unduh sertifikat penghargaan resmi.
* **Open Data Dashboard:** Halaman publik berisi statistik laporan dalam bentuk grafik Chart.js yang dapat diekspor langsung ke format CSV, Excel, dan GeoJSON.

---
*LaporHijau 🌿 — Bersama menjaga lingkungan Indonesia untuk masa depan yang lebih hijau.*
