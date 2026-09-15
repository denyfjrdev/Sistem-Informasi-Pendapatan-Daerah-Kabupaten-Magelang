<?php
    helper('campuran');
    $profil = layout_user_profile($data_user ?? null);
    $data_user      = $profil['data_user'];
    $namaTampil     = $profil['namaTampil'];
    $emailTampil    = $profil['emailTampil'];
    $emailGovTampil = $profil['emailGovTampil'];
?>
<header id="page-topbar">
    <div class="navbar-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <div class="navbar-brand-box" style="background-color: #FAFAFA;">
                <a href="<?= base_url('/') ?>" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?= base_url('skote/assets/images/mgl2.png') ?>" height="30" alt="Logo">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url('skote/assets/images/mgl2.png') ?>" height="45" alt="Logo">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <div id="navbar-menu" class="d-flex align-items-center ms-3">
                <?php
                if (isset($data_user->role)) {
                    if ($data_user->role == 'sijaka_master') {
                        echo $this->include('layouts/menu_master');
                    }
                    if ($data_user->role == 'sijaka_admin') {
                        echo $this->include('layouts/menu_admin');
                    }
                    if ($data_user->role == 'sijaka_pimpinan') {
                        echo $this->include('layouts/menu_pimpinan');
                    }
                }
                ?>
            </div>
        </div>

        <?php if (isset($data_user->role)) { ?>
        <div class="d-flex align-items-center">
            <!-- Nama/role tetap tampil. Klik tidak membuka informasi. -->
            <button type="button" class="d-flex align-items-center btn header-item waves-effect">
                <div class="text-end">
                    <div class="username-info fw-bold"><?= esc($namaTampil ?? '') ?></div>
                    <div class="small text-muted">
                        <i class="bx bx-shield-quarter me-1"></i>
                        <?= esc($data_user->role) ?>
                    </div>
                </div>
                <i class="mdi mdi-account-circle-outline font-size-20 ms-2"></i>
            </button>

            <?php /*
            Nonaktif: klik profil menampilkan informasi (dropdown + Lihat profil).
            Aktifkan kembali: uncomment blok ini, comment tombol profil di atas,
            dan uncomment #modalProfile di base.php.
            <div class="dropdown">
                <button type="button" class="d-flex align-items-center btn header-item waves-effect dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end">
                        <div class="username-info fw-bold"><?= esc($namaTampil ?? '') ?></div>
                        <div class="small text-muted">
                            <i class="bx bx-shield-quarter me-1"></i>
                            <?= esc($data_user->role) ?>
                        </div>
                    </div>
                    <i class="mdi mdi-account-circle-outline font-size-20 ms-2"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 260px;">
                    <div class="mb-2">
                        <div class="text-muted small">Nama akun</div>
                        <div class="fw-bold"><?= esc($namaTampil ?? '-') ?></div>
                    </div>
                    <div>
                        <div class="text-muted small">Email</div>
                        <div class="fw-bold"><?= esc(!empty($emailTampil) ? $emailTampil : '-') ?></div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item px-0" href="#" data-bs-toggle="modal" data-bs-target="#modalProfile">
                        <i class="fas fa-id-card me-2"></i>Lihat profil
                    </a>
                    <button onclick="window.location.href='<?= base_url('auth/logout') ?>'" class="logout-btn dropdown-item text-danger px-0">
                        <i class="bx bx-log-out me-2"></i> Keluar
                    </button>
                </div>
            </div>
            */ ?>
        </div>
        <?php } ?>

    </div>
</header>
