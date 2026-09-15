<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
  <div class="dashboard-card">
    <h5 class="fw-bold mb-3">Sinkron API Pajak</h5>
    <p class="text-muted">Load otomatis dimatikan. Data hanya masuk jika dipanggil manual.</p>
    <div class="mb-0">
      Mode: <strong><?= esc($status['mode'] ?? '-') ?></strong><br>
      Selesai: <?= (int) ($status['done'] ?? 0) ?>
      · Sisa antrian: <?= (int) ($status['remaining'] ?? 0) ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
