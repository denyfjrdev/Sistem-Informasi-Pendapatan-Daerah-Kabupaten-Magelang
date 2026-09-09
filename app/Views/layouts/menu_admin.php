<div id="navbar-menu" class="d-flex align-items-center gap-3 ms-3">

    <a href="<?= base_url('admin') ?>" class="text-dark text-decoration-none">
        <i class="bx bx-home-circle fs-4"></i>
        <span>Dashboard Admin</span>
    </a>

    
  <div class="dropdown d-inline-block hover-dropdown">
      <a href="#" class="text-dark text-decoration-none d-flex align-items-center gap-1">
        <i class="bx bx-bar-chart-alt-2 fs-4"></i>
          <span>Data</span>
          <!-- panah bawah -->
        <i class="bx bx-chevron-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item" href="<?= base_url('admin/target') ?>">
            <i class="bx bx-list me-2"></i> Target
          </a>
        </li>
      </ul>
  </div>

  <div class="dropdown d-inline-block hover-dropdown">
      <a href="#" class="text-dark text-decoration-none d-flex align-items-center gap-1">
        <i class="bx bx-detail fs-4"></i>
          <span>Detail</span>
          <!-- panah bawah -->
        <i class="bx bx-chevron-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item" href="<?= base_url('admin/detil/ketetapan') ?>">
            Ketetapan
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="<?= base_url('admin/detil/target') ?>">
            Target
          </a>
        </li>
        <li class="dropdown-submenu dropend">
          <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">
            Realisasi
          </a>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="<?= base_url('admin/detil/realisasi/kecamatan') ?>">
                Per Kecamatan
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="<?= base_url('admin/detil/realisasi/desa') ?>">
                Per Desa
              </a>
            </li>
          </ul>
        </li>
        <li>
          <a class="dropdown-item" href="<?= base_url('admin/detil/grafik') ?>">
            Grafik
          </a>
        </li>
      </ul>
  </div>

  <a href="<?= base_url('admin/informasi') ?>" class="text-dark text-decoration-none">
      <i class="bx bx-info-circle fs-4"></i>
      <span>Informasi</span>
  </a>

    <!-- <a href="<?= base_url('pengaturan') ?>" class="text-dark text-decoration-none">
        <i class="bx bx-cog fs-4"></i>
        <span>Pengaturan</span>
    </a> -->

</div>