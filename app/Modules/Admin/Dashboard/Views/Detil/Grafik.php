<?= $this->extend('layouts/base') ?>

<?= $this->section('css') ?>
<style>
    .card{
        border-radius:8px;
        border:1px solid #cfcfcf;
    }

    .tab-pilihan{
        display:flex;
        gap:8px;
        margin-bottom:16px;
        flex-wrap:wrap;
    }

    .tab-pilihan .btn{
        border-radius:20px;
        font-size:13px;
        padding:6px 18px;
    }

    .tampilan-grafik{
        display:none;
    }

    .tampilan-grafik.active{
        display:block;
    }

    .chart-area{
        height:280px;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#777;
        font-size:15px;
    }

    .legend-list{
        margin-top:8px;
    }

    .legend-item{
        position:relative;
        display:flex;
        align-items:flex-start;
        gap:10px;
        padding:9px 10px;
        border-bottom:1px solid #eef0f3;
        font-size:13px;
        transition:background-color 0.15s ease;
        overflow:hidden;
        border-radius:4px;
    }

    .legend-item:hover{
        background-color:#f7f9fb;
    }

    .legend-fill{
        position:absolute;
        left:0;
        top:0;
        bottom:0;
        width:0%;
        z-index:0;
        opacity:0.20;
        transition:width 1.4s cubic-bezier(.22,1,.36,1);
        background-image:
            repeating-linear-gradient(
                100deg,
                rgba(255,255,255,0.35) 0px,
                rgba(255,255,255,0.35) 6px,
                transparent 6px,
                transparent 16px
            );
        background-size:32px 100%;
        animation:waterMove 2.2s linear infinite;
    }

    @keyframes waterMove{
        from{ background-position-x:0; }
        to{ background-position-x:-32px; }
    }

    .legend-item > *:not(.legend-fill){
        position:relative;
        z-index:1;
    }

    .legend-color{
        width:11px;
        height:11px;
        min-width:11px;
        border-radius:3px;
        margin-top:3px;
    }

    .legend-name{
        flex:1;
        line-height:1.35;
        color:#333;
    }

    .legend-percent{
        font-weight:600;
        color:#000;
        white-space:nowrap;
        padding-left:8px;
    }

    .small-text{
        font-size:14px;
        color:#666;
    }

    .line-legend{
        display:flex;
        gap:20px;
        flex-wrap:wrap;
        margin-top:12px;
        font-size:13px;
    }

    .line-legend-item{
        display:flex;
        align-items:center;
        gap:6px;
    }

    .line-legend-dash{
        width:18px;
        height:3px;
        border-radius:2px;
    }

    .placeholder-box{
        height:280px;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#888;
        font-size:14px;
        text-align:center;
        padding:0 30px;
    }
</style>
<?= $this->endSection() ?>

<?php
    #===================== DATA UNTUK TAB "PER BULAN" =====================
    // Sumber: get_target() -> pemisahan Ketetapan vs Target berdasarkan status_anggaran per bulan
    // dan get_realisasi() -> total realisasi per bulan (dijumlah dari semua jenis pajak)
    $namaBulan = [
        1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'Mei', 6=>'Jun',
        7=>'Jul', 8=>'Agu', 9=>'Sep', 10=>'Okt', 11=>'Nov', 12=>'Des'
    ];

    $labelsBulan = [];
    $seriKetetapan = [];
    $seriTarget = [];
    $seriRealisasi = [];

    foreach ($data['rekap']['judul'] as $judul) {
        $bulanKey = (int) $judul->bulan;
        $labelsBulan[] = $namaBulan[$bulanKey] ?? $judul->bulan;

        $sumKetetapan = 0;
        $sumTarget = 0;
        foreach ($data['rekap']['data'] as $val) {
            $angka = isset($val[$bulanKey]) ? (float) $val[$bulanKey] : 0;
            if ($judul->status_anggaran == 'penetapan') {
                $sumKetetapan += $angka;
            } else {
                $sumTarget += $angka;
            }
        }
        $seriKetetapan[] = $sumKetetapan;
        $seriTarget[] = $sumTarget;

        $sumRealisasi = 0;
        foreach ($realisasi['realisasi'] as $rlv) {
            $sumRealisasi += isset($rlv['detil'][$bulanKey]) ? (float) $rlv['detil'][$bulanKey] : 0;
        }
        $seriRealisasi[] = $sumRealisasi;
    }

    #===================== DATA UNTUK TAB "PER PAJAK" =====================
    $arrPerPajak = [];
    foreach($realisasi['target'] as $val){
        $arrPerPajak[$val['jenis_id']]['nama_pajak'] = $val['nama_pajak'];
    }
    foreach($realisasi['target'] as $val){
        $t = 0;
        for($i=1;$i<=12;$i++){ $t += (float) $val[$i]; }
        $arrPerPajak[$val['jenis_id']]['target'] = $t;
    }
    foreach($realisasi['realisasi'] as $val){
        $r = 0;
        for($i=1;$i<=12;$i++){ $r += isset($val['detil'][$i]) ? (float) $val['detil'][$i] : 0; }
        $arrPerPajak[$val['jenis_id']]['realisasi'] = $r;
    }
    foreach($arrPerPajak as $idx=>$val){
        $t = $val['target'] ?? 0;
        $r = $val['realisasi'] ?? 0;
        $arrPerPajak[$idx]['persen'] = $t != 0 ? ($r / $t) * 100 : 0;
    }
?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- ================= TOMBOL PILIHAN TAMPILAN ================= -->
    <div class="tab-pilihan">
        <button type="button" class="btn btn-success btn-tab active" data-target="tampilan-bulan">Per Bulan</button>
        <button type="button" class="btn btn-outline-success btn-tab" data-target="tampilan-pajak">Per Pajak</button>
        <button type="button" class="btn btn-outline-success btn-tab" data-target="tampilan-kecamatan">Per Kecamatan</button>
    </div>

    <!-- ================= TAMPILAN: PER BULAN (default) ================= -->
    <div class="tampilan-grafik active" id="tampilan-bulan">
        <div class="row">
            <div class="col-lg-12">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">Ketetapan, Target &amp; Realisasi per Bulan</h5>
                        <div class="small-text mb-3">Total seluruh jenis pajak, dibandingkan per bulan</div>
                        <canvas id="lineChartKTR" height="90"></canvas>
                        <div class="line-legend">
                            <div class="line-legend-item"><span class="line-legend-dash" style="background:#0d6efd"></span> Ketetapan</div>
                            <div class="line-legend-item"><span class="line-legend-dash" style="background:#f0b565"></span> Target</div>
                            <div class="line-legend-item"><span class="line-legend-dash" style="background:#34c3b4"></span> Realisasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= TAMPILAN: PER PAJAK ================= -->
    <div class="tampilan-grafik" id="tampilan-pajak">
        <div class="row">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">Persentase Realisasi per Jenis Pajak</h5>
                        <div class="small-text mb-3">Realisasi dibanding target, per jenis pajak</div>
                        <div style="height:420px;">
                            <canvas id="barChartPajak"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="legend-list">
                        <?php
                            $colors = ['#23235c','#f0b565','#0d6b2f','#7757ff','#34c3b4','#a279ff','#ff9945','#d9534f','#84d4a0','#ff66cc','#d63384'];
                            $no = 0;
                            foreach($arrPerPajak as $val){
                                $persen = $val['persen'] ?? 0;
                                $warna  = $colors[$no % count($colors)];
                        ?>
                            <div class="legend-item" data-percent="<?= min($persen,100) ?>">
                                <div class="legend-fill" style="background-color:<?=$warna?>;"></div>
                                <span class="legend-color" style="background:<?=$warna?>"></span>
                                <span class="legend-name"><?=$val['nama_pajak']?></span>
                                <span class="legend-percent"><?=number_format($persen,1)?>%</span>
                            </div>
                        <?php $no++; } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= TAMPILAN: PER KECAMATAN ================= -->
    <div class="tampilan-grafik" id="tampilan-kecamatan">
        <div class="row">
            <div class="col-lg-12">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">Realisasi per Kecamatan</h5>
                        <div class="small-text mb-3">Total realisasi dikelompokkan per kecamatan</div>
                        <div class="placeholder-box">
                            Data realisasi per kecamatan belum punya sumber data di aplikasi ini.<br>
                            Grafik ini akan diisi begitu sumber datanya tersedia.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('js-script') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===== Switch tab =====
    document.querySelectorAll('.btn-tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.btn-tab').forEach(function (b) {
                b.classList.remove('active','btn-success');
                b.classList.add('btn-outline-success');
            });
            this.classList.add('active','btn-success');
            this.classList.remove('btn-outline-success');

            document.querySelectorAll('.tampilan-grafik').forEach(function (el) {
                el.classList.remove('active');
            });
            document.getElementById(this.dataset.target).classList.add('active');
        });
    });

    // ===== Animasi water-fill legend =====
    document.querySelectorAll('.legend-item[data-percent]').forEach(function (item, idx) {
        const percent = parseFloat(item.getAttribute('data-percent')) || 0;
        const fillEl = item.querySelector('.legend-fill');
        if (fillEl) {
            setTimeout(function () {
                fillEl.style.width = percent + '%';
            }, 150 + (idx * 60));
        }
    });

    // ===== Line Chart: Per Bulan =====
    const ctxBulan = document.getElementById('lineChartKTR');
    if (ctxBulan) {
        const canvasCtx = ctxBulan.getContext('2d');
        function makeGradient(color) {
            const gradient = canvasCtx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, color + '55');
            gradient.addColorStop(1, color + '00');
            return gradient;
        }

        new Chart(ctxBulan, {
            type: 'line',
            data: {
                labels: <?= json_encode($labelsBulan) ?>,
                datasets: [
                    {
                        label: 'Ketetapan',
                        data: <?= json_encode($seriKetetapan) ?>,
                        borderColor: '#0d6efd',
                        backgroundColor: makeGradient('#0d6efd'),
                        borderWidth: 2, tension: 0.4, fill: true,
                        pointRadius: 0, pointHoverRadius: 5, pointHitRadius: 20
                    },
                    {
                        label: 'Target',
                        data: <?= json_encode($seriTarget) ?>,
                        borderColor: '#f0b565',
                        backgroundColor: makeGradient('#f0b565'),
                        borderWidth: 2, tension: 0.4, fill: true,
                        pointRadius: 0, pointHoverRadius: 5, pointHitRadius: 20
                    },
                    {
                        label: 'Realisasi',
                        data: <?= json_encode($seriRealisasi) ?>,
                        borderColor: '#34c3b4',
                        backgroundColor: makeGradient('#34c3b4'),
                        borderWidth: 2, tension: 0.4, fill: true,
                        pointRadius: 0, pointHoverRadius: 5, pointHitRadius: 20
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e222d',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function (item) {
                                return item.dataset.label + ': Rp ' + item.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // ===== Bar Chart: Per Pajak =====
    const ctxPajak = document.getElementById('barChartPajak');
    if (ctxPajak) {
        new Chart(ctxPajak, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($arrPerPajak, 'nama_pajak')) ?>,
                datasets: [{
                    label: 'Persentase Realisasi (%)',
                    data: <?= json_encode(array_column($arrPerPajak, 'persen')) ?>,
                    backgroundColor: '#288052',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { callback: function (v) { return v + '%'; } }
                    }
                }
            }
        });
    }

});
</script>
<?= $this->endSection() ?>