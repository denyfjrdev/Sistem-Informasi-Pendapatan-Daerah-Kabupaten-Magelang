<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?> 

  <div class="card">
    <div class="card-body">

      <div class="mb-3 d-flex justify-content-between align-items-center gap-2">
          <select id="layanan_id"
                  onchange="tampil_tahapan(this.value)"
                  class="form-control">
              <option value="">---Pilih kategori layanan---</option>
              <?php foreach($rlayanan as $val){ ?>
                  <option value="<?= $val->layanan_id ?>">
                      <?= $val->layanan_nama ?>
                  </option>
              <?php } ?>
          </select>

          <button type="button"                  
                  style="min-width: 120px; min-height: 35px"
                  class="btn btn-success btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target=".bs-example-modal-xl"
                  onclick="document.getElementById('formTahapan').reset()">
              <i class="fa-solid fa-plus me-2"></i>Baru
          </button>
      </div>      

      <!----LOADING view--->
      <div id="loading_tahapan" style="display:none;" class="text-center mb-3">
          <div class="spinner-border text-primary" role="status"></div>
          <div>Loading data...</div>
      </div>       
      <table style="width:100%" class="table table-sm table-striped table-bordered table-hover table2" id="table-artikel-query">
          <thead id="header_tahapan">
              <tr>
                <th>Urut</th>        
                <th>Nama tahapan</th>
                <th>Role</th>
                <th>Proses</th>            
                <th>Keterangan</th>
                <th></th>                
              </tr>
          </thead>
          <tbody id="html_tahapan"></tbody>
      </table>  
    </div>
  </div>

  <?= view('App\Modules\Master\Views\Setting\modal_tahapan') ?>

  <script>    
    var url_simpan    = '<?=base_url('master/ajax_simpan_tahapan')?>';    
    var url_hapus     = '<?=base_url('master/ajax_hapus_tahapan')?>';
    var url_load_data = '<?=base_url('master/ajax_get_tahapan')?>';
  </script>
  <script>
    <?php include APPPATH . 'Modules/Master/Views/Setting/tahapan.js'; ?>
  </script>
  
  

<?= $this->endSection() ?>