<?= $this->extend('layouts/base') ?>

<?= $this->section('css') ?>
  <link href="<?= base_url('skote/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css">
  <link href="<?= base_url('skote/assets/libs/daterangepicker/daterangepicker.css') ?>" rel="stylesheet" type="text/css">
  <link href="<?= base_url('css/custom/daterangepicker.css') ?>" rel="stylesheet" type="text/css">
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link
        href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
        rel="stylesheet"
    />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>    

    <style>
      .select2-container {
          width: 100% !important;
      }

      .select2-container .select2-selection--single {
          height: 38px;
          border: 1px solid #dee2e6;
          border-radius: 0.375rem;
      }

      .select2-container--default
      .select2-selection--single
      .select2-selection__rendered {
          line-height: 36px;
          padding-left: 12px;
      }

      .select2-container--default
      .select2-selection--single
      .select2-selection__arrow {
          height: 36px;
      }

      .select2-dropdown {
          border-color: #dee2e6;
      }

      .select2-search--dropdown .select2-search__field {
          border-radius: 4px;
      }      
    </style>

    <style>
      .table-header-green th{
          background-color:#288052;
          color:#fff;
          text-align:center;
          vertical-align:middle;
      }

      .dashboard-card{
          border-radius:10px;
          border:1px solid #dcdcdc;
          background:#fff;
          padding:20px;
          width:100%;
          display:flex;
          flex-direction:column;
          justify-content:center;
      }      
    </style>

   
        
    
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<?php 
  // echo "<pre>";
  // var_dump($dataRealisasi);
  // echo "</pre>";
  // exit();
?>


<div class="container-fluid">

    <div class="mb-4">

        <!-- <h4 class="fw-bold mb-1">
            Detil Realisasi Pajak
        </h4>

        <div class="text-muted">
            Realisasi pajak per bulan berdasarkan wilayah
        </div> -->

    </div>


    <div class="row g-3">    


            <!-- =====================================================
                FILTER KIRI
            ====================================================== -->

            <div class="col-lg-3">
              <div class="dashboard-card">               

                <!-- <div class="card border-0 shadow-sm"> -->

                    <div class="card-header bg-white">

                        <h5 class="fw-bold mb-0">
                            Filter Data
                        </h5>

                    </div>


                    <div class="card-body">

                        <form
                            method="get"
                            action="<?= route_to('admin.detil.index') ?>"
                            id="formFilter">


                            <!-- TAHUN -->

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Tahun
                                </label>

                                <select
                                    name="tahun"
                                    class="form-select">

                                    <?php

                                    $tahunSekarang = date('Y');

                                    for (
                                        $i = $tahunSekarang - 5;
                                        $i <= $tahunSekarang + 1;
                                        $i++
                                    ):
                                    ?>

                                        <option
                                            value="<?= $i ?>"
                                            <?= $tahun == $i
                                                ? 'selected'
                                                : '' ?>>

                                            <?= $i ?>

                                        </option>

                                    <?php endfor; ?>

                                </select>

                            </div>


                            <!-- KECAMATAN -->

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Kecamatan
                                </label>

                                <select
                                    name="kode_kecamatan"
                                    id="kode_kecamatan"
                                    class="form-select"
                                    style="width: 100%;">

                                    <option value="">
                                        Semua Kecamatan
                                    </option>

                                    <?php foreach (
                                        $kecamatan as $item
                                    ): ?>

                                        <option
                                            value="<?= esc(
                                                $item['kode_kecamatan']
                                            ) ?>"
                                            <?= $kodeKecamatan ==
                                                $item['kode_kecamatan']
                                                    ? 'selected'
                                                    : '' ?>>

                                            <?= esc(
                                                $item['nama_kecamatan']
                                            ) ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <!-- DESA -->

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Desa
                                </label>

                                <select
                                    name="kode_desa"
                                    id="kode_desa"
                                    class="form-select select2"
                                    style="width: 100%;">

                                    <option value="">
                                        Semua Desa
                                    </option>

                                    <?php foreach (
                                        $desa as $item
                                    ): ?>

                                        <option
                                            value="<?= esc(
                                                $item['kode_desa']
                                            ) ?>"
                                            <?= $kodeDesa ==
                                                $item['kode_desa']
                                                    ? 'selected'
                                                    : '' ?>>

                                            <?= esc(
                                                $item['nama_desa']
                                            ) ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <!--- jenis pajak --> 
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Jenis Pajak
                                </label>

                                <select
                                    name="jenis_id"
                                    id="jenis_id"
                                    class="form-select select2"
                                    style="width: 100%;">

                                    <option value="">
                                        Semua Jenis Pajak
                                    </option>

                                    <?php foreach ($jenisPajak as $item): ?>

                                        <option
                                            value="<?= esc($item['id']) ?>"
                                            <?= $jenisId == $item['id']
                                                ? 'selected'
                                                : '' ?>>

                                            <?= esc($item['nama_pajak']) ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>                        


                            <button
                                type="submit"
                                class="btn btn-primary w-100">

                                <i class="bi bi-search me-1"></i>

                                Tampilkan

                            </button>


                            <a
                                href="<?= route_to(
                                    'admin.detil.index'
                                ) ?>"
                                class="btn btn-light w-100 mt-2">

                                Reset

                            </a>

                        </form>

                    </div>

                <!-- </div> -->
              </div>                

            </div>
                       


            <!-- =====================================================
                TABEL KANAN
            ====================================================== -->

            <div class="col-lg-9">
              <div class="dashboard-card">               

                <!-- <div class="card border-0 shadow-sm"> -->

                    <div class="card-header bg-white">

                      <!---- SUMMARY --> 
                      <div class="row g-3 mb-3">

                          <!-- TARGET -->

                          <div class="col-md-4">

                              <div class="card border-0 shadow-sm h-100">

                                  <div class="card-body">

                                      <div class="text-muted small">
                                          Target Tahun <?= esc($tahun) ?>
                                      </div>

                                      <div class="fs-5 fw-bold mt-1">

                                          Rp <?= number_format(
                                              (float) $total['total_target'],
                                              0,
                                              ',',
                                              '.'
                                          ) ?>

                                      </div>

                                  </div>

                              </div>

                          </div>


                          <!-- REALISASI -->

                          <div class="col-md-4">

                              <div class="card border-0 shadow-sm h-100">

                                  <div class="card-body">

                                      <div class="text-muted small">
                                          Realisasi Tahun <?= esc($tahun) ?>
                                      </div>

                                      <div class="fs-5 fw-bold text-success mt-1">

                                          Rp <?= number_format(
                                              (float) $total['total_realisasi'],
                                              0,
                                              ',',
                                              '.'
                                          ) ?>

                                      </div>

                                  </div>

                              </div>

                          </div>


                          <!-- PERSENTASE -->

                          <div class="col-md-4">

                              <div class="card border-0 shadow-sm h-100">

                                  <div class="card-body">

                                      <div class="text-muted small">
                                          Persentase Realisasi
                                      </div>

                                      <div class="fs-5 fw-bold mt-1">

                                          <?= number_format(
                                              (float) $total['persentase'],
                                              1,
                                              ',',
                                              '.'
                                          ) ?>%

                                      </div>

                                      <div class="progress mt-2"
                                          style="height: 7px;">

                                          <?php
                                          $progress =
                                              min(
                                                  100,
                                                  max(
                                                      0,
                                                      (float) $total['persentase']
                                                  )
                                              );
                                          ?>

                                          <div
                                              class="progress-bar
                                              <?= $total['persentase'] >= 100
                                                  ? 'bg-success'
                                                  : 'bg-primary' ?>"
                                              style="width: <?= $progress ?>%">

                                          </div>

                                      </div>

                                  </div>

                              </div>

                          </div>

                      </div>                

                        <div class="d-flex
                                    justify-content-between
                                    align-items-center">

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Realisasi Per Bulan
                                </h5>

                                <small class="text-muted">

                                    Tahun <?= esc($tahun) ?>

                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <!-- <div class="card border-0 shadow-sm mb-3">

                            <div class="card-header bg-white">

                                <h5 class="fw-bold mb-1">
                                    Grafik Realisasi Pajak
                                </h5>

                                <small class="text-muted">
                                    Realisasi per bulan tahun <?= esc($tahun) ?>
                                </small>

                            </div>

                            <div class="card-body">

                                <div style="height: 350px;">

                                    <canvas id="chartRealisasi"></canvas>

                                </div>

                            </div>

                        </div>                 -->

                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header bg-white">
                                <h5 class="fw-bold mb-0">
                                    Target vs Realisasi
                                </h5>
                            </div>

                            <div class="card-body">
                                <div style="height: 400px;">
                                    <canvas id="chartRealisasi"></canvas>
                                </div>
                            </div>
                        </div>      


                        <div class="table-responsive">

                            <table
                                class="table table-bordered
                                      table-hover
                                      align-middle mb-0">

                                <!-- <thead class="table-light"> -->
                                  <thead class="table-header-green">

                                    <tr>

                                        <th
                                            class="text-center"
                                            style="width:50px">

                                            No

                                        </th>

                                        <th
                                            style="min-width:200px">

                                            Jenis Pajak

                                        </th>

                                        <th class="text-end">
                                            Jan
                                        </th>

                                        <th class="text-end">
                                            Feb
                                        </th>

                                        <th class="text-end">
                                            Mar
                                        </th>

                                        <th class="text-end">
                                            Apr
                                        </th>

                                        <th class="text-end">
                                            Mei
                                        </th>

                                        <th class="text-end">
                                            Jun
                                        </th>

                                        <th class="text-end">
                                            Jul
                                        </th>

                                        <th class="text-end">
                                            Agu
                                        </th>

                                        <th class="text-end">
                                            Sep
                                        </th>

                                        <th class="text-end">
                                            Okt
                                        </th>

                                        <th class="text-end">
                                            Nov
                                        </th>

                                        <th class="text-end">
                                            Des
                                        </th>

                                        <th class="text-end">
                                            Total Target
                                        </th>                                    

                                        <th class="text-end">
                                            Total Realisasi
                                        </th>

                                        <th
                                            class="text-end"
                                            style="min-width:90px">

                                            %

                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                <?php if (
                                    !empty($dataRealisasi)
                                ): ?>

                                    <?php $no = 1; ?>

                                    <?php foreach (
                                        $dataRealisasi
                                        as $item
                                    ): ?>

                                        <tr>

                                            <td class="text-center">
                                                <?= $no++ ?>
                                            </td>

                                            <td>

                                                <strong>
                                                    <?= esc(
                                                        $item['nama_pajak']
                                                    ) ?>
                                                </strong>

                                            </td>


                                          <?php for ($bulan = 1; $bulan <= 12; $bulan++): ?>

                                              <?php
                                              $realisasiBulan =
                                                  $item['bulan'][$bulan]['realisasi'] ?? 0;
                                              ?>

                                              <td class="text-end">

                                                  <?= number_format(
                                                      (float) $realisasiBulan,
                                                      0,
                                                      ',',
                                                      '.'
                                                  ) ?>

                                              </td>

                                          <?php endfor; ?>

                                          <td class="text-end fw-semibold">

                                              <?= number_format(
                                                  (float) ($item['total_target'] ?? 0),
                                                  0,
                                                  ',',
                                                  '.'
                                              ) ?>

                                          </td>

                                          <td class="text-end fw-bold">

                                              <?= number_format(
                                                  (float) $item['total_realisasi'],
                                                  0,
                                                  ',',
                                                  '.'
                                              ) ?>

                                          </td>

                                          <td class="text-end fw-bold">

                                              <?= number_format(
                                                  (float) $item['persentase'],
                                                  1,
                                                  ',',
                                                  '.'
                                              ) ?>%

                                          </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>

                                        <td
                                            colspan="16"
                                            class="text-center
                                                  text-muted
                                                  py-5">

                                            <i class="bi
                                                      bi-database-x
                                                      fs-2 d-block mb-2">
                                            </i>

                                            Tidak ada data realisasi

                                        </td>

                                    </tr>

                                <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                <!-- </div> -->
                 
              </div>

            </div>



    </div>

</div>


<script>
  document
      .getElementById('kode_kecamatan')
      .addEventListener('change', function () {

          const kodeKecamatan = this.value;

          const selectDesa =
              document.getElementById('kode_desa');


          /*
          * Reset desa
          */
          selectDesa.innerHTML = `
              <option value="">
                  Semua Desa
              </option>
          `;


          /*
          * Jika semua kecamatan
          */
          if (!kodeKecamatan) {

              return;

          }


          fetch(
              '<?= route_to('admin.detil.desa') ?>'
              + '?kode_kecamatan='
              + encodeURIComponent(kodeKecamatan)
          )

          .then(response => response.json())

          .then(response => {

              if (!response.status) {
                  return;
              }


              response.data.forEach(function (item) {

                  const option =
                      document.createElement('option');

                  option.value =
                      item.kode_desa;

                  option.textContent =
                      item.nama_desa;

                  selectDesa.appendChild(option);

              });

          })

          .catch(error => {

              console.error(error);

          });

      });

</script>

<!---GRAFIK-->
<?php

  $grafikTarget = [];
  $grafikRealisasi = [];
  $grafikPersentase = [];

  for ($bulan = 1; $bulan <= 12; $bulan++) {

      $totalTarget = 0;
      $totalRealisasi = 0;

      foreach ($dataRealisasi as $item) {

          $totalTarget += (float) (
              $item['bulan'][$bulan]['target'] ?? 0
          );

          $totalRealisasi += (float) (
              $item['bulan'][$bulan]['realisasi'] ?? 0
          );
      }

      $grafikTarget[] = $totalTarget;
      $grafikRealisasi[] = $totalRealisasi;

      $grafikPersentase[] = $totalTarget > 0
          ? ($totalRealisasi / $totalTarget) * 100
          : null;
  }

?>
<script>
  const grafikTarget = <?= json_encode($grafikTarget) ?>;

  const grafikRealisasi = <?= json_encode($grafikRealisasi) ?>;

  const grafikPersentase = <?= json_encode($grafikPersentase) ?>;

  console.log('Target:', grafikTarget);
  console.log('Realisasi:', grafikRealisasi);
  console.log('Persentase:', grafikPersentase);  
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {

      const canvas = document.getElementById(
          'chartRealisasi'
      );

      if (!canvas) {
          return;
      }


      const labels = [
          'Januari',
          'Februari',
          'Maret',
          'April',
          'Mei',
          'Juni',
          'Juli',
          'Agustus',
          'September',
          'Oktober',
          'November',
          'Desember'
      ];


      const target =
          <?= json_encode($grafikTarget) ?>;


      const realisasi =
          <?= json_encode($grafikRealisasi) ?>;


      const persentase =
          <?= json_encode($grafikPersentase) ?>;


      console.log('TARGET', target);
      console.log('REALISASI', realisasi);
      console.log('PERSENTASE', persentase);


      function formatRupiah(value) {

          return 'Rp ' +
              Number(value).toLocaleString('id-ID');

      }

      // format milyar
      function formatSingkat(value) {

          value = Number(value);

          if (value >= 1000000000) {

              return 'Rp ' +
                  (value / 1000000000)
                      .toFixed(2)
                  + ' M';

          }

          if (value >= 1000000) {

              return 'Rp ' +
                  (value / 1000000)
                      .toFixed(2)
                  + ' Jt';

          }

          if (value >= 1000) {

              return 'Rp ' +
                  (value / 1000)
                      .toFixed(0)
                  + ' Rb';

          }

          return 'Rp ' +
              value.toLocaleString('id-ID');
      }    


      new Chart(canvas, {

          type: 'line',

          plugins: [
              ChartDataLabels
          ],

          data: {

              labels: labels,

              datasets: [

                  // =====================================================
                  // TARGET
                  // =====================================================

                  {
                      label: 'Target',

                      data: target,

                      borderWidth: 3,

                      borderDash: [8, 5],

                      tension: 0.3,

                      fill: false,

                      pointRadius: 5,

                      pointHoverRadius: 7,

                      datalabels: {

                          display: false

                      }
                  },


                  // =====================================================
                  // REALISASI
                  // =====================================================

                  {
                      label: 'Realisasi',

                      data: realisasi,

                      borderWidth: 3,

                      tension: 0.3,

                      fill: false,

                      pointRadius: 5,

                      pointHoverRadius: 7,

                      datalabels: {

                          display: function(context) {

                              const index =
                                  context.dataIndex;

                              return (
                                  realisasi[index] > 0 &&
                                  persentase[index] !== null
                              );

                          },

                          formatter: function(
                              value,
                              context
                          ) {

                              const index =
                                  context.dataIndex;

                              return (
                                  persentase[index]
                                      .toFixed(1)
                                  + '%'
                              );

                          },

                          align: 'top',

                          anchor: 'center',

                          offset: 6,

                          clamp: true,

                          clip: false,

                          font: {

                              weight: 'bold',

                              size: 10

                          },

                          padding: 2
                      }
                  }

              ]

          },


          // =============================================================
          // OPTIONS
          // =============================================================

          options: {

              responsive: true,

              maintainAspectRatio: false,

              layout: {

                  padding: {

                      top: 30,

                      right: 20,

                      left: 10,

                      bottom: 10

                  }

              },


              interaction: {

                  mode: 'index',

                  intersect: false

              },


              plugins: {

                  legend: {

                      display: true,

                      position: 'bottom',

                      labels: {

                          padding: 15

                      }

                  },


                  tooltip: {

                      callbacks: {

                          label: function(context) {

                              return (
                                  context.dataset.label
                                  + ': '
                                  + formatRupiah(
                                      context.raw
                                  )
                              );

                          },

                          afterLabel: function(context) {

                              if (
                                  context.dataset.label
                                  !== 'Realisasi'
                              ) {
                                  return '';
                              }

                              const index =
                                  context.dataIndex;

                              const persen =
                                  persentase[index];

                              if (
                                  persen === null ||
                                  persen === undefined
                              ) {
                                  return '';
                              }

                              return (
                                  'Pencapaian: '
                                  + persen.toFixed(1)
                                  + '%'
                              );
                          }

                      }

                  }

              },


              // =========================================================
              // SATU SUMBU Y
              // =========================================================

              scales: {

                  y: {

                      beginAtZero: true,

                      ticks: {

                          callback: function(value) {

                              return formatSingkat(value);

                          }

                      }

                  },

                  x: {

                      ticks: {

                          autoSkip: false

                      }

                  }

              }

          }

      });    


  });

</script>




<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('skote/assets/libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/daterangepicker/moment-with-locales.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/daterangepicker/daterangepicker.js') ?>"></script>
<?= $this->endSection() ?>