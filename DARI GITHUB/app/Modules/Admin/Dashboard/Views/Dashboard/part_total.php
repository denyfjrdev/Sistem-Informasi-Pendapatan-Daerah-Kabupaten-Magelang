<?php 
  // echo "<pre>";
  // var_dump($realisasi['realisasi']);
  // echo "</pre>";
  // exit();


  #--- initial variable global
  $semua_realisasi_berjalan = $semua_realisasi_tahun = 0;
  $semua_target_berjalan = $semua_target_tahun = 0;
  $total_persentase_berjalan  = $total_persentase_tahun = 0;

  #---buat data per jenis_pajak
  $array_per_jenis_pajak =  [];
  foreach($realisasi['target'] as $val){
    $array_per_jenis_pajak[$val['jenis_id']]["nama_pajak"]  = $val['nama_pajak'];    
  }
  
  #---isi target      
  foreach($realisasi['target'] as $val){
    $total_target  = 0;
    for($i=1;$i<=12;$i++){
      $total_target += $val[$i];

      #----total target sampai saat ini ( bulan berjalan )
      if($i <= date("m")){
        $semua_target_berjalan += $val[$i];
      }
    }
    $array_per_jenis_pajak[$val['jenis_id']]["target"]  = $total_target;                 
  }

  #---isi realisasi  
  foreach($realisasi['realisasi'] as $val){
    $total_realisasi  = 0;
    for($i=1;$i<=12;$i++){
      $total_realisasi += isset($val['detil'][$i]) ? $val['detil'][$i] : 0;

      #----total target sampai saat ini ( bulan berjalan )
      if($i <= date("m")){
        $semua_realisasi_berjalan += isset($val['detil'][$i]) ? $val['detil'][$i] : 0;
      }      
    }
    $array_per_jenis_pajak[$val['jenis_id']]["realisasi"]  = $total_realisasi;          
  }

  #---prosentase
  foreach($array_per_jenis_pajak as $index=>$val){
    // $persen   = ($val['target'] / $val['realisasi']) * 100;
    $target    = $val['target'] ?? 0;
    $realisasi = $val['realisasi'] ?? 0;

    $persen = $target != 0
        ? ( $realisasi / $target) * 100
        : 0;    
    $array_per_jenis_pajak[$index]["persen"]  = $persen;

    #---hitung total target dan total setahun
    $semua_target_tahun += $target;
    $semua_realisasi_tahun += $realisasi;
  }
  // $total_persentase_berjalan  = ($semua_realisasi_berjalan/$semua_target_tahun) * 100;
  // $total_persentase_tahun     = ($semua_realisasi_tahun/$semua_target_tahun) * 100;
$total_persentase_berjalan = $semua_target_tahun > 0
    ? ($semua_realisasi_berjalan / $semua_target_berjalan) * 100
    : 0;

$total_persentase_tahun = $semua_target_tahun > 0
    ? ($semua_realisasi_tahun / $semua_target_tahun) * 100
    : 0;  



  // echo "<pre>";
  // var_dump($array_per_jenis_pajak);
  // echo "</pre>";
  // var_dump($total_persentase_berjalan);exit();
?>



  <div class="container-fluid mt-3">
    <!---DASHBOARD--->
    <div class="row g-3">
        <!---KIRI--->
        <div class="col-lg-6">

            <div class="dashboard-card">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="card-title">
                            REALISASI TOTAL SAAT INI : <?=date("d-m-Y")?>
                        </div>

                        <div class="nominal" id="semuaRealisasiBerjalan">
                            Rp 0
                        </div>

                        <div class="d-flex align-items-center">

                            <span class="badge-progress" id="semuaPersenBerjalan">
                                +0.0%
                            </span>

                            <span class="subtitle ms-2">
                                vs target periode saat ini : <strong>Rp. <?= number_format($semua_target_berjalan / 1000000000, 2, ',', '.') . ' M'; ?> </strong>
                            </span>

                        </div>

                    </div>

                    <div>
                        <i class="bi bi-arrow-up-right icon-growth"></i>
                    </div>

                </div>

            </div>

        </div>

        <!---KANAN--->
        <div class="col-lg-6">

            <div class="dashboard-card">

                <div class="row align-items-center">

                    <div class="col-md-9">

                        <div class="card-title">
                            PROGRESS TARGET PERIODE <?=date("Y")?>
                        </div>

                        <div class="progress mb-2" id="progressBar">
                          <div class="progress-bar"
                              role="progressbar"
                              style="width:<?=$total_persentase_tahun?>%">
                          </div>
                        </div>

                        <div class="d-flex justify-content-between">

                            <div class="text-small" id="persenRealisasiTahun">
                                <strong>SAAT INI : 0.0%</strong>
                            </div>

                            <div class="text-small text-secondary" id="semuaTargetTahun">
                                <strong>Target belum tersedia</strong>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="periode" id="periode-countdown">

                            <div class="card-title mb-1">
                                PERIODE BERAKHIR DALAM
                            </div>

                            <div class="d-flex align-items-baseline justify-content-center gap-2">
                                <div class="hari" id="periode-hari">
                                    -
                                </div>
                                <div class="fw-bold hari-label">
                                    Hari
                                </div>
                            </div>

                            <div class="tanggal" id="periode-tanggal">
                                -
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!---TABEL2--->
    <div class="row"> 


        <div class="col-md-7">
          <div class="dashboard-card">
            <table class="table table-hover table-sm table-bordered align-middle">          
              <thead class="table-header-green">
                  <th>No</th>
                  <th>Nama Jenis Pajak</th>                  
                  <th>Ketetapan</th>
                  <th>Target</th>
                  <th>Realisasi</th>
                  <th>Prosentase</th>
              </thead>
              <tbody>
                <?php  $persen_realisasi=$semua_target=$semua_realisasi=0;$no=1;foreach($array_per_jenis_pajak as $index=>$val){?>                            
                  <tr>
                    <td><?=$no++?></td>
                    <td><?=$val['nama_pajak']?></td> 
                    <td>...</td>
                    <td align="right"> <?= number_format($val['target'] ?? 0, 0, ',', '.') ?> </td>
                    <td align="right"> <?= number_format($val['realisasi'] ?? 0, 0, ',', '.') ?> </td>
                    <td align="right"> <?= number_format($val['persen'] ?? 0, 0, ',', '.') ?> % </td>
                  </tr>
                <?php }?>  
                
                <tr>              
                  <td colspan="2" align="right" class="fw-bold fs-5">Total</td>
                  <td>...</td>
                  <td align="right" class="fw-bold fs-5">
                      <?= number_format($semua_target_tahun ?? 0, 0, ',', '.') ?>
                  </td>
                  <td align="right" class="fw-bold fs-5">
                      <?= number_format($semua_realisasi_tahun ?? 0, 0, ',', '.') ?>
                  </td>
                  <td align="right" class="fw-bold fs-5">
                    <?= number_format($total_persentase_tahun ?? 0, 0, ',', '.') ?> %
                  </td>

                </tr>          
              </tbody>
            </table>
          </div>
        </div>

      
        <div class="col-md-5">
          <!-- <div class="card">
            <div class="card-header">
              Prosentase
            </div>        
          </div> -->
          <div class="dashboard-card">
              <div class="card-body">
                  <h5 class="card-title text-white p-2" style="background-color: #288052;">
                      Persentase Realisasi Pajak
                  </h5>

                  <div style="height: 450px;">
                      <canvas id="grafikPersentasePajak"></canvas>
                  </div>
              </div>
          </div>      
        </div>      
                
    </div>


  </div>



<!----GRAFIK-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
  const dataPajak = <?= json_encode($array_per_jenis_pajak) ?>;

  const labels = [];
  const persentase = [];

  Object.values(dataPajak).forEach(function(item) {
      labels.push(item.nama_pajak);
      persentase.push(Number(item.persen));
  });

  console.log(labels);
  console.log(persentase);
</script>
<script>
  const ctx = document.getElementById('grafikPersentasePajak');


  new Chart(ctx, {
      type: 'bar',

      data: {
          labels: labels,

          datasets: [{
              label: 'Persentase Realisasi (%)',
              data: persentase,
              borderWidth: 1
          }]
      },

      options: {
          responsive: true,
          maintainAspectRatio: false,

          indexAxis: 'y',

          scales: {
              x: {
                  beginAtZero: true,

                  ticks: {
                      callback: function(value) {
                          return value + '%';
                      }
                  }
              }
          },

          plugins: {

              legend: {
                  display: true
              },

              // Tampilkan persen pada bar
              datalabels: {
                  anchor: 'end',
                  align: 'right',

                  formatter: function(value) {
                      return Number(value).toFixed(1) + '%';
                  },

                  font: {
                      weight: 'bold',
                      size: 12
                  }
              },

              tooltip: {
                  callbacks: {
                      label: function(context) {
                          return Number(context.raw).toFixed(1) + '%';
                      }
                  }
              }
          }
      },

      plugins: [
          ChartDataLabels
      ]
  });  

  
</script>

<script>
  // kiri
  $("#semuaRealisasiBerjalan").html("Rp. <?= number_format($semua_realisasi_berjalan / 1000000000, 2, ',', '.') . ' M'; ?>");  
  $("#semuaPersenBerjalan").html("<?= number_format($total_persentase_berjalan ?? 0, 0, ',', '.') ?> %");    

  // kanan
  $("#persenRealisasiTahun").html("<?= number_format($total_persentase_tahun ?? 0, 0, ',', '.') ?> %");    
  $("#semuaTargetTahun").html("<strong>Rp. <?= number_format($semua_target_tahun / 1000000000, 2, ',', '.') . ' M'; ?></strong>");  

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

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
        tanggalEl.innerHTML = tanggalStr + "<br>" + jam + "." + menit + "." + detik;
    }

    updatePeriodeCountdown();
    setInterval(updatePeriodeCountdown, 1000);
</script>