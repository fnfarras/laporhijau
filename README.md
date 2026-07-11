<div align="center">

# 🌿 LaporHijau
### Platform Civic Tech Kolaborasi Aksi Lingkungan Nasional

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Tests](https://img.shields.io/badge/Tests-32%20Passed-16a34a?style=for-the-badge&logo=checkmarx&logoColor=white)](#)
[![License](https://img.shields.io/badge/License-MIT-yellow?style=for-the-badge)](LICENSE)

> **Solusi Digital untuk Keberlanjutan Lingkungan Hidup di Indonesia.**  
> Dikembangkan untuk **Lomba Web Development NIFC 5.0** dengan tema  
> *"Empowering Communities Through Digital Solutions for Sustainability"*

**[🌐 Live Demo](https://laporhijau-production.up.railway.app)** &nbsp;|&nbsp;
**[📹 Video Demo](#-video-demo)**  &nbsp;|&nbsp;
**[📖 Dokumentasi](#️-panduan-instalasi-lokal)**

</div>

---

## 📌 Deskripsi Proyek

**LaporHijau** adalah platform *civic technology* berbasis web yang memungkinkan masyarakat umum untuk **melaporkan, memantau, dan berkolaborasi** dalam penanganan masalah lingkungan hidup secara transparan dan terstruktur bersama relawan dan instansi pemerintah daerah.

### 🎯 Permasalahan yang Diselesaikan

Indonesia menghadapi tantangan serius dalam pengelolaan lingkungan hidup:
- Banyak masalah lingkungan (sampah liar, pencemaran sungai, kerusakan pohon) **tidak pernah ditindaklanjuti** karena tidak ada saluran pelaporan yang efektif.
- **Kurangnya transparansi** dalam proses penanganan laporan warga oleh instansi terkait.
- Rendahnya **partisipasi masyarakat** dalam aksi lingkungan akibat minimnya apresiasi dan insentif.

### 💡 Solusi yang Ditawarkan

LaporHijau menjawab tantangan tersebut dengan:
- **Pelaporan terstruktur** berupa laporan masalah lingkungan dengan foto, peta lokasi, dan kategori yang jelas.
- **Alur kerja transparan** melalui status laporan multi-tahap *(pending → verified → in_progress → resolved)* yang bisa dipantau publik.
- **Sistem gamifikasi** (poin, badge, leaderboard) yang memberi apresiasi nyata kepada warga aktif.
- **Open Data** publik untuk memastikan akuntabilitas dan memungkinkan penelitian berbasis data lingkungan.

---

## 🚀 Fitur Unggulan

| # | Fitur | Deskripsi |
|---|---|---|
| 1 | 🗺️ **Peta Real-Time** | Visualisasi interaktif semua laporan dengan filter kategori & status menggunakan Leaflet.js |
| 2 | 📝 **Laporan Multi-Status** | Alur kerja transparan: `pending → verified → in_progress → resolved` dengan log perubahan status |
| 3 | 🔒 **Laporan Anonim** | Warga bisa melapor tanpa login, dengan kode unik untuk memantau status laporan mereka |
| 4 | 🏆 **Gamifikasi & Leaderboard** | Sistem poin otomatis via Laravel Event/Listener, badge penghargaan, dan ranking komunitas |
| 5 | 🎁 **Toko Hadiah Poin** | Tukar poin dengan hadiah fisik & sertifikat digital (PDF) yang bisa diverifikasi publik |
| 6 | 📊 **Open Data Dashboard** | Statistik publik + ekspor CSV, Excel, dan GeoJSON untuk transparansi data lingkungan |
| 7 | 📅 **Manajemen Event** | Buat & daftarkan diri ke event aksi lingkungan (bersih-bersih, tanam pohon, dll.) |
| 8 | 📚 **Artikel Edukasi** | Konten edukasi lingkungan yang dapat ditulis oleh admin dan instansi pemerintah |
| 9 | 🔔 **Notifikasi Real-time** | Sistem notifikasi in-app untuk setiap perubahan status laporan |
| 10 | 🌙 **Dark Mode** | Dukungan mode gelap penuh dengan preferensi yang tersimpan di browser |
| 11 | 📱 **Mobile Responsive** | Bottom navigation bar khusus mobile, desain responsif di semua ukuran layar |
| 12 | 🤖 **SLA Monitoring** | Pantauan batas waktu verifikasi (48 jam) dan penanganan laporan (7 hari) |

---

## 🛠️ Teknologi yang Digunakan

### Backend
| Teknologi | Versi | Kegunaan |
|---|---|---|
| **PHP** | 8.2+ | Bahasa pemrograman server |
| **Laravel** | 11.x | Framework MVC utama |
| **MySQL** | 8.0 | Database relasional |
| **Spatie Laravel-Permission** | 6.x | Manajemen role: masyarakat, relawan, pemerintah, admin |
| **Cloudinary** | SDK PHP | Penyimpanan foto laporan di cloud |

### Frontend
| Teknologi | Versi | Kegunaan |
|---|---|---|
| **Tailwind CSS** | v4.0 | Utility-first CSS framework |
| **Alpine.js** | 3.x | Interaktivitas UI ringan (dark mode, toast, dropdown) |
| **Leaflet.js** | 1.9.4 | Peta interaktif pelaporan |
| **Chart.js** | 4.x | Grafik statistik dashboard pemerintah & open data |
| **Plus Jakarta Sans** | — | Tipografi premium (Google Fonts) |

### Infrastruktur & DevOps
| Teknologi | Kegunaan |
|---|---|
| **Railway** | Platform deployment cloud (production) |
| **GitHub Actions** *(opsional)* | CI/CD pipeline |
| **Laravel Queue** | Pengiriman notifikasi dan badge secara asinkron |

### Pola Arsitektur & Best Practice
- **Resource Controller** — satu controller per resource, dipisah per role
- **Form Request** — validasi terpisah dari controller untuk setiap endpoint
- **Laravel Policy** — otorisasi berbasis model (Report, Article, Event)
- **Event & Listener Pattern** — pemberian poin 100% melalui event, bukan hardcode
- **Service Layer** — `CloudinaryService` untuk abstraksi upload foto
- **DB::transaction()** — operasi poin atomik (tidak bisa setengah berhasil)

---

## 🔑 Akun Demo (untuk Juri)

> Password semua akun: **`password`**

| Peran | Email | Yang Bisa Diuji |
|---|---|---|
| 🌱 **Masyarakat** | `masyarakat@laporhijau.test` | Buat laporan, pantau status, RSVP event, tukar poin & sertifikat |
| 🙋 **Relawan** | `relawan@laporhijau.test` | Verifikasi/tolak laporan, upload foto hasil, buat event komunitas |
| 🏛️ **Pemerintah** | `pemerintah@laporhijau.test` | Dashboard analitik, update status `in_progress` & `resolved` |
| ⚙️ **Admin** | `admin@laporhijau.test` | Manajemen penuh: user, kategori, hadiah, artikel, data laporan |

---

## ⚙️ Panduan Instalasi Lokal

### Prasyarat
Pastikan perangkat sudah terinstal:
- **PHP** >= 8.2 + ekstensi: `pdo_mysql`, `mbstring`, `fileinfo`, `curl`
- **Composer** >= 2.x
- **Node.js** >= 18.x + NPM
- **MySQL** >= 8.0

### Langkah 1 — Clone & Install Dependensi
```bash
# Clone repository
git clone https://github.com/fnfarras/laporhijau.git
cd laporhijau

# Install dependensi PHP
composer install

# Install dependensi Node.js
npm install
```

### Langkah 2 — Konfigurasi Environment
```bash
# Salin file konfigurasi
cp .env.example .env

# Generate app key
php artisan key:generate
```

Buka file `.env` dan sesuaikan konfigurasi database:
```env
APP_NAME="LaporHijau"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laporhijau
DB_USERNAME=root
DB_PASSWORD=

# Cloudinary (opsional untuk tes lokal — foto tidak akan terupload)
CLOUDINARY_URL=cloudinary://api_key:api_secret@cloud_name

# Queue (gunakan sync untuk lokal)
QUEUE_CONNECTION=sync
```

### Langkah 3 — Migrasi Database & Seed Data Demo
```bash
# Buat semua tabel & isi dengan data demo realistis
php artisan migrate:fresh --seed
```

Data yang di-seed secara otomatis:
- ✅ 4 role pengguna (masyarakat, relawan, pemerintah, admin)
- ✅ 12 akun demo dengan berbagai tingkat poin
- ✅ 7 kategori laporan lingkungan
- ✅ 30+ laporan dengan status bervariasi dan koordinat nyata
- ✅ 5 artikel edukasi berkualitas tinggi dengan foto cover
- ✅ 5+ event aksi lingkungan
- ✅ 8 badge gamifikasi
- ✅ 5 item hadiah poin (termasuk sertifikat digital)

### Langkah 4 — Jalankan Aplikasi
Buka **dua terminal terpisah**:

**Terminal 1 — Laravel Development Server:**
```bash
php artisan serve
```

**Terminal 2 — Tailwind CSS Compiler:**
```bash
npm run dev
```

Akses aplikasi di: **http://127.0.0.1:8000**

---

## 🏗️ Struktur Direktori Penting

```
laporhijau/
├── app/
│   ├── Events/              # Domain events (ReportSubmitted, EventRsvpRegistered, dll.)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/       # Controller khusus role Admin
│   │   │   ├── Pemerintah/  # Controller khusus role Pemerintah
│   │   │   └── Relawan/     # Controller khusus role Relawan
│   │   ├── Requests/        # Form Request classes (validasi)
│   │   └── Middleware/
│   ├── Listeners/           # Event listeners (poin, badge)
│   ├── Models/              # Eloquent models dengan relationships & scopes
│   ├── Policies/            # Laravel Policies (otorisasi per model)
│   ├── Providers/           # AppServiceProvider (event registration)
│   └── Services/
│       └── CloudinaryService.php  # Abstraksi upload foto cloud
├── database/
│   ├── migrations/          # Semua skema tabel dengan versi
│   └── seeders/             # Data demo realistis per entitas
├── resources/views/
│   ├── admin/               # Views khusus admin
│   ├── pemerintah/          # Views khusus pemerintah
│   ├── relawan/             # Views khusus relawan
│   ├── komunitas/           # Views leaderboard, event, profil
│   ├── laporan-anonim/      # Views pelaporan anonim
│   └── open-data/           # Dashboard open data publik
├── routes/
│   └── web.php              # Semua route dengan middleware role yang tepat
└── tests/
    └── Feature/             # Feature tests (32 tests, 81 assertions)
```

---

## 🧪 Menjalankan Test

```bash
# Jalankan semua test
php artisan test

# Hasil yang diharapkan:
# Tests: 32 passed (81 assertions)
# Duration: ~15s
```

---

## 📊 Alur Sistem

```
Warga Melapor
     │
     ▼
[PENDING] ──── Relawan Verifikasi ────► [VERIFIED]
     │                                      │
     └── Relawan Tolak ──► [REJECTED]       │
                                            ▼
                                     [IN_PROGRESS] ◄── Pemerintah Proses
                                            │
                                            ▼
                                       [RESOLVED] ──► +50 poin warga
                                                       +20 poin relawan

Setiap perubahan status → dicatat ke report_status_logs
Setiap poin → dicatat ke point_logs (via Event & Listener)
```

---

## 📹 Video Demo

> 🎥 Video demonstrasi penggunaan LaporHijau (durasi: 5-10 menit) dapat diakses di:
> 
> **[▶ Tonton di YouTube / Google Drive](#)** ← *(link akan diisi sebelum pengumpulan)*

Video mencakup demo:
1. Registrasi warga baru & submit laporan dengan foto dan lokasi
2. Login relawan → verifikasi laporan → poin diberikan otomatis
3. Login pemerintah → dashboard analitik → update status resolved
4. Fitur laporan anonim & tracking via kode unik
5. Open data dashboard → download CSV/Excel/GeoJSON
6. Sistem poin, badge, leaderboard, & penukaran hadiah/sertifikat
7. Tampilan responsif di mobile & dark mode

---

## 👤 Informasi Tim

| Kompetisi | NIFC 5.0 — Web Development |
|---|---|
| **Tema** | Empowering Communities Through Digital Solutions for Sustainability |
| **Kategori** | Lingkungan Hidup & Teknologi |

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan kompetisi akademik NIFC 5.0.  
Source code bersifat open-source di bawah lisensi **MIT**.

---

<div align="center">

**🌿 LaporHijau — Bersama Menjaga Kelestarian Alam Indonesia**

*"Satu laporan dapat mengubah lingkungan. Bersama, kita bisa mengubah Indonesia."*

</div>
