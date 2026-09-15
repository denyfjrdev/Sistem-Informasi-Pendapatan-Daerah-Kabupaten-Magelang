<!DOCTYPE html>
<?php echo $this->include("\Modules\Auth\Views\auth_header")?>


<body class="hold-transition login-page bg-image">
  <div id="page">
    <div class="login-box">
      <!-- /.login-logo -->
      <div class="card card-danger">
        <div class="card-header text-center">
          <a href="#" class="h4"><i class='fa fa-user-plus'></i> Register Akun SIDERING</a>          
        </div>
        <div class="card-body">      
  
          <?php if($mode == 'otp'){?>

              <form action="<?=base_url('auth/nek_nohp')?>" method="post">
                <div class="input-group mb-3">
                  <input required type="number" class="form-control" placeholder="Nomor whatsapp (*)" name="nohp">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-phone"></span>
                    </div>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <input required type="text" class="form-control" placeholder="Password (*)" name="password">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-key"></span>
                    </div>
                  </div>
                </div>   
                <div class="input-group mb-3">
                  <input required type="text" class="form-control" placeholder="Ketik ulang password (*)" name="password2">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-key"></span>
                    </div>
                  </div>
                </div>                              
                <button type="submit" name="cmdcek_nohp" value="true" class="btn btn-success btn-block"> <i class="fa fa-save"></i> Proses Validasi No Whatsapp</button>                            
                <div class="alert alert-default alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-exclamation-triangle"></i> Petunjuk.., baca dulu sebelum register. !</h5>
                  <ul>
                    <li>Untuk kelancaran proses permohonan, pastikan anda sudah menyiapkan syarat2 yang terlebih dahulu 
                        <button type="button" onclick="direk_newtab('<?=base_url('home/jenis_layanan')?>')" class="btn btn-outline-primary" > Cek persyaratan <i class='fa fa-arrow-right'></i> </button></li>
                    <li>Masukkan nomor Whatsapp yang masih aktif...</li>
                    <li>Sistem akan mengecek validitas nomor whatsapp</li>                                       
                    <li>Jika nomor valid, maka sistem akan mengirimkan link aktivasi yang berlaku selama 24 jam</li>
                    <li>Silahkan buka link tersebut dan lanjutkan proses pengisian data akun</li>
                  </ul>
                </div>                                
              </form>

          <?php }else{?>              
              
              <form action="<?=base_url('auth/save')?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="back" value="<?=$url['full']?>">

                
                <div class="input-group mb-3">
                  <input required type="text" class="form-control" placeholder="Nama lengkap (*)" name="nama">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-user"></span>
                    </div>
                  </div>
                </div>           
                <div class="input-group mb-3">
                  <input required type="text" class="form-control" placeholder="NIK (*)" name="nik">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-book"></span>
                    </div>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <input required type="email" class="form-control" placeholder="Email (*)" name="email">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-envelope"></span>
                    </div>
                  </div>
                </div>            
                <div class="input-group mb-3">
                  <input required type="text" class="form-control" placeholder="No Whatsapp (*)" name="telepon">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-phone"></span>
                    </div>
                  </div>
                </div>            
                <div class="input-group mb-3">  
                  <div class="input-group">
                    <div class="input-group-prepend">                      
                      <canvas id="captcaCanvas" width="150" height="40" style="border:0px solid #d3d3d3;">Your browser does not support the HTML5 canvas tag. change your browser</canvas>                      
                    </div>
                    <input required type="number" id="pengaman" class="form-control form-control-sm" name="pengaman" placeholder="Masukkan kode capthca" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==16) return false;" maxlength="16" required>
                  </div>                
                </div>            
                <div class="row">
                  <div class="col-6">
                    <!-- <div class="icheck-primary">
                      <input type="checkbox" id="remember">
                      <label for="remember">
                        Remember Me
                      </label>
                    </div> -->
                  </div>
                  <!-- /.col -->
                  <div class="col-6">
                    <button type="submit" name="cmdkirim" value="true" class="btn btn-success btn-block"> <i class="fa fa-save"></i> Simpan pendaftaran</button>
                  </div>
                  <!-- /.col -->
                </div>
              </form>

          <?php }?>

          <hr>
          <div class="row">
            <div class="col-6">
              <a href="<?=base_url('auth/login')?>" class="btn btn-link">
                <i class="fa fa-lock-open mr-2"></i> Halaman login
              </a>              
            </div>
            <div class="col-6">
              <a href="<?=base_url()?>" class="btn btn-link">
                <i class="fa fa-home mr-2"></i> Halaman utama
              </a> 
              <!-- <a href="<?=base_url('auth/lupa_password')?>" class="btn btn-link">
                <i class="fa fa-key mr-2"></i> Lupa password
              </a>                -->
            </div>
          </div>

         

          <!-- <div class="social-auth-links text-center mt-2 mb-3">
            <a href="#" class="btn btn-block btn-success">
              <i class="fa fa-user-plus mr-2"></i> Daftar akun baru
            </a>
            <a href="#" class="btn btn-block btn-warning text-white">
              <i class="fa fa-key mr-2"></i> Lupa password
            </a>
          </div> -->
          <!-- /.social-auth-links -->

          <!-- <p class="mb-1">
            <a href="forgot-password.html">I forgot my password</a>
          </p>
          <p class="mb-0">
            <a href="register.html" class="text-center">Register a new membership</a>
          </p> -->
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.login-box -->        
  </div>
  <div id="loading"></div>
  <?php echo $this->include("\Modules\Auth\Views\auth_footer")?>

  <script type="text/javascript">
    var c = document.getElementById("captcaCanvas");
    var ctx = c.getContext("2d");
    ctx.font = "30px Verdana";
    // Create gradient
    var gradient = ctx.createLinearGradient(0, 0, c.width, 0);
    gradient.addColorStop("0", "black");
    gradient.addColorStop("0.5", "blue");
    gradient.addColorStop("1.0", "red");
    // Fill with gradient
    ctx.textAlign = "center";
    ctx.fillStyle = gradient;
    ctx.fillText("<?= $pengaman ?>", 60, 30);
  </script>

  <?php 
    if(session('notif')) {  #--BERHASIL
      $pesan = session('notif'); 
      if($pesan['tipe'] == 'success'){
    ?>
      <script>        
        toastr.<?=$pesan['tipe']?>('<?=$pesan['isi']?>','<?=$pesan['judul']?>');        
        toastr.options.fadeOut = 1000;
        toastr.options.fadeIn = 300;        
      </script>
    <?php }else{ #--GAGAL?>
      <script>          
          Swal.fire(
            '<?=$pesan['judul']?>',
            '<?=$pesan['isi']?>',
            '<?=$pesan['tipe']?>'
          );
      </script>
  <?php }}?>

</body>

</html>
