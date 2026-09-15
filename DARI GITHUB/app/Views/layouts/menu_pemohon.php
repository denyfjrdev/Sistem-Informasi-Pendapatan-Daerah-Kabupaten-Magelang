<div id="navbar-menu" class="d-flex align-items-center gap-3 ms-3">

    <a href="<?= base_url('master/dashboard') ?>" class="text-dark text-decoration-none">
        <i class="bx bx-home-circle fs-4"></i>
        <span>Dashboard</span>
    </a>

    
  <div class="dropdown d-inline-block hover-dropdown">

      <a href="#" class="text-dark text-decoration-none d-flex align-items-center gap-1">

          <i class="bx bx-bar-chart-alt-2 fs-4"></i>
          <span>Setting</span>

          <!-- panah bawah -->
          <i class="bx bx-chevron-down"></i>

      </a>

      <ul class="dropdown-menu dropdown-menu-end">          
        <li>
          <a class="dropdown-item" href="<?= base_url('master/users') ?>">
            <i class="bx bx-user me-2"></i> Users
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="<?= base_url('master/tahapan') ?>">
            <i class="bx bx-table me-2"></i> Tahapan
          </a>
        </li>
      </ul>

  </div>               

    <a href="<?= base_url('pengaturan') ?>" class="text-dark text-decoration-none">
        <i class="bx bx-cog fs-4"></i>
        <span>Pengaturan</span>
    </a>

</div>
