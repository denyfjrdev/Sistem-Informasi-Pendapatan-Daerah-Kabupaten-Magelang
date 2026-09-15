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

        <!-- HEADER -->
        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-1 fw-bold">
                        Ketetapan Pajak
                    </h5>

                    <small class="text-muted">
                        Pengelolaan ketetapan pajak bulanan
                    </small>
                </div>

                <button
                    type="button"
                    class="btn btn-success"
                    id="btnTambah">

                    <i class="bi bi-plus-lg me-1"></i>

                    Tambah Ketetapan

                </button>

            </div>

        </div>


        <!-- BODY -->
        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Tahun
                    </label>

                    <select
                        id="filterTahun"
                        class="form-select">

                        <?php
                        for (
                            $i = date('Y') + 1;
                            $i >= date('Y') - 5;
                            $i--
                        ):
                        ?>

                            <option
                                value="<?= $i ?>"
                                <?= $i == date('Y')
                                    ? 'selected'
                                    : '' ?>>

                                <?= $i ?>

                            </option>

                        <?php endfor ?>

                    </select>

                </div>

            </div>


            <!-- TABLE -->

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle"
                    id="tableKetetapan">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                Jenis Pajak
                            </th>

                            <th width="120">
                                Kode
                            </th>

                            <th width="100">
                                Tahun
                            </th>

                            <th
                                width="220"
                                class="text-end">

                                Total Ketetapan

                            </th>

                            <th width="190">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody id="tbodyKetetapan">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- =====================================================
     MODAL FULLSCREEN
====================================================== -->

<div
    class="modal fade"
    id="modalKetetapan"
    tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false">

    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content">


            <!-- HEADER -->

            <div class="modal-header border-0 shadow-sm">

                <div>

                    <h5
                        class="modal-title fw-bold"
                        id="modalTitle">

                        Tambah Ketetapan Pajak

                    </h5>

                    <small class="text-muted">

                        Masukkan ketetapan Januari
                        sampai Desember

                    </small>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <!-- BODY -->

            <div class="modal-body bg-light">


                <!-- LOADING FORM -->

                <div
                    id="loadingForm"
                    class="text-center py-5 d-none">

                    <div
                        class="spinner-border text-success"
                        style="
                            width:3rem;
                            height:3rem;
                        ">
                    </div>

                    <div class="mt-3 text-muted">

                        Memuat data ketetapan...

                    </div>

                </div>


                <div
                    id="formKetetapanWrapper"
                    class="container-fluid">


                    <form id="formKetetapan">


                        <?= csrf_field() ?>


                        <div class="row mb-4">


                            <!-- JENIS -->

                            <div class="col-md-6">

                                <label
                                    class="form-label fw-semibold">

                                    Jenis Pajak

                                </label>

                                <select
                                    class="form-select form-select-lg"
                                    id="selectJenis"
                                    name="jenis_id">

                                    <option value="">

                                        -- Pilih Jenis Pajak --

                                    </option>

                                    <?php
                                    foreach (
                                        $jenis_pajak
                                        as $jenis
                                    ):
                                    ?>

                                        <option
                                            value="<?= $jenis['id'] ?>">

                                            <?= esc(
                                                $jenis['nama_pajak']
                                            ) ?>

                                        </option>

                                    <?php endforeach ?>

                                </select>

                            </div>


                            <!-- TAHUN -->

                            <div class="col-md-3">

                                <label
                                    class="form-label fw-semibold">

                                    Tahun

                                </label>

                                <select
                                    class="form-select form-select-lg"
                                    id="selectTahun"
                                    name="tahun">

                                    <?php
                                    for (
                                        $i = date('Y') + 1;
                                        $i >= date('Y') - 5;
                                        $i--
                                    ):
                                    ?>

                                        <option
                                            value="<?= $i ?>"
                                            <?= $i == date('Y')
                                                ? 'selected'
                                                : '' ?>>

                                            <?= $i ?>

                                        </option>

                                    <?php endfor ?>

                                </select>

                            </div>

                        </div>


                        <!-- TARGET BULANAN -->

                        <div class="card border-0 shadow-sm">

                            <div class="card-header bg-white">

                                <h6 class="mb-0 fw-bold">

                                    Ketetapan Bulanan

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


                                    <?php
                                    foreach (
                                        $bulan
                                        as $no => $nama
                                    ):
                                    ?>


                                        <div
                                            class="col-xl-3 col-lg-4 col-md-6">


                                            <div
                                                class="border rounded-3 p-3 bg-white">


                                                <label
                                                    class="form-label fw-semibold">

                                                    <?= $nama ?>

                                                </label>


                                                <div
                                                    class="input-group">


                                                    <span
                                                        class="input-group-text">

                                                        Rp

                                                    </span>


                                                    <input
                                                        type="text"
                                                        class="form-control text-end input-ketetapan"
                                                        name="ketetapan[<?= $no ?>]"
                                                        id="ketetapan_<?= $no ?>"
                                                        value="0"
                                                        inputmode="numeric">


                                                </div>

                                            </div>

                                        </div>


                                    <?php endforeach ?>


                                </div>


                                <!-- TOTAL -->

                                <div class="row mt-4">

                                    <div class="col-md-6 ms-auto">

                                        <div
                                            class="alert alert-success mb-0">


                                            <div
                                                class="d-flex justify-content-between">

                                                <strong>
                                                    Total Ketetapan
                                                </strong>

                                                <strong
                                                    id="totalKetetapan">

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


            <!-- FOOTER -->

            <div
                class="modal-footer bg-white border-0 shadow-sm">


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

                        Simpan Ketetapan

                    </span>


                </button>

            </div>

        </div>

    </div>

</div>


<!-- =====================================================
     GLOBAL LOADING
====================================================== -->

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
                style="
                    width:3rem;
                    height:3rem;
                ">
            </div>


            <div class="mt-3 fw-semibold">

                Memuat data...

            </div>


        </div>

    </div>

</div>


<script>

const urlKetetapanData =
    "<?= route_to('ketetapan.data') ?>";

const urlKetetapanGet =
    "<?= route_to('ketetapan.get') ?>";

const urlKetetapanSave =
    "<?= route_to('ketetapan.save') ?>";

const urlKetetapanDelete =
    "<?= route_to('ketetapan.delete') ?>";


let modalKetetapan = null;


/* =====================================================
   FORMAT RUPIAH
===================================================== */

function formatRupiah(value)
{
    value = String(value ?? '');

    value = value.replace(/\D/g, '');

    if (value === '') {
        return '0';
    }

    return new Intl.NumberFormat('id-ID')
        .format(parseInt(value));
}


/* =====================================================
   AMBIL ANGKA
===================================================== */

function getAngka(value)
{
    return parseInt(
        String(value ?? '')
            .replace(/\./g, '')
            .replace(/,/g, '')
            .replace(/\D/g, '')
    ) || 0;
}


/* =====================================================
   TOTAL
===================================================== */

function hitungTotalKetetapan()
{
    let total = 0;

    document
        .querySelectorAll('.input-ketetapan')
        .forEach(input => {

            total += getAngka(input.value);

        });


    document
        .getElementById('totalKetetapan')
        .textContent =
        'Rp ' + formatRupiah(total);
}


/* =====================================================
   FORMAT INPUT
===================================================== */

function initFormatInput()
{
    document
        .querySelectorAll('.input-ketetapan')
        .forEach(input => {

            input.oninput = function () {

                this.value =
                    formatRupiah(this.value);

                hitungTotalKetetapan();

            };


            input.onfocus = function () {

                this.select();

            };

        });
}


/* =====================================================
   LOADING DATA
===================================================== */

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


/* =====================================================
   LOAD DATA
===================================================== */

async function loadKetetapan()
{
    showLoadingData();


    const tahun =
        document
            .getElementById('filterTahun')
            .value;


    try {

        const response = await fetch(
            `${urlKetetapanData}?tahun=${encodeURIComponent(tahun)}`
        );


        const result =
            await response.json();


        const tbody =
            document.getElementById(
                'tbodyKetetapan'
            );


        tbody.innerHTML = '';


        if (
            !result.status ||
            !result.data ||
            result.data.length === 0
        ) {

            tbody.innerHTML = `

                <tr>

                    <td
                        colspan="6"
                        class="text-center text-muted py-5">

                        <i
                            class="bi bi-database-x fs-1 d-block mb-2">
                        </i>

                        Belum ada data ketetapan

                    </td>

                </tr>

            `;

            return;
        }


        /* GROUP JENIS + TAHUN */

        const grouped = {};


        result.data.forEach(row => {

            const key =
                `${row.jenis_id}_${row.tahun}`;


            if (!grouped[key]) {

                grouped[key] = {

                    jenis_id:
                        row.jenis_id,

                    nama_pajak:
                        row.nama_pajak,

                    kode:
                        row.kode,

                    tahun:
                        row.tahun,

                    total:
                        0

                };

            }


            grouped[key].total +=
                Number(row.ketetapan);

        });


        let no = 1;


        Object.values(grouped)
            .forEach(row => {


                tbody.innerHTML += `

                    <tr>

                        <td>
                            ${no++}
                        </td>


                        <td>

                            <div class="fw-semibold">

                                ${escapeHtml(
                                    row.nama_pajak
                                )}

                            </div>

                        </td>


                        <td>

                            <span
                                class="badge text-bg-light">

                                ${escapeHtml(
                                    row.kode ?? '-'
                                )}

                            </span>

                        </td>


                        <td>
                            ${row.tahun}
                        </td>


                        <td
                            class="text-end fw-semibold">

                            Rp ${formatRupiah(
                                row.total
                            )}

                        </td>


                        <td>

                            <div
                                class="d-flex gap-1">


                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    onclick="
                                        editKetetapan(
                                            ${row.jenis_id},
                                            ${row.tahun}
                                        )
                                    ">

                                    <i
                                        class="bi bi-pencil-square me-1">
                                    </i>

                                    Edit

                                </button>


                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="
                                        hapusKetetapan(
                                            ${row.jenis_id},
                                            ${row.tahun}
                                        )
                                    ">

                                    <i
                                        class="bi bi-trash3 me-1">
                                    </i>

                                    Hapus

                                </button>


                            </div>

                        </td>

                    </tr>

                `;

            });


    } catch (error) {

        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal mengambil data ketetapan.'
        });

    } finally {

        hideLoadingData();

    }
}


/* =====================================================
   RESET FORM
===================================================== */

function resetFormKetetapan()
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


    kosongkanKetetapan();  
    
}


/* =====================================================
   TAMBAH
===================================================== */

document
    .getElementById('btnTambah')
    .addEventListener(
        'click',
        function () {


            resetFormKetetapan();


            document
                .getElementById('modalTitle')
                .textContent =
                'Tambah Ketetapan Pajak';


            document
                .getElementById('loadingForm')
                .classList.add('d-none');


            document
                .getElementById(
                    'formKetetapanWrapper'
                )
                .classList.remove('d-none');


            modalKetetapan.show();

        }
    );


/* =====================================================
   EDIT
===================================================== */

async function editKetetapan(
    jenisId,
    tahun
)
{

    resetFormKetetapan();


    document
        .getElementById('modalTitle')
        .textContent =
        'Edit Ketetapan Pajak';


    document
        .getElementById('loadingForm')
        .classList.remove('d-none');


    document
        .getElementById(
            'formKetetapanWrapper'
        )
        .classList.add('d-none');


    modalKetetapan.show();


    try {

        const response = await fetch(
            `${urlKetetapanGet}?jenis_id=${encodeURIComponent(jenisId)}&tahun=${encodeURIComponent(tahun)}`
        );


        const result =
            await response.json();


        if (!result.status) {

            throw new Error(
                result.message ||
                'Gagal mengambil data.'
            );

        }


        document
            .getElementById('selectJenis')
            .value = jenisId;


        document
            .getElementById('selectTahun')
            .value = tahun;


        Object.values(result.data)
            .forEach(row => {

                document
                    .getElementById(
                        `ketetapan_${row.bulan}`
                    )
                    .value =
                    formatRupiah(
                        row.ketetapan
                    );

            });


        hitungTotalKetetapan();


    } catch (error) {

        console.error(error);

        modalKetetapan.hide();


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
            .getElementById(
                'formKetetapanWrapper'
            )
            .classList.remove('d-none');

    }
}


/* =====================================================
   SIMPAN
===================================================== */

document
    .getElementById('btnSimpan')
    .addEventListener(
        'click',
        async function () {


            const jenisId =
                document
                    .getElementById(
                        'selectJenis'
                    )
                    .value;


            if (!jenisId) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan pilih jenis pajak.'
                });

                return;
            }


            const tahun =
                document
                    .getElementById(
                        'selectTahun'
                    )
                    .value;


            const button = this;


            button.disabled = true;


            document
                .getElementById(
                    'spinnerSimpan'
                )
                .classList.remove(
                    'd-none'
                );


            document
                .getElementById(
                    'iconSimpan'
                )
                .classList.add(
                    'd-none'
                );


            document
                .getElementById(
                    'textSimpan'
                )
                .textContent =
                'Menyimpan...';


            const form =
                document.getElementById(
                    'formKetetapan'
                );


            const formData =
                new FormData(form);


            /*
             * Pastikan angka yang dikirim
             * bukan format Rupiah
             */

            for (
                let bulan = 1;
                bulan <= 12;
                bulan++
            ) {

                const input =
                    document.getElementById(
                        `ketetapan_${bulan}`
                    );


                formData.set(
                    `ketetapan[${bulan}]`,
                    getAngka(
                        input.value
                    )
                );

            }


            try {

                const response =
                    await fetch(
                        urlKetetapanSave,
                        {
                            method: 'POST',
                            body: formData
                        }
                    );


                const result =
                    await response.json();


                if (
                    result.csrfHash
                ) {

                    const csrfInput =
                        form.querySelector(
                            'input[name="<?= csrf_token() ?>"]'
                        );

                    if (csrfInput) {

                        csrfInput.value =
                            result.csrfHash;

                    }

                }


                if (!result.status) {

                    throw new Error(
                        result.message ||
                        'Gagal menyimpan data.'
                    );

                }


                modalKetetapan.hide();


                await loadKetetapan();


                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result.message,
                    timer: 1500,
                    showConfirmButton: false
                });


            } catch (error) {

                console.error(error);


                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message
                });


            } finally {

                button.disabled = false;


                document
                    .getElementById(
                        'spinnerSimpan'
                    )
                    .classList.add(
                        'd-none'
                    );


                document
                    .getElementById(
                        'iconSimpan'
                    )
                    .classList.remove(
                        'd-none'
                    );


                document
                    .getElementById(
                        'textSimpan'
                    )
                    .textContent =
                    'Simpan Ketetapan';

            }

        }
    );


/* =====================================================
   HAPUS
===================================================== */

async function hapusKetetapan(
    jenisId,
    tahun
)
{

    const confirm =
        await Swal.fire({

            icon: 'warning',

            title: 'Hapus Ketetapan?',

            text:
                `Seluruh ketetapan tahun ${tahun} untuk jenis pajak ini akan dihapus.`,

            showCancelButton: true,

            confirmButtonText:
                'Ya, Hapus',

            cancelButtonText:
                'Batal',

            confirmButtonColor:
                '#dc3545'

        });


    if (!confirm.isConfirmed) {
        return;
    }


    showLoadingData();


    const formData =
        new FormData();


    formData.append(
        'jenis_id',
        jenisId
    );


    formData.append(
        'tahun',
        tahun
    );


    /*
     * CSRF
     */

    const csrfInput =
        document.querySelector(
            '#formKetetapan input[name="<?= csrf_token() ?>"]'
        );


    if (csrfInput) {

        formData.append(
            '<?= csrf_token() ?>',
            csrfInput.value
        );

    }


    try {

        const response =
            await fetch(
                urlKetetapanDelete,
                {
                    method: 'POST',
                    body: formData
                }
            );


        const result =
            await response.json();


        if (
            result.csrfHash &&
            csrfInput
        ) {

            csrfInput.value =
                result.csrfHash;

        }


        if (!result.status) {

            throw new Error(
                result.message ||
                'Gagal menghapus data.'
            );

        }


        await loadKetetapan();


        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: result.message,
            timer: 1500,
            showConfirmButton: false
        });


    } catch (error) {

        console.error(error);


        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: error.message
        });


    } finally {

        hideLoadingData();

    }
}


/* =====================================================
   FILTER TAHUN
===================================================== */

document
    .getElementById('filterTahun')
    .addEventListener(
        'change',
        loadKetetapan
    );


/* =====================================================
   ESCAPE HTML
===================================================== */

function escapeHtml(value)
{
    if (
        value === null ||
        value === undefined
    ) {

        return '';

    }


    return String(value)
        .replace(
            /&/g,
            '&amp;'
        )
        .replace(
            /</g,
            '&lt;'
        )
        .replace(
            />/g,
            '&gt;'
        )
        .replace(
            /"/g,
            '&quot;'
        )
        .replace(
            /'/g,
            '&#039;'
        );
}


  /* =====================================================
    INITIALIZE
  ===================================================== */

  document.addEventListener(
      'DOMContentLoaded',
      function () {


          /*
          * Bootstrap 5
          */

          const modalElement =
              document.getElementById(
                  'modalKetetapan'
              );


          modalKetetapan =
              bootstrap.Modal.getOrCreateInstance(
                  modalElement
              );


          /*
          * Input Rupiah
          */

          initFormatInput();


          /*
          * Load data
          */

          loadKetetapan();

      }
  );

  /* =====================================================
    LOAD KETETAPAN BERDASARKAN JENIS + TAHUN
  ===================================================== */

  async function loadKetetapanForm()
  {
      const jenisId =
          document.getElementById('selectJenis').value;

      const tahun =
          document.getElementById('selectTahun').value;


      /*
      * Kalau jenis belum dipilih,
      * kosongkan semua input
      */
      if (!jenisId) {

          kosongkanKetetapan();

          return;
      }


      /*
      * Tampilkan loading
      */
      document
          .getElementById('loadingForm')
          .classList.remove('d-none');

      document
          .getElementById('formKetetapanWrapper')
          .classList.add('d-none');


      try {

          const response = await fetch(
              `${urlKetetapanGet}?jenis_id=${encodeURIComponent(jenisId)}&tahun=${encodeURIComponent(tahun)}`
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
                  'Gagal mengambil data ketetapan.'
              );

          }


          /*
          * Kosongkan terlebih dahulu
          */
          kosongkanKetetapan();


          /*
          * Isi data Januari - Desember
          */
          Object.values(result.data)
              .forEach(row => {

                  const input =
                      document.getElementById(
                          `ketetapan_${row.bulan}`
                      );


                  if (input) {

                      input.value =
                          formatRupiah(
                              row.ketetapan
                          );

                  }

              });


          /*
          * Hitung total
          */
          hitungTotalKetetapan();


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
              .getElementById('formKetetapanWrapper')
              .classList.remove('d-none');

      }
  }  

  function kosongkanKetetapan()
  {
      for (
          let bulan = 1;
          bulan <= 12;
          bulan++
      ) {

          const input =
              document.getElementById(
                  `ketetapan_${bulan}`
              );


          if (input) {

              input.value = '0';

          }

      }


      hitungTotalKetetapan();
  }
  
  /*====event saat Jenis Pajak berubah */
  document
      .getElementById('selectJenis')
      .addEventListener(
          'change',
          function () {

              loadKetetapanForm();

          }
      );  

  
  /*=== event saat Tahun berubah ===*/
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

                  loadKetetapanForm();

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