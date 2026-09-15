<div class="col-md-7">
  <div class="dashboard-card h-100">
    <table class="table table-hover table-sm table-bordered align-middle mb-0">
      <thead class="table-header-green">
          <th>No</th>
          <th>Jenis Pajak</th>
          <th>Target</th>
          <th>Realisasi</th>
          <!-- <th>Realisasi_Piutang</th> pending dulu -->
          <th>Persentase</th>
      </thead>
      <tbody>
        <?php $no=1; foreach($array_per_jenis_pajak as $index=>$val){ ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= esc($val['nama_pajak']) ?></td>
            <td align="right"><?= number_format($val['target'] ?? 0, 0, ',', '.') ?></td>
            <td align="right"><?= number_format($val['realisasi'] ?? 0, 0, ',', '.') ?></td>
            <!-- <td align="right">-</td> kolom Realisasi_Piutang pending dulu -->
            <td align="right"><?= number_format($val['persen'] ?? 0, 1, ',', '.') ?> %</td>
          </tr>
        <?php } ?>

        <tr class="row-total">
          <td></td>
          <td class="fw-bold">Total</td>
          <td align="right">
              <?= number_format($semua_target_tahun ?? 0, 0, ',', '.') ?>
          </td>
          <td align="right">
              <?= number_format($semua_realisasi_tahun ?? 0, 0, ',', '.') ?>
          </td>
          <!-- <td align="right">-</td> kolom Realisasi_Piutang pending dulu -->
          <td align="right">
            <?= number_format($total_persentase_tahun ?? 0, 1, ',', '.') ?> %
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
