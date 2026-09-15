<!---- GRAFIK: di atas tab, card sendiri --->
<div class="dashboard-card mb-3">
    <h5 class="card-title text-white p-2 mb-3" style="background-color:#288052; border-radius:6px;">
        Target vs Realisasi per Bulan
    </h5>
    <small class="text-muted d-block mb-2">Tahun <?= esc($tahun) ?> — mengikuti filter aktif</small>
    <div style="height: 400px;">
        <canvas id="chartRealisasi"></canvas>
    </div>
</div>

<?php
  $grafikTarget = [];
  $grafikRealisasi = [];
  $grafikPersentase = [];

  for ($bulan = 1; $bulan <= 12; $bulan++) {
      $t = (float) ($dataBulanan[$bulan]['target'] ?? 0);
      $r = (float) ($dataBulanan[$bulan]['realisasi'] ?? 0);
      $grafikTarget[] = $t;
      $grafikRealisasi[] = $r;
      $grafikPersentase[] = $t > 0 ? ($r / $t) * 100 : null;
  }
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
      const canvas = document.getElementById('chartRealisasi');
      if (!canvas) return;

      const labels = [
          'Januari','Februari','Maret','April','Mei','Juni',
          'Juli','Agustus','September','Oktober','November','Desember'
      ];
      const target = <?= json_encode($grafikTarget) ?>;
      const realisasi = <?= json_encode($grafikRealisasi) ?>;
      const persentase = <?= json_encode($grafikPersentase) ?>;

      function formatRupiah(value) {
          return 'Rp ' + Number(value).toLocaleString('id-ID');
      }
      function formatSingkat(value) {
          value = Number(value);
          if (value >= 1000000000) return 'Rp ' + (value / 1000000000).toFixed(2) + ' M';
          if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(2) + ' Jt';
          if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + ' Rb';
          return 'Rp ' + value.toLocaleString('id-ID');
      }

      new Chart(canvas, {
          type: 'line',
          plugins: [ChartDataLabels],
          data: {
              labels: labels,
              datasets: [
                  {
                      label: 'Target',
                      data: target,
                      borderWidth: 3,
                      borderDash: [8, 5],
                      tension: 0.3,
                      fill: false,
                      pointRadius: 5,
                      datalabels: { display: false }
                  },
                  {
                      label: 'Realisasi',
                      data: realisasi,
                      borderWidth: 3,
                      tension: 0.3,
                      fill: false,
                      pointRadius: 5,
                      datalabels: {
                          display: function(ctx) {
                              const i = ctx.dataIndex;
                              return realisasi[i] > 0 && persentase[i] !== null;
                          },
                          formatter: function(value, ctx) {
                              return Number(persentase[ctx.dataIndex]).toFixed(1) + '%';
                          },
                          align: 'top',
                          anchor: 'center',
                          font: { weight: 'bold', size: 10 }
                      }
                  }
              ]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              layout: { padding: { top: 30, right: 20, left: 10, bottom: 10 } },
              interaction: { mode: 'index', intersect: false },
              plugins: {
                  legend: { display: true, position: 'bottom' },
                  tooltip: {
                      callbacks: {
                          label: function(ctx) {
                              return ctx.dataset.label + ': ' + formatRupiah(ctx.raw);
                          }
                      }
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true,
                      ticks: { callback: function(v) { return formatSingkat(v); } }
                  },
                  x: { ticks: { autoSkip: false } }
              }
          }
      });
  });
</script>
