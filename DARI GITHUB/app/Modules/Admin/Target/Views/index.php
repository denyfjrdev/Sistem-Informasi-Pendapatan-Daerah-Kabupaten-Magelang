<?= $this->extend('layouts/base') ?>

<?= $this->section('css') ?>
<link href="<?= base_url('skote/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('skote/assets/libs/daterangepicker/daterangepicker.css') ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('css/custom/daterangepicker.css') ?>" rel="stylesheet" type="text/css">
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
<?= $this->endSection() ?>


<?= $this->section('content') ?>


    <div class="container-fluid py-3">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-1 fw-bold">
                            Target Pajak
                        </h5>

                        <small class="text-muted">
                            Pengelolaan target pajak bulanan
                        </small>
                    </div>

                    <button
                        type="button"
                        class="btn btn-success"
                        id="btnTambah">

                        <i class="bi bi-plus-lg me-1"></i>
                        Tambah Target
                    </button>

                </div>

            </div>

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Tahun
                        </label>

                        <select
                            id="filterTahun"
                            class="form-select">

                            <?php for ($i = date('Y') + 1; $i >= date('Y') - 5; $i--): ?>

                                <option
                                    value="<?= $i ?>"
                                    <?= $i == date('Y') ? 'selected' : '' ?>>

                                    <?= $i ?>

                                </option>

                            <?php endfor ?>

                        </select>

                    </div>

                </div>

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle"
                        id="tableTarget">

                        <thead class="table-light">

                            <tr>
                                <th width="60">No</th>
                                <th>Jenis Pajak</th>
                                <th>Kode</th>
                                <th>Tahun</th>
                                <th class="text-end">
                                    Total Target
                                </th>
                                <th width="160">
                                    Aksi
                                </th>
                            </tr>

                        </thead>

                        <tbody id="tbodyTarget">

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
        MODAL FULLSCREEN
    ===================================================== -->

    <div
        class="modal fade"
        id="modalTarget"
        tabindex="-1"
        data-bs-backdrop="static"
        data-bs-keyboard="false">

        <div class="modal-dialog modal-fullscreen">

            <div class="modal-content">

                <div class="modal-header border-0 shadow-sm">

                    <div>

                        <h5
                            class="modal-title fw-bold"
                            id="modalTitle">

                            Tambah Target Pajak

                        </h5>

                        <small class="text-muted">
                            Masukkan target untuk Januari sampai Desember
                        </small>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body bg-light">

                    <!-- LOADING -->

                    <div
                        id="loadingForm"
                        class="text-center py-5 d-none">

                        <div
                            class="spinner-border text-success"
                            style="width:3rem;height:3rem;">
                        </div>

                        <div class="mt-3 text-muted">
                            Memuat data...
                        </div>

                    </div>


                    <div
                        id="formTargetWrapper"
                        class="container-fluid">

                        <form id="formTarget">

                            <input
                                type="hidden"
                                name="jenis_id"
                                id="jenis_id">

                            <input
                                type="hidden"
                                name="tahun"
                                id="tahun">


                            <div class="row mb-4">

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Jenis Pajak
                                    </label>

                                    <select
                                        class="form-select form-select-lg"
                                        id="selectJenis"
                                        name="jenis_id">

                                        <option value="">
                                            -- Pilih Jenis Pajak --
                                        </option>

                                        <?php foreach ($jenis_pajak as $jenis): ?>

                                            <option
                                                value="<?= $jenis['id'] ?>">

                                                <?= esc($jenis['nama_pajak']) ?>

                                            </option>

                                        <?php endforeach ?>

                                    </select>

                                </div>


                                <div class="col-md-3">

                                    <label class="form-label fw-semibold">
                                        Tahun
                                    </label>

                                    <select
                                        class="form-select form-select-lg"
                                        id="selectTahun"
                                        name="tahun">

                                        <?php for ($i = date('Y') + 1; $i >= date('Y') - 5; $i--): ?>

                                            <option
                                                value="<?= $i ?>"
                                                <?= $i == date('Y') ? 'selected' : '' ?>>

                                                <?= $i ?>

                                            </option>

                                        <?php endfor ?>

                                    </select>

                                </div>

                            </div>


                            <div class="card border-0 shadow-sm">

                                <div class="card-header bg-white">

                                    <h6 class="mb-0 fw-bold">
                                        Target Bulanan
                                    </h6>

                                </div>


                                <div class="card-body">

                                    <div class="row g-3">

                                        <?php

                                        $bulan = [
                                            1  => 'Januari',
                                            2  => 'Februari',
                                            3  => 'Maret',
                                            4  => 'April',
                                            5  => 'Mei',
                                            6  => 'Juni',
                                            7  => 'Juli',
                                            8  => 'Agustus',
                                            9  => 'September',
                                            10 => 'Oktober',
                                            11 => 'November',
                                            12 => 'Desember',
                                        ];

                                        ?>

                                        <?php foreach ($bulan as $no => $nama): ?>

                                            <div class="col-xl-3 col-lg-4 col-md-6">

                                                <div class="border rounded-3 p-3 bg-white">

                                                    <label
                                                        class="form-label fw-semibold">

                                                        <?= $nama ?>

                                                    </label>

                                                    <div class="input-group">

                                                        <span class="input-group-text">
                                                            Rp
                                                        </span>

                                                        <input
                                                            type="text"
                                                            class="form-control text-end input-target"
                                                            name="target[<?= $no ?>]"
                                                            id="target_<?= $no ?>"
                                                            value="0"
                                                            inputmode="numeric">

                                                    </div>

                                                </div>

                                            </div>

                                        <?php endforeach ?>

                                    </div>


                                    <div class="row mt-4">

                                        <div class="col-md-6 ms-auto">

                                            <div class="alert alert-success mb-0">

                                                <div class="d-flex justify-content-between">

                                                    <strong>
                                                        Total Target
                                                    </strong>

                                                    <strong
                                                        id="totalTarget">
                                                        Rp 0
                                                    </strong>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>


                <div class="modal-footer bg-white border-0 shadow-sm">

                    <button
                        type="button"
                        class="btn btn-light px-4"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="button"
                        class="btn btn-success px-5"
                        id="btnSimpan">

                        <span
                            class="spinner-border spinner-border-sm me-2 d-none"
                            id="spinnerSimpan">
                        </span>

                        <i
                            class="bi bi-save me-1"
                            id="iconSimpan">
                        </i>

                        <span id="textSimpan">
                            Simpan Target
                        </span>

                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
        LOADING DATA
    ===================================================== -->

    <div
        id="loadingData"
        class="position-fixed top-0 start-0 w-100 h-100 d-none"
        style="
            background:rgba(255,255,255,.75);
            z-index:9999;
        ">

        <div
            class="d-flex justify-content-center align-items-center h-100">

            <div class="text-center">

                <div
                    class="spinner-border text-success"
                    style="width:3rem;height:3rem;">
                </div>

                <div class="mt-3 fw-semibold">
                    Memuat data...
                </div>

            </div>

        </div>

    </div>    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>

      const modalElement = document.getElementById('modalTarget');

      const modalTarget = new bootstrap.Modal(modalElement);

      const urlData   = "<?= route_to('target.data') ?>";
      const urlGet    = "<?= route_to('target.get') ?>";
      const urlSave   = "<?= route_to('target.save') ?>";
      const urlDelete = "<?= route_to('target.delete') ?>";


      const namaBulan = [
          '',
          'Januari',
          'Februari',
          'Maret',
          'April',
          'Mei',
          'Juni',
          'Juli',
          'Agustus',
          'September',
          'Oktober',
          'November',
          'Desember'
      ];


      // ======================================================
      // FORMAT RUPIAH
      // ======================================================

      function formatRupiah(value)
      {
          value = String(value ?? '');

          value = value.replace(/\D/g, '');

          if (value === '') {
              return '0';
          }

          return new Intl.NumberFormat('id-ID').format(
              parseInt(value)
          );
      }


      // ======================================================
      // AMBIL ANGKA
      // ======================================================

      function angka(value)
      {
          return parseInt(
              String(value)
                  .replace(/\./g, '')
                  .replace(/,/g, '')
                  .replace(/\D/g, '')
          ) || 0;
      }


      // ======================================================
      // FORMAT INPUT
      // ======================================================

      document.querySelectorAll('.input-target').forEach(input => {

          input.addEventListener('input', function () {

              this.value = formatRupiah(this.value);

              hitungTotal();

          });

          input.addEventListener('focus', function () {

              this.select();

          });

      });


      // ======================================================
      // TOTAL
      // ======================================================

      function hitungTotal()
      {
          let total = 0;

          document.querySelectorAll('.input-target')
              .forEach(input => {

                  total += angka(input.value);

              });

          document.getElementById('totalTarget')
              .textContent =
              'Rp ' + formatRupiah(total);
      }


      // ======================================================
      // LOADING DATA
      // ======================================================

      function showLoadingData()
      {
          document
              .getElementById('loadingData')
              .classList.remove('d-none');
      }


      function hideLoadingData()
      {
          document
              .getElementById('loadingData')
              .classList.add('d-none');
      }


      // ======================================================
      // LOAD DATA
      // ======================================================

      async function loadData()
      {
          showLoadingData();

          const tahun =
              document.getElementById('filterTahun').value;

          try {

              const response = await fetch(
                  `${urlData}?tahun=${tahun}`
              );

              const result = await response.json();

              const tbody =
                  document.getElementById('tbodyTarget');

              tbody.innerHTML = '';

              if (
                  !result.status ||
                  result.data.length === 0
              ) {

                  tbody.innerHTML = `
                      <tr>
                          <td colspan="6"
                              class="text-center text-muted py-5">

                              <i class="bi bi-database-x fs-1 d-block mb-2"></i>

                              Belum ada data target

                          </td>
                      </tr>
                  `;

                  return;
              }


              // ==================================================
              // GROUP JENIS + TAHUN
              // ==================================================

              const grouped = {};

              result.data.forEach(row => {

                  const key =
                      `${row.jenis_id}_${row.tahun}`;

                  if (!grouped[key]) {

                      grouped[key] = {
                          jenis_id: row.jenis_id,
                          nama_pajak: row.nama_pajak,
                          kode: row.kode,
                          tahun: row.tahun,
                          total: 0
                      };

                  }

                  grouped[key].total +=
                      Number(row.target);

              });


              let no = 1;

              Object.values(grouped).forEach(row => {

                  tbody.innerHTML += `

                      <tr>

                          <td>
                              ${no++}
                          </td>

                          <td>
                              <div class="fw-semibold">
                                  ${escapeHtml(row.nama_pajak)}
                              </div>
                          </td>

                          <td>
                              <span class="badge text-bg-light">
                                  ${escapeHtml(row.kode ?? '-')}
                              </span>
                          </td>

                          <td>
                              ${row.tahun}
                          </td>

                          <td class="text-end fw-semibold">
                              Rp ${formatRupiah(row.total)}
                          </td>

                          <td>

                              <button
                                  class="btn btn-sm btn-outline-primary me-1"
                                  onclick="
                                      editTarget(
                                          ${row.jenis_id},
                                          ${row.tahun}
                                      )
                                  ">

                                  <i class="bi bi-pencil"></i>
                                  Edit

                              </button>

                              <button
                                  class="btn btn-sm btn-outline-danger"
                                  onclick="
                                      hapusTarget(
                                          ${row.jenis_id},
                                          ${row.tahun}
                                      )
                                  ">

                                  <i class="bi bi-trash"></i>
                                  Hapus

                              </button>

                          </td>

                      </tr>

                  `;

              });

          } catch (error) {

              console.error(error);

              alert('Gagal mengambil data.');

          } finally {

              hideLoadingData();

          }
      }


      // ======================================================
      // TAMBAH
      // ======================================================

      document
          .getElementById('btnTambah')
          .addEventListener(
              'click',
              function () {

                  resetForm();


                  document
                      .getElementById('modalTitle')
                      .textContent =
                      'Tambah Target Pajak';


                  modalTarget.show();

              }
          );

      // document
      //     .getElementById('btnTambah')
      //     .addEventListener('click', function () {

      //         resetForm();

      //         document.getElementById('modalTitle')
      //             .textContent =
      //             'Tambah Target Pajak';

      //         modalTarget.show();

      //     });


      // ======================================================
      // RESET FORM
      // ======================================================

      function resetForm()
      {
        document
            .getElementById('selectJenis')
            .value = '';


        document
            .getElementById('selectTahun')
            .value =
            document
                .getElementById('filterTahun')
                .value;


        kosongkanTarget();        
          // document
          //     .getElementById('selectJenis')
          //     .value = '';

          // document
          //     .getElementById('selectTahun')
          //     .value =
          //     document.getElementById('filterTahun').value;


          // for (let bulan = 1; bulan <= 12; bulan++) {

          //     document
          //         .getElementById(`target_${bulan}`)
          //         .value = '0';

          // }

          // hitungTotal();
      }


      // ======================================================
      // EDIT
      // ======================================================
      async function editTarget(
          jenisId,
          tahun
      )
      {
          document
              .getElementById('modalTitle')
              .textContent =
              'Edit Target Pajak';


          document
              .getElementById('selectJenis')
              .value = jenisId;


          document
              .getElementById('selectTahun')
              .value = tahun;


          /*
          * Buka modal
          */
          modalTarget.show();


          /*
          * Ambil data
          */
          await loadTargetForm();
      }

      // async function editTarget(jenisId, tahun)
      // {
      //     resetForm();

      //     document.getElementById('modalTitle')
      //         .textContent =
      //         'Edit Target Pajak';


      //     document
      //         .getElementById('loadingForm')
      //         .classList.remove('d-none');

      //     document
      //         .getElementById('formTargetWrapper')
      //         .classList.add('d-none');


      //     modalTarget.show();


      //     try {

      //         const response = await fetch(
      //             `${urlGet}?jenis_id=${jenisId}&tahun=${tahun}`
      //         );

      //         const result = await response.json();

      //         if (!result.status) {

      //             alert(result.message);

      //             return;
      //         }


      //         document
      //             .getElementById('selectJenis')
      //             .value = jenisId;

      //         document
      //             .getElementById('selectTahun')
      //             .value = tahun;


      //         Object.values(result.data)
      //             .forEach(row => {

      //                 document
      //                     .getElementById(
      //                         `target_${row.bulan}`
      //                     )
      //                     .value =
      //                     formatRupiah(row.target);

      //             });


      //         hitungTotal();


      //     } catch (error) {

      //         console.error(error);

      //         alert('Gagal mengambil data target.');

      //     } finally {

      //         document
      //             .getElementById('loadingForm')
      //             .classList.add('d-none');

      //         document
      //             .getElementById('formTargetWrapper')
      //             .classList.remove('d-none');

      //     }
      // }


      // ======================================================
      // SIMPAN
      // ======================================================

      document
          .getElementById('btnSimpan')
          .addEventListener('click', async function () {

              const jenisId =
                  document.getElementById('selectJenis').value;

              const tahun =
                  document.getElementById('selectTahun').value;


              if (!jenisId) {

                  alert('Silakan pilih jenis pajak.');

                  return;
              }


              // ----------------------------------------------
              // LOADING BUTTON
              // ----------------------------------------------

              const button = this;

              button.disabled = true;

              document
                  .getElementById('spinnerSimpan')
                  .classList.remove('d-none');

              document
                  .getElementById('iconSimpan')
                  .classList.add('d-none');

              document
                  .getElementById('textSimpan')
                  .textContent =
                  'Menyimpan...';


              const form =
                  document.getElementById('formTarget');

              const formData =
                  new FormData(form);


              // Bersihkan target agar server menerima angka
              for (let bulan = 1; bulan <= 12; bulan++) {

                  const input =
                      document.getElementById(`target_${bulan}`);

                  formData.set(
                      `target[${bulan}]`,
                      angka(input.value)
                  );

              }


              try {

                  const response = await fetch(
                      urlSave,
                      {
                          method: 'POST',
                          body: formData
                      }
                  );

                  const result =
                      await response.json();


                  if (!result.status) {

                      alert(
                          result.message ||
                          'Gagal menyimpan data.'
                      );

                      return;
                  }


                  modalTarget.hide();

                  await loadData();

                  // alert(
                  //     'Target pajak berhasil disimpan.'
                  // );
                  Swal.fire({
                      icon: 'success',
                      title: 'Berhasil',
                      text: 'Target pajak berhasil disimpan.',
                      confirmButtonText: 'OK'
                  });


              } catch (error) {

                  console.error(error);

                  alert(
                      'Terjadi kesalahan saat menyimpan data.'
                  );

              } finally {

                  button.disabled = false;

                  document
                      .getElementById('spinnerSimpan')
                      .classList.add('d-none');

                  document
                      .getElementById('iconSimpan')
                      .classList.remove('d-none');

                  document
                      .getElementById('textSimpan')
                      .textContent =
                      'Simpan Target';

              }

          });


      // ======================================================
      // HAPUS
      // ======================================================

      async function hapusTarget(jenisId, tahun)
      {
          if (
              !confirm(
                  `Hapus seluruh target tahun ${tahun} untuk jenis pajak ini?`
              )
          ) {
              return;
          }


          showLoadingData();


          const formData = new FormData();

          formData.append(
              'jenis_id',
              jenisId
          );

          formData.append(
              'tahun',
              tahun
          );


          try {

              const response = await fetch(
                  urlDelete,
                  {
                      method: 'POST',
                      body: formData
                  }
              );

              const result =
                  await response.json();


              if (!result.status) {

                  alert(result.message);

                  return;
              }


              await loadData();

              alert(
                  'Target berhasil dihapus.'
              );


          } catch (error) {

              console.error(error);

              alert(
                  'Gagal menghapus data.'
              );

          } finally {

              hideLoadingData();

          }
      }


      // ======================================================
      // FILTER TAHUN
      // ======================================================

      document
          .getElementById('filterTahun')
          .addEventListener(
              'change',
              loadData
          );


      // ======================================================
      // ESCAPE HTML
      // ======================================================

      function escapeHtml(value)
      {
          if (value === null || value === undefined) {
              return '';
          }

          return String(value)
              .replace(/&/g, '&amp;')
              .replace(/</g, '&lt;')
              .replace(/>/g, '&gt;')
              .replace(/"/g, '&quot;')
              .replace(/'/g, '&#039;');
      }


      // ======================================================
      // INITIAL LOAD
      // ======================================================

      document.addEventListener(
          'DOMContentLoaded',
          function () {

              loadData();

          }
      );


      /* =====================================================
        LOAD TARGET BERDASARKAN JENIS + TAHUN
      ===================================================== */

      async function loadTargetForm()
      {
          const jenisId =
              document.getElementById('selectJenis').value;

          const tahun =
              document.getElementById('selectTahun').value;


          /*
          * Kalau jenis belum dipilih,
          * kosongkan semua target
          */
          if (!jenisId) {

              kosongkanTarget();

              return;
          }


          /*
          * Loading
          */
          document
              .getElementById('loadingForm')
              .classList.remove('d-none');

          document
              .getElementById('formTargetWrapper')
              .classList.add('d-none');


          try {

              const response = await fetch(
                  `${urlGet}?jenis_id=${encodeURIComponent(jenisId)}&tahun=${encodeURIComponent(tahun)}`
              );


              if (!response.ok) {

                  throw new Error(
                      'Gagal mengambil data dari server.'
                  );

              }


              const result =
                  await response.json();


              if (!result.status) {

                  throw new Error(
                      result.message ||
                      'Gagal mengambil data target.'
                  );

              }


              /*
              * Kosongkan terlebih dahulu
              */
              kosongkanTarget();


              /*
              * Isi Januari - Desember
              */
              Object.values(result.data)
                  .forEach(row => {

                      const input =
                          document.getElementById(
                              `target_${row.bulan}`
                          );


                      if (input) {

                          input.value =
                              formatRupiah(
                                  row.target
                              );

                      }

                  });


              /*
              * Hitung total
              */
              hitungTotal();


          } catch (error) {

              console.error(error);


              Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: error.message
              });


          } finally {

              document
                  .getElementById('loadingForm')
                  .classList.add('d-none');

              document
                  .getElementById('formTargetWrapper')
                  .classList.remove('d-none');

          }
      } 
      
      /*=== kosongkan target=== */
      function kosongkanTarget()
      {
          for (
              let bulan = 1;
              bulan <= 12;
              bulan++
          ) {

              const input =
                  document.getElementById(
                      `target_${bulan}`
                  );


              if (input) {

                  input.value = '0';

              }

          }


          hitungTotal();
      }    
      
      document
          .getElementById('selectJenis')
          .addEventListener(
              'change',
              function () {

                  loadTargetForm();

              }
          );     
          
      document
          .getElementById('selectTahun')
          .addEventListener(
              'change',
              function () {

                  const jenisId =
                      document.getElementById(
                          'selectJenis'
                      ).value;


                  if (jenisId) {

                      loadTargetForm();

                  }

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