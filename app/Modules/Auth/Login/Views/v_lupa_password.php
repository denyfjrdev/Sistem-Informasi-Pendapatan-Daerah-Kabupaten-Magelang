<!DOCTYPE html>
<?php echo $this->include("\Modules\Auth\Views\auth_header")?>
<body class="hold-transition login-page">
  <div id="page">
    <div class="login-box">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <div class="card-title">
            <i class="fa fa-key"></i> Lupa password <?=session('notif')['tipe']?>
          </div>
        </div>
        <div class="card-body">
            <?php if($otp == ''){ #-----tidak ada session kode OTP?>
              <form action="<?= base_url('auth/lupa_password_kirim')?>" method="POST">              
                <div class="input-group mb-3">  							
                    <div class="col-sm-12 col-md-12">
                      <input required name="nik" type="number" class="form-control" placeholder="Masukkan No Whatsapp atau Email" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==16) return false;" maxlength="16">
                    </div>
                </div>
                <div class="input-group mb-3">  	
                  <div class="col-md-5">
                    <center>
                      <canvas id="captcaCanvas" width="300" height="40" style="border:0px solid #d3d3d3;">Your browser does not support the HTML5 canvas tag. change your browser</canvas>
                    </center>
                  </div>      
                  <div class="col-md-7">
                    <input type="number" id="pengaman" class="form-control form-control-sm" name="pengaman" placeholder="Masukkan angka di atas" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==16) return false;" maxlength="16" 
                    required>
                  </div>
                  <small> <i>**) Sistem akan mengirimkan kode OTP ke nomor WA anda, pastikan WA anda aktif...!</i> </small>
                </div>  
                <div class="row pb-30">                  
                  <div class="col-md-6">
                    <button onclick="show('loading',true)" type="submit" name="cmdproses" value="true" class="btn btn-primary float-right"> Proses <i class="fa fa-arrow-right"></i> </button>
                  </div>                    
                </div>                  
              </form>
            <?php }else{?>          
              <form action="<?= base_url('auth/lupa_password_kirim')?>" method="POST">              
                <div class="input-group mb-3">  							
                    <div class="col-sm-12 col-md-12">
                      <input  name="otp" type="number" class="form-control" placeholder="Masukkan kode OTP" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==16) return false;" maxlength="16">
                      <small><i>**) Kode OTP dikirim maksimal 5 menit, jika dalam waktu 5 menit kode OTP belum terkirim, bisa dicoba diulang prosesnya</i></small>
                    </div>
                </div>
                 
                <div class="row">
                  <div class="col-md-6">
                    <!-- <button type="submit" name="cmdkirim_otp" value="true" class="btn btn-success float-left"> Resend <i class="fa fa-arrow-right"></i> </button> -->
                  </div>
                  <div class="col-md-6">
                    <button onclick="show('loading',true)" type="submit" name="cmdproses_otp" value="true" class="btn btn-primary float-right"> Proses <i class="fa fa-cog"></i> </button>
                  </div>                    
                </div>                  
              </form>
            <?php }?>
            <div class="social-auth-links text-center mt-2 mb-3">
              <a href="<?=base_url('auth/daftar')?>" class="btn btn-block btn-success">
                <i class="fa fa-user-plus mr-2"></i> Daftar akun baru
              </a>
              <a href="<?=base_url('auth/login')?>" class="btn btn-block btn-info text-white">
                <i class="fa fa-lock-open mr-2"></i> Halaman login
              </a>
            </div>

        </div> <!---/.card-body--->    
      </div> <!---/.card-primary--->
    </div> <!---/.login-box---->
  </div> <!----/.page---->
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