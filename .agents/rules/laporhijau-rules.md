---
trigger: always_on
---

# LaporHijau — Agent Rules

## Project
Platform pelaporan masalah lingkungan. Laravel 11 + PHP 8.2 + MySQL 8 + Blade + Alpine.js + Tailwind CSS v4. Deploy ke Railway.

## Package Wajib
- Spatie Laravel-Permission (role system)
- Laravel Breeze (auth, blade)
- Cloudinary (upload foto)
- Laravel Reverb (real-time)
- Leaflet.js (peta), Chart.js (dashboard)

## 4 Role (Spatie)
masyarakat · relawan · pemerintah · admin

## Status Laporan
pending → verified → in_progress → resolved
pending → rejected (oleh relawan, wajib isi alasan)
Setiap perubahan status: catat ke tabel report_status_logs.

## Poin Otomatis (via Event & Listener, BUKAN hardcode di controller)
+5 submit laporan · +10 laporan diverifikasi · +50 laporan resolved
+20 relawan verifikasi · +15 ikut event · +5 komentar

## Konvensi Kode WAJIB
- Controller: Resource Controller, pisah per role (misal RelawanVerificationController)
- Validasi: selalu pakai Form Request class (bukan validate() langsung di controller)
- Authorization: pakai Policy Laravel per model
- Poin & badge: trigger via Laravel Event & Listener
- Email & notifikasi: kirim via Queue Job (tidak boleh blocking)
- Blade component: simpan di resources/views/components/
- Naming: snake_case kolom DB · camelCase method PHP · PascalCase class

## Routing
/ landing · /laporan CRUD · /peta · /komunitas · /artikel
/dashboard → redirect sesuai role
/relawan/* · /pemerintah/* · /admin/* → group route per role dengan middleware

## Design System
Font: Plus Jakarta Sans
Primary: #16a34a · Secondary: #0ea5e9 · Accent: #f59e0b · Danger: #ef4444
Border radius: 12px card · 8px button · 20px pill

## Hal yang TIDAK boleh dilakukan agent
- Jangan hardcode logika poin di controller
- Jangan skip Form Request, langsung validate() di controller
- Jangan buat route tanpa middleware role yang sesuai
- Jangan simpan file foto di storage lokal (wajib Cloudinary)
- Jangan push ke branch main langsung (selalu ke dev atau feature branch)
