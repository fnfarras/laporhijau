# LaporHijau — Knowledge Item (Antigravity)

Simpan file ini ke Knowledge Base Antigravity di awal setup workspace.
Dengan ini, agent selalu paham konteks project tanpa perlu re-explain tiap sesi baru.

---

## Identitas Project

**Nama:** LaporHijau
**Konsep:** Platform civic tech berbasis web untuk pelaporan, pemantauan, dan penanganan masalah lingkungan hidup secara kolaboratif di Indonesia. Masyarakat melapor, relawan memverifikasi di lapangan, pemerintah/lembaga merespons dan menyelesaikan.
**Konteks lomba:** NIFC 5.0, tingkat nasional, tema "Empowering Communities Through Digital Solutions for Sustainability"

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 11, PHP 8.2 |
| Database | MySQL 8 |
| Frontend | Blade + Alpine.js, Tailwind CSS v4, Vite |
| Maps | Leaflet.js (marker, heatmap, cluster) |
| Charts | Chart.js |
| Auth & Role | Spatie Laravel-Permission |
| Real-time | Laravel Reverb |
| Queue | Laravel Queue + Jobs |
| Storage | Cloudinary (foto laporan) |
| Deployment | Railway atau VPS |

---

## Struktur Database

| Tabel | Kolom Utama |
|---|---|
| users | id, name, email, points |
| reports | id, user_id, category_id, volunteer_id (nullable), title, description, latitude, longitude, address, status |
| report_categories | id, name, icon |
| report_photos | id, report_id, photo_url |
| report_status_logs | id, report_id, old_status, new_status, changed_by, notes |
| comments | id, report_id, user_id, content, parent_id (nullable) |
| votes | id, report_id, user_id |
| badges | id, name, icon, criteria_type, criteria_value |
| user_badges | id, user_id, badge_id, earned_at |
| point_logs | id, user_id, points, reason, reference_id |
| events | id, organizer_id, report_id (nullable), title, description, event_date, location |
| event_participants | id, event_id, user_id, status |
| articles | id, author_id, category, title, slug, content, published_at |
| notifications | id, user_id, type, data (JSON), read_at |

---

## 4 Role Pengguna (Spatie Permission)

1. **masyarakat** — submit laporan, komentar, upvote, RSVP event, lihat peta & leaderboard
2. **relawan** — semua fitur masyarakat + verifikasi/tolak laporan, buat event, upload bukti lapangan
3. **pemerintah** — dashboard analitik, update status ke in_progress & resolved, assign relawan, export laporan
4. **admin** — full access: kelola user, role, kategori, badge, artikel

---

## Status Workflow Laporan (reports.status)

```
pending  →  verified   (relawan approve)
pending  →  rejected   (relawan tolak, wajib isi alasan)
verified →  in_progress  (pemerintah mulai tangani)
in_progress → resolved   (pemerintah selesaikan)
```

Setiap perubahan status WAJIB dicatat ke tabel report_status_logs.

---

## Gamifikasi — Trigger Poin Otomatis

Implementasikan via Laravel Event & Listener agar tidak hardcode di controller.

| Aksi | Poin | Siapa |
|---|---|---|
| Submit laporan baru | +5 | Reporter (masyarakat) |
| Laporan diverifikasi | +10 | Reporter |
| Laporan resolved | +50 | Reporter |
| Verifikasi laporan | +20 | Relawan |
| Ikut event komunitas | +15 | Semua |
| Komentar aktif | +5 | Semua |

Cek badge otomatis setiap kali poin user berubah via Observer atau Listener.

---

## Konvensi Kode

- **Controller:** Resource Controller, pisah per role jika scope berbeda (contoh: `RelawanVerificationController`)
- **Authorization:** Policy Laravel untuk tiap model (`ReportPolicy`, `EventPolicy`, dll)
- **Validasi:** Form Request class (`StoreReportRequest`, `UpdateReportStatusRequest`, dll)
- **Poin & Badge:** Trigger via Event & Listener (`ReportVerifiedEvent`, `ReportResolvedEvent`)
- **Notifikasi & Email:** Queue Job agar tidak memblokir request
- **Blade:** Component reusable di `resources/views/components/`
- **Naming:** snake_case database, camelCase PHP method, PascalCase class

---

## Routing Convention

```
/                   → landing page (publik)
/laporan            → daftar laporan (publik, read-only)
/laporan/create     → form buat laporan (masyarakat)
/laporan/{id}       → detail laporan (publik)
/peta               → peta interaktif (publik)
/komunitas          → leaderboard + event
/artikel            → artikel edukasi
/dashboard          → redirect ke dashboard sesuai role
/relawan/*          → fitur khusus relawan
/pemerintah/*       → fitur khusus pemerintah
/admin/*            → panel admin
/profil/{user}      → profil publik user
```

---

## Design System

- **Primary:** `#16a34a` (green-600) — CTA, button utama, badge aktif
- **Secondary:** `#0ea5e9` (sky-500) — link, info, marker peta
- **Accent:** `#f59e0b` (amber-500) — poin, highlight, status pending
- **Danger:** `#ef4444` (red-500) — rejected, hapus, error
- **Neutral:** Zinc gray scale
- **Font:** Plus Jakarta Sans (semua teks)
- **Border radius:** 12px card, 8px button, 20px pill/badge
- **Shadow:** Minimal, hanya card dengan elevasi penting
