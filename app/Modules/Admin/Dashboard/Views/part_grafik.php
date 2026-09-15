<div class="col-md-5">
  <div class="dashboard-card h-100">
      <div class="card-body">
          <h5 class="card-title text-white p-2" style="background-color: #288052;">
              Persentase Realisasi Pajak
          </h5>
          <div style="height: 400px;">
              <canvas id="grafikPersentasePajak"></canvas>
          </div>
      </div>
  </div>
</div>

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
              legend: { display: true },
              datalabels: {
                  anchor: 'end',
                  align: 'right',
                  formatter: function(value) {
                      return Number(value).toFixed(1) + '%';
                  },
                  font: { weight: 'bold', size: 12 }
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
      plugins: [ ChartDataLabels ]
  });
</script>
