<?php include('auth_header.php') ;  ?>
<body class="login-page bg-image">
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="<?=base_url()?>">
					<!-- <img src="<?=base_url()?>/public/deskapp/vendors/images/deskapp-logo.svg" alt=""> -->
          <!-- <img src="<?=base_url('/foto?path=deskapp/vendors/images/menoreh2.jpg')?>" alt=""> -->
				</a>
        <?=(strtolower(session('versi')) == 'production') ? '<span class="badge badge-success">'.session('versi').'</span>':'<span class="badge badge-danger">'.session('versi').'</span>' ?>
			</div>
			<div class="login-menu">
				<ul>
					<li><a href="<?=base_url()?>"><i class="dw dw-home"></i> Home</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
      <div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<!-- <img src="<?=base_url()?>/foto?path=deskspp/vendors/images/login-page-img.png" alt=""> -->
				</div>
        <div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h3 class="text-center text-primary"><i class="icon-copy dw dw-user-2"></i> <a href="<?=base_url()?>"> <?=NAMA_APLIKASI?> </a> </h3>
              <h5 class="text-center text-success"><span class="badge badge-success badge-pill badge-sm">--Lupa password--</span></h5>
						</div>            
            <?php if($otp == ''){ #-----tidak ada session kode OTP?>
              <form action="<?= base_url('/lupa_password_kirim')?>" method="POST">              
                <div class="form-group row">									
                    <div class="col-sm-12 col-md-12">
                      <input required name="nik" type="number" class="form-control" placeholder="Masukkan NIK yang terdaftar" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==16) return false;" maxlength="16">
                    </div>
                </div>
                <div class="input-group custom">  
                  <div class="col-md-5">
                    <center>
                      <canvas id="captcaCanvas" width="300" height="40" style="border:0px solid #d3d3d3;">Your browser does not support the HTML5 canvas tag. change your browser</canvas>
                    </center>
                  </div>      
                  <div class="col-md-7">
                    <input type="number" id="pengaman" class="form-control form-control-sm" name="pengaman" placeholder="Capthca" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==16) return false;" maxlength="16" 
                    required>
                  </div>
                  <small> <i>**) Sistem akan mengirimkan kode OTP ke nomor WA anda, pastikan WA anda aktif...!</i> </small>
                </div>  
                <div class="row pb-30">
                  <div class="col-md-6">
                    <a href="<?=base_url('/lupa_password')?>" class="btn btn-outline-primary float-left"> <i class="icon-copy dw dw-left-arrow"></i> Kembali </a>
                  </div>
                  <div class="col-md-6">
                    <button type="submit" name="cmdproses" value="true" class="btn btn-primary float-right"> Proses <i class="icon-copy dw dw-enter-1"></i> </button>
                  </div>                    
                </div>                  
              </form>
            <?php }else{ #-----ada session kode OTP?>
              <form action="<?= base_url('/lupa_password_kirim')?>" method="POST">              
                <div class="form-group row">									
                    <div class="col-sm-12 col-md-12">
                      <input  name="otp" type="number" class="form-control" placeholder="Masukkan kode OTP" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==16) return false;" maxlength="16">
                      <small><i>**) Kode OTP dikirim maksimal 5 menit, jika dalam waktu 5 menit kode OTP belum terkirim, bisa ditekan tombol <b>Resend</b></i></small>
                    </div>
                </div>
                 
                <div class="row pb-30">
                  <div class="col-md-6">
                    <button type="submit" name="cmdkirim_otp" value="true" class="btn btn-success float-left"> Resend <i class="icon-copy dw dw-enter"></i> </button>
                  </div>
                  <div class="col-md-6">
                    <button type="submit" name="cmdproses_otp" value="true" class="btn btn-primary float-right"> Proses <i class="icon-copy dw dw-enter-1"></i> </button>
                  </div>                    
                </div>                  
              </form>
            <?php }?>
            
          </div>
        </div>
        
      </div>
    </div>
  </div>
</body>

<?php include('auth_footer.php') ;  ?>

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