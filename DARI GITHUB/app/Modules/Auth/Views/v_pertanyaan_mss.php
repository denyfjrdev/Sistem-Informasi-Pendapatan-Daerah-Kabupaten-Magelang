<?php echo $this->include("layout_pemohon/pemohon_main_header")?>  
<body>
  <div class="card card-danger">
    <div class="card-header">
      <h5><?=$judul?></h5>
    </div>
    <div class="card-body">
      <div class="callout callout-warning">
        <h5 class="title h5"><i><u><i class="fa fa-exclamation"></i> Informasi :</u></i></h5>
        <small>
          <p>
            <?php //var_dump($css); ?>
            Ini adalah halaman konfirmasi layanan bagi pengunjung pertama Aplikasi Perizinan Online Non OSS (SIDERING).
            Jika anda benar-benar menggunakan layanan ini, silahkan tekan tombol <b>LANJUT</b> atau tombol <b>HOME</b> untuk kembali ke menu utama
          </p>
        </small>
      </div>

      <form action="<?=base_url('auth/mss_pertama')?>" method="POST">        
        <button class="btn btn-primary float-right" type="submit"> Lanjut <i class="fa fa-arrow-right"></i> </button>
      </form>
    </div>
  </div>


  <?php echo $this->include("layout_pemohon/pemohon_main_footer")?> 

</body>