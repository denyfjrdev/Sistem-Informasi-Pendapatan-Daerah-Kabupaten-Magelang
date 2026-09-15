<!---- SUMMARY: Target / Realisasi / Persentase --->
<style>
  .detil-kpi-card{
    border:2px solid #28a745 !important;
    border-radius:10px;
    background:#fff;
  }
</style>
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card detil-kpi-card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Target Tahun <?= esc($tahun) ?></div>
                <div class="fs-5 fw-bold mt-1">
                    Rp <?= number_format((float) $total['total_target'], 0, ',', '.') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card detil-kpi-card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Realisasi Tahun <?= esc($tahun) ?></div>
                <div class="fs-5 fw-bold text-success mt-1">
                    Rp <?= number_format((float) $total['total_realisasi'], 0, ',', '.') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card detil-kpi-card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Persentase Realisasi</div>
                <div class="fs-5 fw-bold mt-1">
                    <?= number_format((float) $total['persentase'], 1, ',', '.') ?>%
                </div>
                <div class="progress mt-2" style="height: 7px;">
                    <?php $progress = min(100, max(0, (float) $total['persentase'])); ?>
                    <div class="progress-bar <?= $total['persentase'] >= 100 ? 'bg-success' : 'bg-primary' ?>"
                         style="width: <?= $progress ?>%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
