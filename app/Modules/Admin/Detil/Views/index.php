<?= $this->extend('layouts/base') ?>

<?= $this->section('css') ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    .table-header-green th{
      background-color:#288052;
      color:#fff;
      text-align:center;
      vertical-align:middle;
      white-space:nowrap;
    }
    .dashboard-card{
      border-radius:10px;
      border:1px solid #dcdcdc;
      background:#fff;
      padding:20px;
      width:100%;
    }
    th.sortable{
      cursor:pointer;
      user-select:none;
    }
    th.sortable .sort-icon{
      opacity:.55;
      margin-left:4px;
      font-size:11px;
    }
    th.sortable.sort-asc .sort-icon,
    th.sortable.sort-desc .sort-icon{
      opacity:1;
    }
    tr.row-kecamatan td{
      background:#f3faf6;
      font-weight:600;
    }
    tr.row-desa td:nth-child(2){
      padding-left:1.5rem;
    }
    tr.row-total td{
      background:#eef6f1;
      font-weight:700;
    }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
  $jenisPakaiKelurahan = $jenisPakaiKelurahan ?? true;
  $colCount = 2 + count($kolomPajak) + ($showTotalKolom ? 1 : 0);
  $namaBulan = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
    7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
  ];
?>

<div class="container-fluid">
  <div class="row g-3">

    <!-- FILTER -->
    <div class="col-lg-3">
      <div class="dashboard-card">
        <h5 class="fw-bold mb-3">Filter Data</h5>

        <form method="get" action="<?= route_to('admin.detil.index') ?>" id="formFilter">

          <div class="mb-3">
            <label class="form-label fw-semibold">Tahun</label>
            <select name="tahun" class="form-select">
              <?php
              $tahunSekarang = (int) date('Y');
              for ($i = $tahunSekarang - 5; $i <= $tahunSekarang + 1; $i++):
              ?>
                <option value="<?= $i ?>" <?= (int)$tahun === $i ? 'selected' : '' ?>><?= $i ?></option>
              <?php endfor; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Kecamatan</label>
            <select name="kode_kecamatan" id="kode_kecamatan" class="form-select">
              <option value="">Semua Kecamatan</option>
              <?php foreach ($kecamatan as $item): ?>
                <option value="<?= esc($item['kode_kecamatan']) ?>"
                  <?= $kodeKecamatan == $item['kode_kecamatan'] ? 'selected' : '' ?>>
                  <?= esc($item['nama_kecamatan']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Desa</label>
            <select name="kode_desa" id="kode_desa" class="form-select">
              <option value="">Semua Desa</option>
              <?php foreach ($desa as $item): ?>
                <option value="<?= esc($item['kode_desa']) ?>"
                  <?= $kodeDesa == $item['kode_desa'] ? 'selected' : '' ?>>
                  <?= esc($item['nama_desa']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Jenis Pajak</label>
            <select name="jenis_id" id="jenis_id" class="form-select">
              <option value="">Semua Jenis Pajak</option>
              <?php foreach ($jenisPajak as $item): ?>
                <option value="<?= esc($item['id']) ?>"
                  <?= $jenisId == $item['id'] ? 'selected' : '' ?>>
                  <?= esc($item['nama_pajak']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="form-text">
              Data per kecamatan/desa untuk e-SPTPD hanya tampil jika Semua Jenis Pajak.
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search me-1"></i> Tampilkan
          </button>
          <a href="<?= route_to('admin.detil.index') ?>" class="btn btn-light w-100 mt-2">Reset</a>
        </form>
      </div>
    </div>

    <!-- KONTEN -->
    <div class="col-lg-9">

      <?= $this->include('App\Modules\Admin\Detil\Views\card_summary') ?>

      <?= $this->include('App\Modules\Admin\Detil\Views\card_chart_bulan') ?>

      <div class="dashboard-card mt-3">
        <ul class="nav nav-tabs" id="tabDetil" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-wilayah-btn" data-bs-toggle="tab"
              data-bs-target="#tab-wilayah" type="button" role="tab">
              Per Kecamatan
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-bulan-btn" data-bs-toggle="tab"
              data-bs-target="#tab-bulan" type="button" role="tab">
              Per Bulan
            </button>
          </li>
        </ul>

        <div class="tab-content pt-3" id="tabDetilContent">

          <!-- TAB PER KECAMATAN / WILAYAH -->
          <div class="tab-pane fade show active" id="tab-wilayah" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle mb-0" id="tableWilayah">
                <thead class="table-header-green">
                  <tr>
                    <th style="width:50px">No</th>
                    <th>
                      <?php if ($modeWilayah === 'one_desa'): ?>
                        Desa
                      <?php elseif ($modeWilayah === 'one_kec'): ?>
                        Kecamatan / Desa
                      <?php else: ?>
                        Kecamatan
                      <?php endif; ?>
                    </th>
                    <?php foreach ($kolomPajak as $kol): ?>
                      <th class="sortable text-end" data-sort-key="<?= esc($kol['key']) ?>">
                        <?= esc($kol['label']) ?>
                        <span class="sort-icon">▲▼</span>
                      </th>
                    <?php endforeach; ?>
                    <?php if ($showTotalKolom): ?>
                      <th class="sortable text-end" data-sort-key="total">
                        Total
                        <span class="sort-icon">▲▼</span>
                      </th>
                    <?php endif; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($tabelWilayah)): ?>
                    <?php $no = 1; foreach ($tabelWilayah as $row): ?>
                      <tr class="<?= ($row['tipe'] ?? '') === 'kecamatan' ? 'row-kecamatan' : 'row-desa' ?>"
                          data-total="<?= (float) $row['total_realisasi'] ?>"
                          <?php foreach ($kolomPajak as $kol): ?>
                            data-<?= esc($kol['key']) ?>="<?= (float) ($row['pajak'][$kol['key']] ?? 0) ?>"
                          <?php endforeach; ?>>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                          <?php if (($row['tipe'] ?? '') === 'desa' && $modeWilayah === 'one_kec'): ?>
                            <span class="text-muted">↳</span>
                          <?php endif; ?>
                          <?= esc($row['nama']) ?>
                        </td>
                        <?php foreach ($kolomPajak as $kol): ?>
                          <td class="text-end">
                            <?= number_format((float) ($row['pajak'][$kol['key']] ?? 0), 0, ',', '.') ?>
                          </td>
                        <?php endforeach; ?>
                        <?php if ($showTotalKolom): ?>
                          <td class="text-end fw-bold">
                            <?= number_format((float) $row['total_realisasi'], 0, ',', '.') ?>
                          </td>
                        <?php endif; ?>
                      </tr>
                    <?php endforeach; ?>

                    <tr class="row-total" data-nosort="1">
                      <td></td>
                      <td>
                        <?php if ($modeWilayah === 'all_kec'): ?>
                          Semua Kecamatan
                        <?php elseif ($modeWilayah === 'one_kec'): ?>
                          Total
                        <?php else: ?>
                          Total Desa
                        <?php endif; ?>
                      </td>
                      <?php foreach ($kolomPajak as $kol): ?>
                        <td class="text-end">
                          <?= number_format((float) ($totalRowPajak[$kol['key']] ?? 0), 0, ',', '.') ?>
                        </td>
                      <?php endforeach; ?>
                      <?php if ($showTotalKolom): ?>
                        <td class="text-end">
                          <?= number_format((float) $totalRowSum, 0, ',', '.') ?>
                        </td>
                      <?php endif; ?>
                    </tr>
                  <?php else: ?>
                    <tr>
                      <td colspan="<?= $colCount ?>" class="text-center text-muted py-5">
                        <?php if (empty($jenisPakaiKelurahan)): ?>
                          Data per kecamatan/desa tidak tersedia untuk jenis pajak ini.
                          Realisasi per kelurahan hanya dipakai jika filter Semua Jenis Pajak.
                        <?php else: ?>
                          Tidak ada data
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <div class="text-muted small mt-2">
              <i class="bi bi-info-circle"></i>
              <?php if ($jenisId === null): ?>
                Kolom PBB = jenis pajak PBB, Opsen = kendaraan, STPD = non-PBB (per kelurahan). Klik header untuk mengurutkan.
              <?php elseif (empty($jenisPakaiKelurahan)): ?>
                Filter per jenis pajak e-SPTPD tidak bisa dipecah per kelurahan. Ringkasan dan tab Per Bulan memakai data LRA kabupaten.
              <?php else: ?>
                Filter satu jenis pajak aktif — kolom Total disembunyikan. Klik header untuk mengurutkan.
              <?php endif; ?>
            </div>
          </div>

          <!-- TAB PER BULAN -->
          <div class="tab-pane fade" id="tab-bulan" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-header-green">
                  <tr>
                    <th style="width:50px">No</th>
                    <th>Bulan</th>
                    <th class="text-end">Target</th>
                    <th class="text-end">Realisasi</th>
                    <th class="text-end">%</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sumT = 0; $sumR = 0; $n = 1;
                    foreach ($dataBulanan as $b => $val):
                      $sumT += $val['target'];
                      $sumR += $val['realisasi'];
                  ?>
                    <tr>
                      <td class="text-center"><?= $n++ ?></td>
                      <td><?= $namaBulan[$b] ?></td>
                      <td class="text-end"><?= number_format($val['target'], 0, ',', '.') ?></td>
                      <td class="text-end"><?= number_format($val['realisasi'], 0, ',', '.') ?></td>
                      <td class="text-end"><?= number_format($val['persentase'], 1, ',', '.') ?>%</td>
                    </tr>
                  <?php endforeach; ?>
                  <tr class="row-total">
                    <td></td>
                    <td>Total</td>
                    <td class="text-end"><?= number_format($sumT, 0, ',', '.') ?></td>
                    <td class="text-end"><?= number_format($sumR, 0, ',', '.') ?></td>
                    <td class="text-end">
                      <?= number_format($sumT > 0 ? ($sumR / $sumT) * 100 : 0, 1, ',', '.') ?>%
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('kode_kecamatan').addEventListener('change', function () {
    const kodeKecamatan = this.value;
    const selectDesa = document.getElementById('kode_desa');
    selectDesa.innerHTML = '<option value="">Semua Desa</option>';
    if (!kodeKecamatan) return;

    fetch('<?= route_to('admin.detil.desa') ?>?kode_kecamatan=' + encodeURIComponent(kodeKecamatan))
      .then(r => r.json())
      .then(response => {
        if (!response.status) return;
        response.data.forEach(function (item) {
          const option = document.createElement('option');
          option.value = item.kode_desa;
          option.textContent = item.nama_desa;
          selectDesa.appendChild(option);
        });
      })
      .catch(console.error);
  });

  // Sorting dinamis kolom pajak / total
  (function () {
    const table = document.getElementById('tableWilayah');
    if (!table) return;
    const tbody = table.querySelector('tbody');
    let sortKey = null;
    let sortDir = 'desc';

    table.querySelectorAll('th.sortable').forEach(function (th) {
      th.addEventListener('click', function () {
        const key = th.getAttribute('data-sort-key');
        if (sortKey === key) {
          sortDir = sortDir === 'asc' ? 'desc' : 'asc';
        } else {
          sortKey = key;
          sortDir = 'desc';
        }

        table.querySelectorAll('th.sortable').forEach(el => el.classList.remove('sort-asc', 'sort-desc'));
        th.classList.add(sortDir === 'asc' ? 'sort-asc' : 'sort-desc');

        const rows = Array.from(tbody.querySelectorAll('tr')).filter(tr => !tr.hasAttribute('data-nosort'));
        const totalRow = tbody.querySelector('tr[data-nosort]');

        rows.sort(function (a, b) {
          const av = parseFloat(a.getAttribute('data-' + key) || '0');
          const bv = parseFloat(b.getAttribute('data-' + key) || '0');
          return sortDir === 'asc' ? av - bv : bv - av;
        });

        rows.forEach((tr, idx) => {
          tr.querySelector('td').textContent = String(idx + 1);
          tbody.appendChild(tr);
        });
        if (totalRow) tbody.appendChild(totalRow);
      });
    });
  })();
</script>

<?= $this->endSection() ?>
