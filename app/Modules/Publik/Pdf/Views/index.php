<?php 
if($hp == 1){
  echo $this->extend('layouts/base_mss'); 
}else{
  echo $this->extend('layouts/base');
}  
?>

<?= $this->section('css') ?>
<link href="<?= base_url('skote/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('skote/assets/libs/daterangepicker/daterangepicker.css') ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('css/custom/daterangepicker.css') ?>" rel="stylesheet" type="text/css">
<?= $this->endSection() ?>


<?= $this->section('content') ?>

    <!DOCTYPE html>
    <html lang="id">

      <head>

          <meta charset="UTF-8">

          <meta
              name="viewport"
              content="width=device-width, initial-scale=1">

          <title><?= esc($title) ?></title>

          <link
              href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
              rel="stylesheet">   
          
          <style>
              <?= file_get_contents(
                  APPPATH . 'Modules/Publik/Pdf/Views/style.css'
              ) ?>
          </style>        

      </head>


      <body>


      <!-- =====================================================
          HERO
      ===================================================== -->

      <section class="hero">

          <div class="container">

              <h1 class="hero-title">
                  Pusat Dokumen
              </h1>

              <p class="hero-subtitle">
                  Temukan dokumen PDF dengan mudah berdasarkan
                  kategori dan kata kunci deskripsi.
              </p>

          </div>

      </section>


      <!-- =====================================================
          SEARCH
      ===================================================== -->

      <div class="container">

          <div class="search-wrapper">

              <div class="search-card">

                  <form
                      action="<?= route_to('pdf.public') ?>"
                      method="get">

                      <div class="row g-3 align-items-end">


                          <!-- KEYWORD -->

                          <div class="col-lg-6">

                              <label class="form-label">
                                  Kata Kunci
                              </label>

                              <input
                                  type="text"
                                  name="keyword"
                                  class="form-control search-input"
                                  placeholder="Cari berdasarkan deskripsi dokumen..."
                                  value="<?= esc($keyword) ?>">

                          </div>


                          <!-- KATEGORI -->

                          <div class="col-lg-4">

                              <label class="form-label">
                                  Kategori
                              </label>

                              <select
                                  name="kategori_id"
                                  class="form-select search-input">

                                  <option value="">
                                      Semua Kategori
                                  </option>

                                  <?php foreach (
                                      $kategori_list as $kategori
                                  ): ?>

                                      <option
                                          value="<?= $kategori['id'] ?>"
                                          <?= $kategori_id == $kategori['id']
                                              ? 'selected'
                                              : '' ?>>

                                          <?= esc(
                                              $kategori['nama_kategori']
                                          ) ?>

                                      </option>

                                  <?php endforeach; ?>

                              </select>

                          </div>


                          <!-- BUTTON -->

                          <div class="col-lg-2">

                              <button
                                  type="submit"
                                  class="btn btn-search w-100">

                                  🔍 Cari Dokumen

                              </button>

                          </div>

                      </div>

                  </form>

              </div>

          </div>


          <!-- =================================================
              RESULT HEADER
          ================================================== -->

          <div class="result-header">

              <div class="result-title">

                  📄 Menampilkan

                  <strong>
                      <?= $pager->getTotal() ?>
                  </strong>

                  dokumen

              </div>

          </div>


          <!-- =================================================
              DATA
          ================================================== -->

          <?php if (!empty($dokumen)): ?>

              <div class="row g-4">

                  <?php foreach ($dokumen as $row): ?>

                      <div class="col-md-6 col-lg-4">

                          <div class="card pdf-card">

                              <div class="pdf-card-body">


                                  <!-- HEADER -->

                                  <div class="pdf-header">

                                      <div class="pdf-icon">
                                          PDF
                                      </div>

                                      <div class="flex-grow-1">

                                          <div>
                                              <span class="category-badge">

                                                  <?= esc(
                                                      $row['nama_kategori']
                                                  ) ?>

                                              </span>
                                          </div>

                                          <h5 class="pdf-title">

                                              <?= esc(
                                                  $row['nama_file_asli']
                                              ) ?>

                                          </h5>

                                      </div>

                                  </div>


                                  <!-- =================================================
                                      DESKRIPSI
                                      PENTING:
                                      JANGAN gunakan esc() di sini
                                  ================================================== -->

                                  <div class="pdf-description">

                                      <?= html_entity_decode(
                                          $row['deskripsi'],
                                          ENT_QUOTES,
                                          'UTF-8'
                                      ) ?>

                                  </div>


                                  <!-- META -->

                                  <div class="pdf-meta">

                                      <div class="pdf-meta-item">

                                          <span>📅</span>

                                          <span>

                                              <?= date(
                                                  'd M Y',
                                                  strtotime(
                                                      $row['created_at']
                                                  )
                                              ) ?>

                                          </span>

                                      </div>


                                      <div class="pdf-meta-item">

                                          <span>📄</span>

                                          <span>

                                              <?= number_format(
                                                  $row['ukuran_file']
                                                  / 1024
                                                  / 1024,
                                                  2,
                                                  ',',
                                                  '.'
                                              ) ?>

                                              MB

                                          </span>

                                      </div>

                                  </div>


                                  <!-- VIEW -->

                                  <button
                                      type="button"
                                      class="btn-view"
                                      data-bs-toggle="modal"
                                      data-bs-target="#modalPdf"
                                      data-pdf-url="<?= route_to('pdf.view', $row['id']) ?>"
                                      data-pdf-title="<?= esc($row['nama_file_asli']) ?>">

                                      👁 Lihat Dokumen

                                  </button>                                

                              </div>

                          </div>

                      </div>

                  <?php endforeach; ?>

              </div>


              <!-- =================================================
                  PAGINATION
              ================================================== -->

              <div class="mt-5 mb-5">

                  <?= $pager->links() ?>

              </div>


          <?php else: ?>


              <!-- =================================================
                  EMPTY
              ================================================== -->

              <div class="empty-state mb-5">

                  <div class="empty-icon">
                      📄
                  </div>

                  <h5>
                      Dokumen tidak ditemukan
                  </h5>

                  <p>
                      Tidak ada dokumen yang sesuai dengan
                      pencarian Anda.
                  </p>

                  <a
                      href="<?= route_to('pdf.public') ?>"
                      class="btn btn-outline-success">

                      Lihat Semua Dokumen

                  </a>

              </div>

          <?php endif; ?>


      </div>


      </body>

    </html>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>  
    <script>

      document.addEventListener('DOMContentLoaded', function () {

          const modalPdf =
              document.getElementById('modalPdf');

          const pdfViewer =
              document.getElementById('pdfViewer');

          const pdfLoading =
              document.getElementById('pdfLoading');

          const pdfModalTitle =
              document.getElementById('pdfModalTitle');


          /*
          * Saat modal akan dibuka
          */
          modalPdf.addEventListener(
              'show.bs.modal',
              function (event) {

                  const button =
                      event.relatedTarget;

                  const pdfUrl =
                      button.getAttribute(
                          'data-pdf-url'
                      );

                  const pdfTitle =
                      button.getAttribute(
                          'data-pdf-title'
                      );


                  // Set judul
                  pdfModalTitle.textContent =
                      pdfTitle || 'Dokumen PDF';


                  // Tampilkan loading
                  pdfLoading.style.display =
                      'flex';


                  // Kosongkan viewer
                  pdfViewer.src = '';


                  // Load PDF
                  setTimeout(function () {

                      pdfViewer.src = pdfUrl;

                  }, 100);

              }
          );


          /*
          * PDF selesai dimuat
          */
          pdfViewer.addEventListener(
              'load',
              function () {

                  pdfLoading.style.display =
                      'none';

              }
          );


          /*
          * Saat modal ditutup
          */
          modalPdf.addEventListener(
              'hidden.bs.modal',
              function () {

                  // Hentikan PDF
                  pdfViewer.src = '';

                  // Reset loading
                  pdfLoading.style.display =
                      'flex';

              }
          );

      });

    </script>      

    <!-- =====================================================
        MODAL PDF FULL SCREEN
    ====================================================== -->
    <div
        class="modal fade"
        id="modalPdf"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-fullscreen">

            <div class="modal-content modal-pdf-content">

                <!-- HEADER -->

                <div class="modal-header modal-pdf-header">

                    <div class="d-flex align-items-center gap-2">

                        <div class="pdf-modal-icon">
                            PDF
                        </div>

                        <div>

                            <div
                                class="pdf-modal-title"
                                id="pdfModalTitle">

                                Dokumen PDF

                            </div>

                            <small class="text-muted">
                                Pratinjau dokumen
                            </small>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                <!-- BODY -->

                <div class="modal-body modal-pdf-body">

                    <!-- LOADING -->

                    <div
                        id="pdfLoading"
                        class="pdf-loading">

                        <div
                            class="spinner-border text-success"
                            role="status">
                        </div>

                        <div class="mt-3">
                            Membuka dokumen...
                        </div>

                    </div>


                    <!-- PDF -->

                    <iframe
                        id="pdfViewer"
                        class="pdf-viewer"
                        src=""
                        title="PDF Viewer">
                    </iframe>

                </div>

            </div>

        </div>

    </div>    


<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('skote/assets/libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/daterangepicker/moment-with-locales.min.js') ?>"></script>
<script src="<?= base_url('skote/assets/libs/daterangepicker/daterangepicker.js') ?>"></script>
<?= $this->endSection() ?>    