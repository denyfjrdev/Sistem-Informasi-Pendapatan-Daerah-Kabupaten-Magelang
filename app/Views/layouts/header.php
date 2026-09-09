<header id="page-topbar">
    <div class="navbar-header d-flex justify-content-between align-items-center">

        <!-- LEFT -->
        <div class="d-flex align-items-center">

            <!-- LOGO -->
            <div class="navbar-brand-box" style="background-color: #FAFAFA;">
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?= base_url('skote/assets/images/mgl2.png') ?>" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url('skote/assets/images/mgl2.png') ?>" height="45">
                    </span>
                </a>
            </div>

            <!-- HAMBURGER -->
            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <!-- <div id="navbar-menu" class="d-flex align-items-center ms-2"></div> -->
            
            <!---MENU ATAS-->
            <?php 
              if(isset($data_user->role)){
                if($data_user->role == 'sijaka_master'){
                  echo $this->include('layouts/menu_master');
                }
                if($data_user->role == 'sijaka_admin'){
                  echo $this->include('layouts/menu_admin');
                }
                if($data_user->role == 'sijaka_pimpinan'){
                  echo $this->include('layouts/menu_pimpinan');
                }                
              }
            ?>
            

        </div>

        <!-- RIGHT -->
        <div class="d-flex align-items-center">

            <!-- DESKTOP ACTION -->
            <div class="d-none d-xl-flex gap-2">

                <!-- <a href="<?= base_url(); ?>" class="btn btn-primary">
                    Beranda Magelang
                </a> -->

                <div class="dropdown ">
                    <button class="d-flex align-items-center btn header-item waves-effect" data-bs-toggle="dropdown">
                        <span class="username-info fw-bold"></span>
                        &nbsp;&nbsp;<i class="mdi mdi-account-circle-outline font-size-20"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- <a class="dropdown-item" href="/">
                            <i class="bx bx-home me-2"></i> Beranda Magelang
                        </a>

                        <div class="dropdown-divider"></div> -->

                        <!-- <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-reset-paswd"><i class="bx bx-key me-2"></i> Reset Password</button> -->
                        <button onclick="window.location.href='<?= base_url('auth/logout') ?>'"
                              class="logout-btn dropdown-item text-danger">
                          <i class="bx bx-log-out me-2"></i> Keluar
                      </button>
                    </div>
                </div>
            </div>

            <!-- MOBILE MENU -->
            <!-- MOBILE ALL-IN-ONE MENU -->
            <div class="dropdown d-xl-none">
                <button class="d-flex align-items-center btn header-item waves-effect" data-bs-toggle="dropdown">
                    <span class="username-info fw-bold"></span>
                    &nbsp;&nbsp;<i class="mdi mdi-account-circle-outline font-size-20"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="/">
                        <i class="bx bx-home me-2"></i> Beranda Magelang
                    </a>

                    <div class="dropdown-divider"></div>

                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-reset-paswd"><i class="bx bx-key me-2"></i> Reset Password</button>
                    <button class="logout-btn dropdown-item text-danger"><i class="bx bx-log-out me-2"></i> Keluar</button>
                </div>
            </div>
        </div>

    </div>
</header>
