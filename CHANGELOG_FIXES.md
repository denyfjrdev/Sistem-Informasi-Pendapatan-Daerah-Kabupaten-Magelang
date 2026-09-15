# Changelog Fixes

### Fix #1 — Nested `public_html` on git checkout: files landed in `public_html/public_html`

| | |
|---|---|
| Tanggal | 2026-09-08 |
| File | seluruh tree (`git mv public_html/*` → root) |
| Masalah | Upload ke `origin/dev.v2` menaruh project di folder `public_html/`. `pull.sh` server sudah `--work-tree=/home/sijaka/public_html`, jadi checkout jadi `/home/sijaka/public_html/public_html/...`. |
| Akar | Commit `6800880` memindahkan `app/`, `public/`, `vendor/`, `writable/` ke prefix `public_html/` di git tree. |
| Fix | Pindahkan seluruh isi `public_html/` ke root repo (7675 rename, 100%). `.env` ikut dipindah dan tetap gitignored. Folder `public_html/` di repo dihapus. |
| Verifikasi | `git ls-tree HEAD` menampilkan `app`, `public`, `vendor`, `writable` di root. `php spark --version` jalan. |
| Pelajaran | Work-tree server sudah `public_html`; jangan mengulang prefix itu di dalam git tree. |
| Log Keyword | public_html, nested, pull.sh, dev.v2, 6800880 |
| Deploy | BELUM DEPLOY server; commit `5a50adb` + `8f9a68b` di `dev.v2` |
