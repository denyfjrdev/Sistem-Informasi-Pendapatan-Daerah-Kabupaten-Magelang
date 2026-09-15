<style>
    .kpi-card{
      border:2px solid #28a745;
    }

    .kpi-card .card-title,
    .kpi-card .card-bulan{
      font-size:14px;
      font-weight:700;
      text-transform:uppercase;
      color:#333;
      margin-bottom:8px;
    }

    .kpi-card-list .card-title{
      font-size:13px;
      font-weight:700;
      line-height:1.35;
      text-transform:none;
      margin-bottom:8px;
    }

    .kpi-card .badge-row{
      display:flex;
      align-items:center;
      flex-wrap:wrap;
      gap:6px 8px;
    }

    .kpi-card .badge-progress{
      display:inline-block;
      width:fit-content;
      padding:2px 8px;
      white-space:nowrap;
      line-height:1.3;
    }

    .kpi-card .badge-vs{
      font-size:12px;
      font-weight:400;
      color:#666;
      white-space:nowrap;
    }

    .kpi-card-list{
      min-width:0;
      overflow:hidden;
    }
    .top-kec-list{
      list-style:none;
      padding:0;
      margin:0;
      width:100%;
    }
    .top-kec-list li{
      display:flex;
      align-items:baseline;
      gap:6px;
      min-width:0;
      font-size:13px;
      line-height:1.3;
      padding:1px 0;
    }
    .top-kec-nama{
      flex:1;
      min-width:0;
      font-weight:600;
      color:#333;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }
    .top-kec-nilai{
      font-weight:700;
      color:#006400;
      white-space:nowrap;
    }

    .periode-strip{
      border-radius:10px;
      border:1px solid #dcdcdc;
      background:#fff;
      padding:12px 18px;
      display:flex;
      flex-wrap:wrap;
      align-items:center;
      gap:12px 20px;
      width:100%;
    }
    .periode-strip .strip-label{
      font-size:13px;
      font-weight:700;
      text-transform:uppercase;
      color:#333;
      white-space:nowrap;
    }
    .periode-strip .strip-hari{
      font-size:16px;
      font-weight:700;
      color:#28a745;
      line-height:1;
      white-space:nowrap;
    }
    .periode-strip .strip-hari-label{
      font-size:14px;
      font-weight:700;
      
      white-space:nowrap;
    }
    .periode-strip .strip-meta{
      font-size:12px;
      font-weight:400;
      color:#666 !important;
      white-space:nowrap;
    }
    .periode-strip .strip-divider{
      width:1px;
      height:28px;
      background:#dcdcdc;
    }

    .marquee-wrap{
      overflow:hidden;
      flex:1 1 260px;
      min-width:200px;
    }
    .marquee-track{
      display:inline-block;
      white-space:nowrap;
      padding-left:100%;
      animation:marqueeKananKiri 18s linear infinite;
      font-size:13px;
      color:#333;
    }
    .marquee-track span{
      font-weight:700;
      color:#138f55;
    }
    @keyframes marqueeKananKiri{
      0%{ transform:translateX(0); }
      100%{ transform:translateX(-100%); }
    }
</style>

<?php
  $namaBulanID = [
      1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
      5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
      9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
  ];
  $bulanAuto = $namaBulanID[(int) date('n')];
?>

<div class="row g-3">

    <!--- CARD 1: REALISASI SAAT INI --->
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card kpi-card h-100">
            <div class="card-title">
                REALISASI SAAT INI
            </div>
            <div class="card-bulan">
                BULAN <?= strtoupper($bulanAuto) ?>
            </div>
            <div class="nominal" id="semuaRealisasiBerjalan" style="font-size:22px;">
                Rp. <?= number_format(($semua_realisasi_tahun ?? 0) / 1000000000, 2, ',', '.') ?> M
            </div>
            <div class="badge-row">
                <span class="badge-progress" id="semuaPersenBerjalan">
                    +<?= number_format($total_persentase_tahun ?? 0, 1, ',', '.') ?>%
                </span>
                <span class="badge-vs">vs Target Tahun Ini</span>
            </div>
        </div>
    </div>

    <!--- CARD 2: REALISASI + PIUTANG (placeholder Rp 0) — pending dulu --->
    <?php /*
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card kpi-card h-100">
            <div class="card-title">
                REALISASI + PIUTANG SAAT INI
            </div>
            <div class="card-bulan">
                BULAN <?= strtoupper($bulanAuto) ?>
            </div>
            <div class="nominal" style="font-size:22px;" data-field="realisasi_piutang">
                Rp 0
            </div>
            <div class="badge-row">
                <span class="badge-progress">+0%</span>
                <span class="badge-vs">vs Target Tahun Ini</span>
            </div>
        </div>
    </div>
    */ ?>

    <!--- CARD 2: 5 KECAMATAN TERTINGGI (semua pajak) --->
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card kpi-card kpi-card-list h-100">
            <div class="card-title">5 Kecamatan Realisasi Pajak Tertinggi</div>
            <?php if (!empty($top_kecamatan)): ?>
            <ol class="top-kec-list">
              <?php $rank = 1; foreach ($top_kecamatan as $kec): ?>
                <li>
                  <span class="top-kec-nama"><?= $rank++ ?>. <?= esc($kec['nama_kecamatan'] ?? '-') ?></span>
                  <span class="top-kec-nilai"><?= number_format(((float) ($kec['total_realisasi'] ?? 0)) / 1000000000, 2, ',', '.') ?> M</span>
                </li>
              <?php endforeach; ?>
            </ol>
            <?php else: ?>
              <div class="subtitle">Belum ada data kecamatan</div>
            <?php endif; ?>
        </div>
    </div>

    <!--- CARD 3: TARGET PERIODE SAAT INI --->
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card kpi-card h-100">
            <div class="card-title">
                TARGET PERIODE SAAT INI
            </div>
            <div class="nominal" style="color:#333;">
                Rp. <?= number_format($semua_target_berjalan / 1000000000, 2, ',', '.') ?> M
            </div>
            <span class="subtitle">
                Sampai bulan berjalan
            </span>
        </div>
    </div>

    <!--- CARD 4: PROGRES REALISASI vs TARGET PENUH --->
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card kpi-card h-100">
            <div class="card-title">
                PROGRES REALISASI
            </div>

            <div class="progress mb-2">
              <div class="progress-bar"
                  role="progressbar"
                  style="width:<?= $bar_width ?>%"
                  aria-valuenow="<?= $bar_width ?>"
                  aria-valuemin="0"
                  aria-valuemax="100">
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div class="text-small" id="persenRealisasiTahun">
                    <strong><?= number_format($total_persentase_tahun, 1, ',', '.') ?>%</strong>
                </div>
                <div class="text-small text-secondary" id="semuaTargetTahun">
                    <strong>Target <?= date('Y') ?>: Rp. <?= number_format($semua_target_tahun / 1000000000, 2, ',', '.') ?> M</strong>
                </div>
            </div>
        </div>
    </div>

</div>

<!--- STRIP: PERIODE BERAKHIR + TERAKHIR DIPERBARUI --->
<div class="row mt-3">
  <div class="col-12">
    <div class="periode-strip">

      <span class="strip-label">Periode berakhir dalam</span>
      <span class="strip-hari" id="periode-hari">-</span>
      <span class="strip-hari-label">Hari</span>
      <span class="strip-meta" id="periode-tanggal">-</span>

      <span class="strip-divider d-none d-md-inline-block"></span>

      <div class="marquee-wrap">
        <div class="marquee-track">
          Terakhir diperbarui :
          &nbsp;<span>PBB</span> <?= isset($config->last_update_pbb) ? tanggal_angka($config->last_update_pbb, true) : '13-08-2026 08:00' ?>
          &nbsp;&nbsp;|&nbsp;&nbsp;<span>OPSEN</span> <?= isset($config->last_update_opsen) ? tanggal_angka($config->last_update_opsen, true) : '13-08-2026 08:05' ?>
          &nbsp;&nbsp;|&nbsp;&nbsp;<span>E-SPTPD</span> <?= !empty($config->last_update_esptpd) ? tanggal_angka($config->last_update_esptpd, true) : '-' ?>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
    const namaBulanID = [
        "Januari","Februari","Maret","April","Mei","Juni",
        "Juli","Agustus","September","Oktober","November","Desember"
    ];

    function getPeriodeEnd() {
        const now = new Date();
        return new Date(now.getFullYear(), 11, 31, 23, 59, 59);
    }

    function updatePeriodeCountdown() {
        const now = new Date();
        const end = getPeriodeEnd();
        const diffMs = end - now;

        const hariEl = document.getElementById('periode-hari');
        const tanggalEl = document.getElementById('periode-tanggal');

        if (!hariEl || !tanggalEl) return;

        if (diffMs <= 0) {
            hariEl.textContent = "0";
        } else {
            const totalDetik = Math.floor(diffMs / 1000);
            const hari = Math.floor(totalDetik / 86400);
            hariEl.textContent = hari;
        }

        const tanggalStr = now.getDate() + " " + namaBulanID[now.getMonth()] + " " + now.getFullYear();
        const jam = String(now.getHours()).padStart(2, '0');
        const menit = String(now.getMinutes()).padStart(2, '0');
        const detik = String(now.getSeconds()).padStart(2, '0');
        tanggalEl.textContent = tanggalStr + " " + jam + ":" + menit + ":" + detik;
    }

    updatePeriodeCountdown();
    setInterval(updatePeriodeCountdown, 1000);
</script>
