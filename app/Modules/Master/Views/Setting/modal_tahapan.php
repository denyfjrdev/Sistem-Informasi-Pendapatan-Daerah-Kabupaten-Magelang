  <!--  Modal tambah tahapan -->
  <div id="modal_id" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="myExtraLargeModalLabel"><?=$fiture?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                          <div class="card-body">
                            <form id="formTahapan" action="#"">
                              <input hidden type="text" name="id" id="id_id">

                              <h4 class="card-title">Tambah tahapan baru</h4>                              
                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Jenis layanan</label>
                                  <div class="col-md-10">
                                    <select id="layanan_id_id" name="layanan_id" class="form-control data-placeholder="Choose ...">
                                      <option value="">---Pilih kategori layanan---</option>
                                      <?php foreach($rlayanan as $val){?>
                                        <option value="<?=$val->layanan_id?>"><?=$val->layanan_nama?></option>
                                      <?php }?>              
                                    </select>
                                  </div>
                              </div>
                            
                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Nama tahapan</label>
                                  <div class="col-md-10">
                                      <input required id="nama_tahapan_id" name="nama_tahapan" class="form-control" type="text" value="" id="example-text-input">
                                  </div>
                              </div>

                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Pilih proses</label>
                                  <div class="col-md-5">
                                    <select id="proses_id" name="proses" class="form-control data-placeholder="Choose ...">
                                    <option value="">---Pilih proses tahapan---</option>
                                      <option value="sistem">Sistem</option>                                      
                                      <option value="manual">Non sistem</option>                                      
                                    </select>
                                  </div>
                              </div>

                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Role pelaksana</label>
                                  <div class="col-md-5">
                                    <select id="role_id" name="role" class="form-control data-placeholder="Choose ...">
                                      <option value="">---Pilih role pelaksana---</option>
                                      <?php foreach($role as $val){?>
                                        <option value="<?=$val?>"><?=$val?></option>
                                      <?php }?>              
                                    </select>
                                  </div>
                              </div>

                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Keterangan</label>
                                  <div class="col-md-10">
                                      <input required id="keterangan_id" name="keterangan" class="form-control" type="text" value="" id="example-text-input">
                                  </div>
                              </div>

                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Urutan</label>
                                  <div class="col-md-3">
                                      <input required id="urutan_id" name="urutan" class="form-control" type="number" value="" id="example-text-input">
                                  </div>
                              </div>

                              <div class="mb-3 row">
                                <button onclick="simpan_tahapan()" type="button" class="btn btn-primary w-xs waves-effect waves-light"><i class="fa-solid fa-save me-2"></i>Simpan</button>
                              </div>                              

                            </form>

                          </div>                  
                        </div>
                    </div>
                  </div>
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


    <!--  Modal konfirmasi hapus tahapan -->
  <div id="modal_id_hapus" class="modal fade bs-example-modal-xl-hapus" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="myExtraLargeModalLabel"><i class="fa fa-question"></i> Konfirmasi hapus tahapan</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                          <div class="card-body">
                            <form id="form_hapus_tahapan" action="#"">
                              <input hidden type="text" name="tahapan_id" id="id_id_hapus">    
                              <input hidden type="text" id="layanan_id_id_hapus" >                                                                                    
                            
                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Nama tahapan</label>
                                  <div class="col-md-10">
                                      <input disabled id="nama_tahapan_id_hapus" name="nama_tahapan_hapus" class="form-control" type="text" value="" id="example-text-input">
                                  </div>
                              </div>   
                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Role</label>
                                  <div class="col-md-5">
                                      <input disabled id="role_id_hapus" name="role_hapus" class="form-control" type="text" value="" id="example-text-input">
                                  </div>
                              </div>                          

                              <div class="modal-footer">

                                  <button type="button"
                                          class="btn btn-secondary"
                                          data-bs-dismiss="modal">
                                      Tidak
                                  </button>

                                  <button type="button"
                                          class="btn btn-danger"
                                          id="btnYa" onclick="hapus_tahapan()"
                                          >
                                    <i class="fa fa-check"></i>  Ya
                                  </button>

                              </div>                              

                              <!-- <div class="mb-3 row">                                
                                <button onclick="hapus_tahapan()" type="button" class="btn btn-danger w-xs waves-effect waves-light"><i class="fa-solid fa-trash me-2"></i>Hapus</button>
                              </div>                               -->

                            </form>

                          </div>                  
                        </div>
                    </div>
                  </div>
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->  