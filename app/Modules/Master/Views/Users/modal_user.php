  <!--  Modal tambah tahapan -->
  <div id="modal_id" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="myExtraLargeModalLabel"><i class="fa fa-list"></i> Form user baru</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                          <div class="card-body">
                            <form id="formUser" action="#"">
                              <input hidden type="text" name="id" id="id_id">                              
                            
                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Nama</label>
                                  <div class="col-md-10">
                                      <input required id="nama_user_id" name="nama_user" class="form-control" type="text" value="" id="example-text-input">
                                  </div>
                              </div>
                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">No WA</label>
                                  <div class="col-md-5">
                                      <input required id="nohp_id" name="nohp" class="form-control" type="number" value="" id="example-text-input">
                                  </div>
                              </div>                          
                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Email</label>
                                  <div class="col-md-5">
                                      <input required id="email_id" name="email" class="form-control" type="text" value="" id="example-text-input">
                                  </div>
                              </div>    

                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Role</label>
                                  <div class="col-md-5">
                                    <select id="role_id" name="role" class="form-control data-placeholder="Choose ...">
                                      <option value="">---Pilih role---</option>
                                      <?php foreach($role as $val){?>
                                        <option value="<?=$val?>"><?=$val?></option>
                                      <?php }?>              
                                    </select>
                                  </div>
                              </div>
                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Aktif</label>
                                  <div class="col-md-5">
                                    <select id="aktif_id" name="aktif" class="form-control data-placeholder="Choose ...">
                                      <option value="ya">Aktif</option>
                                      <option value="tidak">Tidak aktif</option>
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
                                <button onclick="simpan_user()" type="button" class="btn btn-primary w-xs waves-effect waves-light"><i class="fa-solid fa-save me-2"></i>Simpan</button>
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


  <!--  Modal edit rolre user -->
  <div id="modal_id_roles" class="modal fade bs-example-modal-xl-roles" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="myExtraLargeModalLabel"><i class="fa fa-list"></i> Edit roles user</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                          <div class="card-body">
                            <form id="form_user_roles" action="#"">
                              <input text type="text" name="id" id="id_id_roles">                                                                                        
                              <input text type="text" name="nohp" id="nohp_id_roles">  
                            
                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Nama</label>
                                  <div class="col-md-10">
                                      <input disabled id="nama_user_id_roles" name="nama_user" class="form-control" type="text" value="" id="example-text-input">
                                  </div>
                              </div>                                                                                     

                              <div class="mb-3 row">
                                  <label for="example-text-input" class="col-md-2 col-form-label">Role</label>
                                  <div class="col-md-5">
                                    <select id="role_id" name="role" class="form-control data-placeholder="Choose ...">
                                      <option value="">---Pilih role---</option>
                                      <?php foreach($role as $val){?>
                                        <option value="<?=$val?>"><?=$val?></option>
                                      <?php }?>              
                                    </select>
                                  </div>
                              </div>                                                         

                              <div class="mb-3 row">
                                <button onclick="simpan_roles()" type="button" class="btn btn-primary w-xs waves-effect waves-light"><i class="fa-solid fa-cog me-2"></i> Proses</button>
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

