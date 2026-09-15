<?= $this->extend('layouts/base') ?>

<?= $this->section('css') ?>
<link href="<?= base_url('skote/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('skote/assets/libs/daterangepicker/daterangepicker.css') ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('css/custom/daterangepicker.css') ?>" rel="stylesheet" type="text/css">
<?= $this->endSection() ?>


<?= $this->section('content') ?>

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    Manajemen User
                </h4>

                <small class="text-muted">
                    Kelola pengguna aplikasi
                </small>
            </div>

            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#modalUser"
                onclick="tambahUser()">

                + Tambah User

            </button>

        </div>


        <?php if (session()->getFlashdata('success')): ?>

            <div class="alert alert-success">

                <?= session()->getFlashdata('success') ?>

            </div>

        <?php endif; ?>

        <?php if ($errors = session()->getFlashdata('errors')): ?>

            <div class="alert alert-danger alert-dismissible fade show shadow-sm">

                <div class="fw-bold mb-2">
                    ⚠️ Data belum dapat disimpan
                </div>

                <ul class="mb-0">

                    <?php foreach ($errors as $error): ?>

                        <li>
                            <?= esc($error) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        <?php endif; ?>        



        <!-- SEARCH -->

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <form
                    method="get"
                    action="<?= route_to('users.index') ?>">

                    <div class="row g-2">

                        <div class="col-md-10">

                            <input
                                type="text"
                                name="keyword"
                                class="form-control"
                                placeholder="Cari nama, nomor HP atau email..."
                                value="<?= esc($keyword) ?>">

                        </div>

                        <div class="col-md-2">

                            <button
                                class="btn btn-success w-100">

                                🔍 Cari

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        <!-- TABLE -->

        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="60">
                                    #
                                </th>

                                <th>
                                    User
                                </th>

                                <th>
                                    No HP
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Role
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Last Login
                                </th>

                                <th width="150">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php if (!empty($users)): ?>

                            <?php
                            $no = 1 +
                                (($pager->getCurrentPage() - 1)
                                * 10);
                            ?>

                            <?php foreach ($users as $user): ?>

                                <tr>

                                    <td>
                                        <?= $no++ ?>
                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            <?= esc(
                                                $user['nama_user']
                                            ) ?>

                                        </div>

                                        <small
                                            class="text-muted">

                                            UUID:
                                            <?= esc(
                                                $user['uuid']
                                            ) ?>

                                        </small>

                                    </td>


                                    <td>

                                        <?= esc(
                                            $user['nohp']
                                            ?? '-'
                                        ) ?>

                                    </td>


                                    <td>

                                        <?= esc(
                                            $user['email']
                                            ?? '-'
                                        ) ?>

                                    </td>


                                    <td>

                                        <span
                                            class="badge bg-primary">

                                            <?= esc(
                                                $user['role']
                                            ) ?>

                                        </span>

                                    </td>


                                    <td>

                                        <?php if (
                                            $user['aktif']
                                            === 'ya'
                                        ): ?>

                                            <span
                                                class="badge bg-success">

                                                Aktif

                                            </span>

                                        <?php else: ?>

                                            <span
                                                class="badge bg-secondary">

                                                Tidak Aktif

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <?= $user['last_login']
                                            ? date(
                                                'd-m-Y H:i',
                                                strtotime(
                                                    $user['last_login']
                                                )
                                            )
                                            : '-' ?>

                                    </td>


                                    <td>

                                        <div class="d-flex gap-1">

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalUser"
                                                onclick='editUser(<?= json_encode($user) ?>)'>

                                                Edit

                                            </button>


                                            <form
                                                method="post"
                                                action="<?= route_to(
                                                    'users.delete',
                                                    $user['id']
                                                ) ?>"
                                                onsubmit="
                                                    return confirm(
                                                        'Yakin ingin menghapus user ini?'
                                                    );
                                                ">

                                                <?= csrf_field() ?>

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-danger">

                                                    Hapus

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5">

                                    Data user tidak ditemukan.

                                </td>

                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- PAGINATION -->

        <div class="mt-4">

            <?php //= $pager->links() ?>
          <div class="pagination-wrapper">

              <div class="pagination-info">

                  Halaman

                  <strong>
                      <?= $pager->getCurrentPage() ?>
                  </strong>

                  dari

                  <strong>
                      <?= $pager->getPageCount() ?>
                  </strong>

              </div>


              <nav aria-label="Pagination">

                  <ul class="pagination custom-pagination mb-0">

                      <?= $pager->links(
                          'default',
                          'custom_pager'
                      ) ?>

                  </ul>

              </nav>

          </div>            

        </div>

    </div>


<!-- =====================================================
     MODAL USER FULLSCREEN
====================================================== -->

<div
    class="modal fade"
    id="modalUser"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content modal-user-content">

            <!-- HEADER -->

            <div class="modal-header modal-user-header">

                <div>

                    <h5
                        class="modal-title mb-1"
                        id="modalUserTitle">

                        Tambah User

                    </h5>

                    <small
                        class="text-muted"
                        id="modalUserSubtitle">

                        Tambahkan pengguna baru

                    </small>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>


            <!-- BODY -->

            <div class="modal-body modal-user-body">

                <div class="container">

                    <div class="row justify-content-center">

                        <div class="col-xl-9 col-lg-10">


                            <!-- FORM -->

                            <form
                                id="formUser"
                                method="post"
                                action="<?= route_to(
                                    'users.store'
                                ) ?>">

                                <?= csrf_field() ?>

                                <input
                                    type="hidden"
                                    name="id"
                                    id="user_id">


                                <!-- INFORMASI USER -->

                                <div class="user-section">

                                    <div class="user-section-title">

                                        <span class="section-icon">
                                            👤
                                        </span>

                                        Informasi User

                                    </div>


                                    <div class="row g-4">


                                        <!-- NAMA -->

                                        <div class="col-md-6">

                                            <label
                                                class="form-label">

                                                Nama User

                                                <span class="text-danger">
                                                    *
                                                </span>

                                            </label>

                                            <input
                                                type="text"
                                                name="nama_user"
                                                id="nama_user"
                                                class="form-control form-control-lg"
                                                placeholder="Masukkan nama user"
                                                required>

                                        </div>


                                        <!-- NO HP -->

                                        <div class="col-md-6">

                                            <label
                                                class="form-label">

                                                Nomor HP

                                            </label>

                                            <input
                                                type="text"
                                                name="nohp"
                                                id="nohp"
                                                class="form-control form-control-lg"
                                                placeholder="Masukkan nomor HP">

                                        </div>


                                        <!-- EMAIL -->

                                        <div class="col-md-6">

                                            <label
                                                class="form-label">

                                                Email

                                            </label>

                                            <input
                                                type="email"
                                                name="email"
                                                id="email"
                                                class="form-control form-control-lg"
                                                placeholder="nama@email.com">

                                        </div>


                                        <!-- EMAIL GOV -->

                                        <div class="col-md-6">

                                            <label
                                                class="form-label">

                                                Email Government

                                            </label>

                                            <input
                                                type="email"
                                                name="email_gov"
                                                id="email_gov"
                                                class="form-control form-control-lg"
                                                placeholder="nama@go.id">

                                        </div>

                                    </div>

                                </div>


                                <!-- AKSES -->

                                <div class="user-section">

                                    <div class="user-section-title">

                                        <span class="section-icon">
                                            🔐
                                        </span>

                                        Hak Akses

                                    </div>


                                    <div class="row g-4">


                                        <!-- ROLE -->

                                        <div class="col-md-6">

                                            <label
                                                class="form-label">

                                                Role

                                            </label>

                                            <select
                                                name="role"
                                                id="role"
                                                class="form-select form-select-lg">

                                                <option
                                                    value="sijaka_admin">
                                                    Admin
                                                </option>

                                                <option
                                                    value="sijaka_pimpinan">
                                                    Pimpinan
                                                </option>

                                            </select>

                                        </div>


                                        <!-- STATUS -->

                                        <div class="col-md-6">

                                            <label
                                                class="form-label">

                                                Status User

                                            </label>

                                            <select
                                                name="aktif"
                                                id="aktif"
                                                class="form-select form-select-lg">

                                                <option value="ya">

                                                    Aktif

                                                </option>

                                                <option value="tidak">

                                                    Tidak Aktif

                                                </option>

                                            </select>

                                        </div>

                                    </div>

                                </div>


                                <!-- KETERANGAN -->

                                <div class="user-section">

                                    <div class="user-section-title">

                                        <span class="section-icon">
                                            📝
                                        </span>

                                        Keterangan

                                    </div>


                                    <textarea
                                        name="keterangan"
                                        id="keterangan"
                                        rows="6"
                                        class="form-control"
                                        placeholder="Tambahkan keterangan user jika diperlukan..."></textarea>

                                </div>


                                <!-- UUID  HIDDEN-->

                                <div
                                    class="user-info-box"
                                    id="uuidContainer"
                                    style="display:none;">

                                    <div class="small text-muted mb-1">

                                        UUID User

                                    </div>

                                    <div
                                        class="font-monospace"
                                        id="uuid">

                                    </div>

                                </div>


                            </form>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FOOTER -->

            <div class="modal-footer modal-user-footer">

                <button
                    type="button"
                    class="btn btn-light btn-lg px-4"
                    data-bs-dismiss="modal">

                    Batal

                </button>

                <button
                    type="button"
                    class="btn btn-success btn-lg px-5"
                    onclick="simpanUser()">

                    <span id="btnUserText">
                        Simpan User
                    </span>

                </button>

            </div>

        </div>

    </div>

</div>    

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>    

<script>

  const modalUser =
      document.getElementById('modalUser');

  const formUser =
      document.getElementById('formUser');

  const modalUserTitle =
      document.getElementById('modalUserTitle');

  const modalUserSubtitle =
      document.getElementById('modalUserSubtitle');

  const btnUserText =
      document.getElementById('btnUserText');


  /**
   * =====================================================
   * TAMBAH USER
   * =====================================================
   */
  function tambahUser()
  {
      // reset form
      formUser.reset();

      // mode tambah
      formUser.action =
          "<?= route_to('users.store') ?>";


      // title
      modalUserTitle.textContent =
          'Tambah User';

      modalUserSubtitle.textContent =
          'Tambahkan pengguna baru';


      btnUserText.textContent =
          'Simpan User';


      // default
      document.getElementById('aktif').value =
          'ya';

      document.getElementById('role').value =
          'sijaka_admin';


      // hide UUID
      document.getElementById(
          'uuidContainer'
      ).style.display = 'none';

  }


  /**
   * =====================================================
   * EDIT USER
   * =====================================================
   */
  function editUser(user)
  {    
      // mode edit
      formUser.action =
          "<?= base_url('master/users/update') ?>"
          + "/"
          + user.id;


      // title
      modalUserTitle.textContent =
          'Edit User';

      modalUserSubtitle.textContent =
          'Perbarui informasi pengguna';


      btnUserText.textContent =
          'Simpan Perubahan';


      // isi form
      document.getElementById(
          'user_id'
      ).value = user.id;


      document.getElementById(
          'nama_user'
      ).value = user.nama_user ?? '';


      document.getElementById(
          'nohp'
      ).value = user.nohp ?? '';


      document.getElementById(
          'email'
      ).value = user.email ?? '';


      document.getElementById(
          'email_gov'
      ).value = user.email_gov ?? '';


      document.getElementById(
          'role'
      ).value = user.role ?? 'sijaka_admin';


      document.getElementById(
          'aktif'
      ).value = user.aktif ?? 'ya';


      document.getElementById(
          'keterangan'
      ).value = user.keterangan ?? '';


      // UUID
      document.getElementById(
          'uuid'
      ).textContent = user.uuid ?? '-';


      document.getElementById(
          'uuidContainer'
      ).style.display = 'block';

  }


  /**
   * =====================================================
   * SIMPAN
   * =====================================================
   */
  function simpanUser()
  {
      const nama =
          document.getElementById(
              'nama_user'
          ).value.trim();


      if (!nama) {

          alert(
              'Nama user wajib diisi.'
          );

          document.getElementById(
              'nama_user'
          ).focus();

          return;

      }


      formUser.submit();
  }


  /**
   * =====================================================
   * RESET KETIKA MODAL DITUTUP
   * =====================================================
   */
  modalUser.addEventListener(
      'hidden.bs.modal',
      function () {

          formUser.reset();

          document.getElementById(
              'uuidContainer'
          ).style.display = 'none';

      }
  );

</script>    

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('skote/assets/libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/daterangepicker/moment-with-locales.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/daterangepicker/daterangepicker.js') ?>"></script>
<?= $this->endSection() ?>


