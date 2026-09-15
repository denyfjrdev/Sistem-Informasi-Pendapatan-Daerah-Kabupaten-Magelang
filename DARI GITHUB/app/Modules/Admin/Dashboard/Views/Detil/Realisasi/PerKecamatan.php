<?= $this->extend('layouts/base') ?>

<?= $this->section('css') ?>
<style>
    .card{
        border-radius:8px;
        border:1px solid #cfcfcf;
    }

    
    .card-body{
        padding:16px;
    }

    .filter-label{
        font-size:13px;
        font-weight:600;
        color:#444;
        margin-bottom:6px;
    }

    .form-select, .form-control{
        font-size:14px;
    }

    .btn-reset-filter{
        font-size:13px;
    }

    .summary-title{
        font-size:13px;
        font-weight:700;
        text-transform:uppercase;
        color:#333;
        margin-bottom:8px;
    }

    .summary-nominal{
        font-size:24px;
        font-weight:700;
        color:#006400;
    }

    .summary-sub{
        font-size:13px;
        color:#666;
    }

    .table{
        margin-bottom:0;
        font-size:14px;
    }

    .table thead{
        background:#2e8555;
        color:#fff;
    }

    .table thead th{
        border:none;
        padding:8px;
        font-weight:600;
        white-space:nowrap;
    }

    .table tbody td{
        padding:7px 8px;
        vertical-align:middle;
    }

    .table tfoot td{
        font-weight:bold;
    }

    .progress{
        height:10px;
        border-radius:10px;
        background:#e8edf3;
        min-width:80px;
    }

    .progress-bar{
        background:#0d6efd;
    }

    .table-loading, .table-empty{
        text-align:center;
        padding:30px 10px;
        color:#777;
        font-size:14px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">

<div class="row g-3">

    <!-- ================= CARD RINGKASAN REALISASI GLOBAL ================= -->

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 border-end">
                        <div class="summary-title">Total Realisasi</div>
                        <div class="summary-nominal" id="sumTotalRealisasi">Rp —</div>
                        <div class="summary-sub">Sesuai filter yang dipilih</div>
                    </div>
                    <div class="col-md-4 border-end">
                        <div class="summary-title">Total Target</div>
                        <div class="summary-nominal" id="sumTotalTarget">Rp —</div>
                        <div class="summary-sub">Sesuai filter yang dipilih</div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-title">Persentase Capaian</div>
                        <div class="summary-nominal" id="sumPersen">— %</div>
                        <div class="progress mt-2">
                            <div class="progress-bar" id="sumProgressBar" style="width:0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= CARD FILTER (SAMPING) ================= -->

    <div class="col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Filter</h6>

                <div class="mb-3">
                    <div class="filter-label">Jenis Pajak</div>
                    <select class="form-select" id="filterPajak">
                        <option value="semua">Semua Pajak</option>
                        <!-- TODO: opsi jenis pajak diisi dinamis dari API (semua jenis pajak berlaku di sini) -->
                    </select>
                </div>

                <div class="mb-3">
                    <div class="filter-label">Bulan</div>
                    <select class="form-select" id="filterBulan">
                        <option value="semua">Semua Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>

                <div class="mb-3">
                    <div class="filter-label">Kecamatan</div>
                    <select class="form-select" id="filterKecamatan">
                        <option value="semua">Semua Kecamatan</option>
                        <!-- TODO: opsi kecamatan diisi dinamis dari API -->
                    </select>
                </div>

                <button type="button" class="btn btn-outline-secondary w-100 btn-reset-filter" id="btnResetFilter">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- ================= TABEL REALISASI PER KECAMATAN ================= -->

    <div class="col-lg-9">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kecamatan</th>
                            <th>Jenis Pajak</th>
                            <th class="text-end">Target (Rp)</th>
                            <th class="text-end">Realisasi (Rp)</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody id="tabelRealisasiBody">
                        <tr>
                            <td colspan="6" class="table-loading" id="tabelStatus">
                                Memuat data...
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total</strong></td>
                            <td class="text-end" id="footTotalTarget">—</td>
                            <td class="text-end" id="footTotalRealisasi">—</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

</div>

<?= $this->endSection() ?>

<?= $this->section('js-script') ?>
<script>
/*
 * Catatan integrasi API (Detail > Realisasi > Per Kecamatan):
 * - Endpoint realisasi & opsi filter belum tersedia saat file ini dibuat.
 *   Sesuaikan URL_* di bawah begitu API sudah siap. Jangan mengarang nama
 *   endpoint final tanpa konfirmasi ke dokumentasi/backend.
 * - Filter berlaku untuk SEMUA jenis pajak (sesuai spesifikasi halaman ini).
 */
const URL_OPSI_FILTER   = null; // contoh: '<?= base_url('api/detail/realisasi-kecamatan/opsi') ?>'
const URL_DATA_REALISASI = null; // contoh: '<?= base_url('api/detail/realisasi-kecamatan') ?>'

const el = {
    pajak: document.getElementById('filterPajak'),
    bulan: document.getElementById('filterBulan'),
    kecamatan: document.getElementById('filterKecamatan'),
    reset: document.getElementById('btnResetFilter'),
    tbody: document.getElementById('tabelRealisasiBody'),
    footTarget: document.getElementById('footTotalTarget'),
    footRealisasi: document.getElementById('footTotalRealisasi'),
    sumTarget: document.getElementById('sumTotalTarget'),
    sumRealisasi: document.getElementById('sumTotalRealisasi'),
    sumPersen: document.getElementById('sumPersen'),
    sumProgress: document.getElementById('sumProgressBar'),
};

function formatRupiah(angka) {
    const n = Number(angka) || 0;
    return 'Rp ' + n.toLocaleString('id-ID');
}

function setTableStatus(message) {
    el.tbody.innerHTML = '<tr><td colspan="6" class="table-loading">' + message + '</td></tr>';
}

async function muatOpsiFilter() {
    if (!URL_OPSI_FILTER) return; // belum ada endpoint, lewati diam-diam
    try {
        const res = await fetch(URL_OPSI_FILTER);
        if (!res.ok) throw new Error('Gagal memuat opsi filter');
        const opsi = await res.json();

        (opsi.pajak || []).forEach(function (p) {
            const o = document.createElement('option');
            o.value = p.id;
            o.textContent = p.nama_pajak;
            el.pajak.appendChild(o);
        });

        (opsi.kecamatan || []).forEach(function (k) {
            const o = document.createElement('option');
            o.value = k.id;
            o.textContent = k.nama_kecamatan;
            el.kecamatan.appendChild(o);
        });
    } catch (err) {
        console.error('Gagal memuat opsi filter:', err);
    }
}

async function muatDataRealisasi() {
    if (!URL_DATA_REALISASI) {
        setTableStatus('Sumber data realisasi per kecamatan belum tersambung ke API.');
        return;
    }

    setTableStatus('Memuat data...');

    const params = new URLSearchParams({
        pajak: el.pajak.value,
        bulan: el.bulan.value,
        kecamatan: el.kecamatan.value,
    });

    try {
        const res = await fetch(URL_DATA_REALISASI + '?' + params.toString());
        if (!res.ok) throw new Error('Gagal memuat data realisasi');
        const json = await res.json();
        renderTabel(json.data || []);
        renderRingkasan(json.summary || null);
    } catch (err) {
        console.error(err);
        setTableStatus('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
    }
}

function renderTabel(rows) {
    if (!rows.length) {
        el.tbody.innerHTML = '<tr><td colspan="6" class="table-empty">Tidak ada data untuk filter yang dipilih.</td></tr>';
        el.footTarget.textContent = '—';
        el.footRealisasi.textContent = '—';
        return;
    }

    let totalTarget = 0;
    let totalRealisasi = 0;

    el.tbody.innerHTML = rows.map(function (row, idx) {
        const target = Number(row.target) || 0;
        const realisasi = Number(row.realisasi) || 0;
        const persen = target > 0 ? Math.min(100, (realisasi / target) * 100) : 0;

        totalTarget += target;
        totalRealisasi += realisasi;

        return `
            <tr>
                <td>${idx + 1}</td>
                <td>${row.nama_kecamatan ?? '—'}</td>
                <td>${row.nama_pajak ?? '—'}</td>
                <td class="text-end">${formatRupiah(target)}</td>
                <td class="text-end">${formatRupiah(realisasi)}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="progress flex-grow-1">
                            <div class="progress-bar" style="width:${persen.toFixed(1)}%"></div>
                        </div>
                        <small class="ms-2">${persen.toFixed(1)}%</small>
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    el.footTarget.textContent = formatRupiah(totalTarget);
    el.footRealisasi.textContent = formatRupiah(totalRealisasi);
}

function renderRingkasan(summary) {
    if (!summary) return;
    const target = Number(summary.total_target) || 0;
    const realisasi = Number(summary.total_realisasi) || 0;
    const persen = target > 0 ? Math.min(100, (realisasi / target) * 100) : 0;

    el.sumTarget.textContent = formatRupiah(target);
    el.sumRealisasi.textContent = formatRupiah(realisasi);
    el.sumPersen.textContent = persen.toFixed(1) + ' %';
    el.sumProgress.style.width = persen.toFixed(1) + '%';
}

[el.pajak, el.bulan, el.kecamatan].forEach(function (input) {
    input.addEventListener('change', muatDataRealisasi);
});

el.reset.addEventListener('click', function () {
    el.pajak.value = 'semua';
    el.bulan.value = 'semua';
    el.kecamatan.value = 'semua';
    muatDataRealisasi();
});

document.addEventListener('DOMContentLoaded', function () {
    muatOpsiFilter();
    muatDataRealisasi();
});
</script>
<?= $this->endSection() ?>