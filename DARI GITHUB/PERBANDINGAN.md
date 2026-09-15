# Perbandingan sijaka_dev2 (lokal) vs DARI GITHUB

Sumber: isi folder project `sijaka_dev2` (kecuali `DARI GITHUB/`) dibanding salinan GitHub di `sijaka_dev2/DARI GITHUB/`.

Kesimpulan singkat: **GitHub bukan versi yang lebih lengkap.** Lokal justru lebih maju. GitHub menambah terutama **struktur menu Detail** (beberapa halaman masih mockup).

---

## 1. Angka kasar

| | Lokal | GitHub |
|---|---|---|
| File (tanpa vendor/writable) | lebih banyak | ~3.688 |
| Hanya di GitHub | — | **15 file** |
| Hanya di lokal | **ratusan** (modul Pimpinan, BPHTB, helper, dll.) | — |
| File sama path, isi beda nyata | — | ~20 file app (bukan Config) |
| File Config “beda” tapi hanya line ending (CRLF/LF) | — | hampir semua `app/Config/*` |

`composer.json`, `package.json`, `README.md`, `spark` isinya sama (hanya beda line ending).

---

## 2. Yang GitHub nambah (tidak ada di lokal)

### 2.1 Detail dipecah jadi banyak halaman — inti perbedaan

Lokal: satu menu **Detail** → `admin/detil`.

GitHub nambah route di `app/Modules/Admin/Dashboard/Config/Routes.php`:

| Route | File | Status |
|---|---|---|
| `/admin/detil/ketetapan` | `Dashboard/Views/Detil/Ketetapan.php` | Placeholder — kolom masih `"..."` |
| `/admin/detil/target` | `Dashboard/Views/Detil/Target.php` | Ada angka target per jenis pajak |
| `/admin/detil/grafik` | `Dashboard/Views/Detil/Grafik.php` | **Paling jadi** — tab Per Bulan / Per Pajak, data dari model |
| `/admin/detil/realisasi/kecamatan` | `Dashboard/Views/Detil/Realisasi/PerKecamatan.php` | UI lengkap, **API masih TODO** |
| `/admin/detil/realisasi/desa` | `Dashboard/Views/Detil/Realisasi/PerDesa.php` | UI lengkap, **API masih TODO** |
| `/admin/informasi` | `Dashboard/Views/Informasi/index.php` | Placeholder “konten belum tersedia” |

Controller baru:

- `app/Modules/Admin/Dashboard/Controllers/Detil.php`
- `app/Modules/Admin/Dashboard/Controllers/Informasi.php`

Catatan: lokal sudah punya Informasi sebagai modul terpisah (`app/Modules/Admin/Informasi/`) dengan isi yang sama (placeholder). GitHub hanya memindahkannya ke dalam folder Dashboard.

### 2.2 Menu dropdown

Lokal (`app/Views/layouts/menu_admin.php`): tombol datar Beranda / Input Target / Detail / Informasi.

GitHub:

- **Data → Target**
- **Detail → Ketetapan / Target / Realisasi (Per Kecamatan, Per Desa) / Grafik**
- **Informasi**

`menu_pimpinan.php` GitHub ikut pola dropdown itu, tapi **link tetap ke `/admin/...`**. Modul `Pimpinan/` tidak ada di GitHub.

### 2.3 Halaman Grafik (paling worth diambil)

`Dashboard/Views/Detil/Grafik.php` sudah hitung seri dari `get_target()` / `get_realisasi()`:

- ketetapan vs target (dari `status_anggaran` penetapan vs revisi)
- realisasi
- tab per bulan dan per pajak

Di lokal, grafik lebih ke Beranda (`part_grafik`, YoY, top kecamatan), bukan submenu Detail.

### 2.4 Model duplikat Ketetapan di folder Target

`app/Modules/Admin/Target/Models/KetetapanModel.php` — akses tabel `ketetapan`.

Tidak dipakai `TargetController`. Modul `Admin/Ketetapan` sudah ada di **kedua** sisi. Ini duplikat, bukan fitur hidup.

### 2.5 jQuery terpisah

`public/skote/assets/libs/jquery/jquery-3.7.1.min.js`

Lokal memakai `jquery.min.js`. Layout GitHub load `jquery-3.7.1.min.js` di head, lalu `jquery.min.js` lagi di bawah — dobel.

### 2.6 Sisa project lama (bukan fitur SIJAKA)

- `Dashboard/Views/Dashboard/xxx_dashboard.js` — permohonan, revisi ujilab
- `Dashboard/Views/Dashboard/xxx_part_target.php` — tabel target setengah jadi
- route transaksi/ujilab yang di-comment di `Dashboard/Config/Routes.php`

### 2.7 Daftar 15 file yang hanya ada di GitHub

```
app/Modules/Admin/Dashboard/Controllers/Detil.php
app/Modules/Admin/Dashboard/Controllers/Informasi.php
app/Modules/Admin/Dashboard/Views/Dashboard/index.php
app/Modules/Admin/Dashboard/Views/Dashboard/part_bulanan.php
app/Modules/Admin/Dashboard/Views/Dashboard/part_total.php
app/Modules/Admin/Dashboard/Views/Dashboard/xxx_dashboard.js
app/Modules/Admin/Dashboard/Views/Dashboard/xxx_part_target.php
app/Modules/Admin/Dashboard/Views/Detil/Grafik.php
app/Modules/Admin/Dashboard/Views/Detil/Ketetapan.php
app/Modules/Admin/Dashboard/Views/Detil/Realisasi/PerDesa.php
app/Modules/Admin/Dashboard/Views/Detil/Realisasi/PerKecamatan.php
app/Modules/Admin/Dashboard/Views/Detil/Target.php
app/Modules/Admin/Dashboard/Views/Informasi/index.php
app/Modules/Admin/Target/Models/KetetapanModel.php
public/skote/assets/libs/jquery/jquery-3.7.1.min.js
```

View Dashboard GitHub cuma pindah path (`Views/Dashboard/...`). Lokal sudah punya padanannya di `Views/index.php`, `part_bulanan.php`, `part_total.php`.

---

## 3. File yang ada di keduanya tapi isi berbeda (nyata)

Bukan line ending. Fitur/kode memang beda.

| File | Lokal | GitHub |
|---|---|---|
| `app/Config/App.php` | `baseURL` dinamis (localhost + Cloudflare Tunnel) | `baseURL` kosong |
| `app/Helpers/campuran_helper.php` | + `set_parent_url_session`, `layout_user_profile` (SSO/iframe) | helper tanggal + datatables saja |
| `app/Helpers/security_helper.php` | sedikit lebih lengkap | versi lebih pendek |
| `Admin/Dashboard/Controllers/Dashboard.php` | + YoY, top 5 kecamatan | realisasi + last update saja |
| `Admin/Dashboard/Models/DashboardModel.php` | + `get_realisasi_yoy`, `get_top_kecamatan` | `get_target`, `get_realisasi`, `get_last_update` |
| `Admin/Dashboard/Config/Routes.php` | beranda saja | + route Detail pecah-pecah + Informasi |
| `Admin/Detil/Controllers/DetilController.php` | grouping PBB/Opsen/STPD, per kecamatan/desa | halaman all-in-one, lebih sederhana |
| `Admin/Detil/Models/DetilModel.php` | + `getRealisasiPerKecamatan`, `getRealisasiPerDesa` | kecamatan/desa/jenis/realisasi/total |
| `Admin/Detil/Views/index.php` | ~361 baris, terpecah ke `card_*` | ~1235 baris, 1 halaman + Chart.js + Select2 |
| `Admin/Target/Config/Routes.php` | CRUD target | sama + extra `admin.default` |
| `Admin/Target/Controllers/TargetController.php` | hampir sama | hampir sama |
| `Auth/.../v_login.php` | judul SIJAKA + favicon Magelang | judul “Login \| Skote”, tanpa favicon |
| `Views/layouts/base.php` | motif header, top-nav custom, favicon | layout Skote sederhana |
| `Views/layouts/header.php` | disesuaikan layout lokal | versi GitHub |
| `Views/layouts/menu_admin.php` | 4 tombol datar | dropdown Data/Detail/Informasi |
| `Views/layouts/menu_pimpinan.php` | link `/pimpinan/...` | dropdown tapi link `/admin/...` |
| `Views/layouts/menu_master.php` | top-nav button | dropdown Setting → Users |
| `Views/layouts/sidebar.php` | sedikit lebih lengkap | lebih pendek |
| `.gitignore` | beda line ending/byte | beda line ending/byte |

---

## 4. Yang hanya ada di lokal (GitHub tidak punya)

### Modul

- **`app/Modules/Pimpinan/`** — Dashboard, Detil, Target, Informasi (role pimpinan utuh)
- **`app/Modules/Api/Bphtb/`** — API BPHTB
- **`app/Modules/Admin/Informasi/`** — modul terpisah (GitHub nested di Dashboard)
- **`app/Modules/Master/`** ekstra — Dashboard, Setting tahapan, Users (view/js)
- **`app/Modules/Sample/`**
- **`app/Modules/Api/xxx_*`** — sisa MSS/transaksi (juga tidak di GitHub)

### Dashboard / Detil lokal

- `Dashboard/Views/part_cards.php`
- `Dashboard/Views/part_grafik.php`
- `Dashboard/Views/part_tabel.php`
- `Detil/Views/card_chart_bulan.php`
- `Detil/Views/card_summary.php`

### Lainnya

- Migrasi: `AddKodeKecamatanToTargetRealisasi`, `realisasi_piutang`
- Helper `xxx_*` (data, elemen, foto, group, sys, tte, verif, wa)
- `app/public/form/`, `script/`, `uploads/`, `lte/`
- Root: `motif-header.svg`, `semua_kecamatan.json`, `realisasi.php`, script Cloudflare tunnel, `vendor/`

---

## 5. Struktur modul Admin

```
LOKAL                              GITHUB
Admin/                             Admin/
  Dashboard/                         Dashboard/
    Controllers/Dashboard.php          Controllers/Dashboard.php
                                       Controllers/Detil.php          ← baru
                                       Controllers/Informasi.php      ← baru
    Views/index.php                    Views/Dashboard/index.php      ← path beda
    Views/part_*                       Views/Dashboard/part_*
    Views/part_cards, grafik, tabel    Views/Detil/*                  ← baru
  Detil/                             Detil/   (masih ada, 1 halaman gemuk)
  Informasi/  ← modul sendiri        (tidak ada; pindah ke Dashboard)
  Ketetapan/                         Ketetapan/
  Target/                            Target/
                                       Models/KetetapanModel.php      ← duplikat
```

---

## 6. Kalau mau ambil dari GitHub ke lokal

Worth diambil:

1. **Halaman Grafik Detail** (`Detil/Grafik.php` + route + menu)
2. **Pola submenu Realisasi** (kecamatan/desa) — UI-nya, bukan JS TODO-nya; backend grouping sudah ada di lokal

Jangan timpa:

- `Pimpinan/`
- `Api/Bphtb/`
- helper SSO (`campuran_helper.php`)
- `Config/App.php` (tunnel)
- Detil lokal yang sudah grouping wilayah PBB/Opsen/STPD
- layout/header/favicon Magelang

Tidak perlu diambil:

- `xxx_dashboard.js`, `xxx_part_target.php`
- `KetetapanModel` di folder Target (duplikat)
- jquery-3.7.1.min.js (sudah ada jquery.min.js)
- halaman Ketetapan Detail yang masih `"..."`
- Realisasi kecamatan/desa GitHub selama masih `URL_* = null`
