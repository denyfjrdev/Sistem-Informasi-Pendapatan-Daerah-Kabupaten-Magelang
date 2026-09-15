<?= $this->extend('layouts/base') ?>

<?= $this->section('css') ?>
<style>
    .card{
        border-radius:8px;
        border:1px solid #cfcfcf;
    }

    .table{
        margin-bottom:0;
        font-size:14px;
    }

    .table-header-green th{
        background-color:#288052;
        color:#fff;
        text-align:center;
        vertical-align:middle;
    }

    .table tbody td{
        padding:7px 8px;
        vertical-align:middle;
    }
</style>
<?= $this->endSection() ?>

<?php
  $array_per_jenis_pajak = [];
  foreach($realisasi['target'] as $val){
    $array_per_jenis_pajak[$val['jenis_id']]["nama_pajak"] = $val['nama_pajak'];
  }
?>

<?= $this->section('content') ?>

<div class="container-fluid">

<div class="row">

    <!-- ================= TABEL KETETAPAN ================= -->

    <div class="col-lg-12">
      <div class="card">
        <table class="table table-hover table-bordered align-middle">
          <thead class="table-header-green">
            <tr>
              <th>No</th>
              <th>Nama Jenis Pajak</th>
              <th>Ketetapan</th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; foreach($array_per_jenis_pajak as $val){ ?>
            <tr>
              <td><?=$no++?></td>
              <td><?=$val['nama_pajak']?></td>
              <td>...</td>
            </tr>
            <?php } ?>
            <tr>
              <td colspan="2" align="right" class="fw-bold fs-5">Total</td>
              <td>...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

</div>

</div>

<?= $this->endSection() ?>