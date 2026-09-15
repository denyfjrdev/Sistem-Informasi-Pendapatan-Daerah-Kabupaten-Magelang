<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?=env('NAMA_APLIKASI')?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?= base_url('skote/assets/images/mgl2.png') ?>?v=2">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>?v=2">
    <link rel="apple-touch-icon" href="<?= base_url('skote/assets/images/mgl2.png') ?>?v=2">

    <link href="<?= base_url('skote/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('skote/assets/css/icons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('skote/assets/css/app.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="<?= base_url('skote/assets/libs/select2/css/select2.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('skote/plugins/sweetalert2/sweetalert2.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('skote/assets/css/custom.css') ?>?v=<?= @filemtime(FCPATH . 'skote/assets/css/custom.css') ?>" rel="stylesheet">
    <link href="<?= base_url('skote/assets/libs/metismenu/metisMenu.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('skote/assets/libs/simplebar/simplebar.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('skote/assets/libs/node-waves/waves.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="<?= base_url('skote/assets/libs/jquery/jquery.min.js') ?>"></script>

    <style>
        .otp-input {
            width: 50px;
            height: 50px;
            font-size: 22px;
            font-weight: 600;
        }

        .otp-input:focus {
            border-color: #556ee6;
            box-shadow: 0 0 0 .15rem rgba(85, 110, 230, .25);
        }

        .vertical-menu {
            z-index: 1020;
        }

        .main-content {
            padding-top: 70px;
            overflow: hidden;
        }

        #page-topbar {
            z-index: 1030;
        }

        #navbar-menu {
            display: flex;
            align-items: center;
            height: 100%;
        }

        #navbar-menu > * {
            margin-right: 4px;
        }

        #navbar-menu .top-nav-btn {
            height: 38px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0 12px;
            border: 0;
            background: transparent;
            color: #495057;
            font-size: 14px;
            border-radius: 4px;
            text-decoration: none;
        }

        #navbar-menu .top-nav-btn:hover,
        #navbar-menu .top-nav-btn.active {
            background-color: #f5f6f8;
            color: #556ee6;
        }

        #navbar-menu .top-nav-btn i {
            font-size: 17px;
        }

        #navbar-menu .dropdown-toggle::after {
            margin-left: 5px;
        }

        #navbar-menu .dropdown-menu {
            margin-top: 8px;
            border: 0;
            box-shadow: 0 5px 20px rgba(0,0,0,.12);
            border-radius: 5px;
        }

        #navbar-menu .dropdown-item {
            font-size: 14px;
            padding: 8px 15px;
        }

        #navbar-menu .dropdown-item:hover {
            background-color: #f8f9fa;
        }
    </style>

    <?= $this->renderSection('css') ?>
    <?php helper('campuran'); ?>
</head>

<body data-sidebar="light">
    <?php
        $profil = layout_user_profile($data_user ?? null);
        $data_user      = $profil['data_user'];
        $namaTampil     = $profil['namaTampil'];
        $emailTampil    = $profil['emailTampil'];
        $emailGovTampil = $profil['emailGovTampil'];
    ?>
    <div id="layout-wrapper">

        <?= $this->include('layouts/header'); ?>
        <?= $this->include('layouts/sidebar'); ?>

        <div class="main-content">
            <div style="margin: 0; padding: 20px;" class="position-relative overflow-hidden card-body-brown">
                <img src="<?= base_url('skote/assets/images/motif-header.svg') ?>" class="header-bg" alt="">
                <div>
                    <h4 style="color: #288052; font-size: 20px; font-weight: 600; margin: 0; letter-spacing: 0.1rem;"><?= $menu ?? '' ?></h4>
                    <h2 style="color: #000000; font-size: 30px; font-weight: 600; margin: 0; text-wrap: wrap; width: 400px;"><?= $fiture ?? '' ?></h2>
                </div>
            </div>
            <?= $this->renderSection('content') ?>
        </div>

    </div>

    <script src="<?= base_url('skote/assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('skote/assets/libs/metismenu/metisMenu.min.js') ?>"></script>
    <script src="<?= base_url('skote/assets/libs/simplebar/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('skote/assets/libs/node-waves/waves.min.js') ?>"></script>
    <script src="<?= base_url('skote/assets/js/app.js') ?>"></script>
    <script src="<?= base_url('skote/assets/libs/select2/js/select2.min.js') ?>"></script>
    <script src="<?= base_url('skote/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>

    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2').select2();
            }
        });
    </script>

    <?php /*
    Nonaktif: modal informasi profil. Aktifkan kembali bersama dropdown profil di header.php.
    <div class="modal fade" id="modalProfile" tabindex="-1" aria-labelledby="modalProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalProfileLabel">
                        <i class="fas fa-user-circle me-2"></i>
                        Profile User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-5x text-secondary"></i>
                    </div>
                    <div class="mb-2 row">
                        <label class="col-md-4 col-form-label text-muted text-end">Nama :</label>
                        <div class="col-md-8 col-form-label fw-bold"><?= esc($namaTampil ?? '') ?></div>
                        <label class="col-md-4 col-form-label text-muted text-end">Role Aplikasi :</label>
                        <div class="col-md-8 col-form-label fw-bold"><?= esc($data_user->role ?? '') ?></div>
                        <label class="col-md-4 col-form-label text-muted text-end">Email :</label>
                        <div class="col-md-8 col-form-label fw-bold"><?= esc($emailTampil ?? '') ?></div>
                        <label class="col-md-4 col-form-label text-muted text-end">Email Gov :</label>
                        <div class="col-md-8 col-form-label fw-bold"><?= esc($emailGovTampil ?? '') ?></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    */ ?>

    <?php
        if (session()->has('parent_url')) {
            $parent_url = session()->get('parent_url');
        } else {
            $parent_url = 'https://magelangkab.go.id';
        }
    ?>

    <script>
        function keluarFrame() {
            let parentUrl = <?= json_encode($parent_url) ?>;
            if (!parentUrl) {
                window.top.location.href = '/';
                return;
            }
            let urlBaru = parentUrl.replace(/\/iframe\/?$/, '');
            window.top.location.href = urlBaru || '/';
        }
    </script>

</body>
</html>
