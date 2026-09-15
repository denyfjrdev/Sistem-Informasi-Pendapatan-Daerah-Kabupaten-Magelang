<?= $this->extend('layouts/base') ?>

<?= $this->section('css') ?>
<link href="<?= base_url('skote/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('skote/assets/libs/daterangepicker/daterangepicker.css') ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('css/custom/daterangepicker.css') ?>" rel="stylesheet" type="text/css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">
        konten
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('skote/assets/libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/daterangepicker/moment-with-locales.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/daterangepicker/daterangepicker.js') ?>"></script>
<?= $this->endSection() ?>
