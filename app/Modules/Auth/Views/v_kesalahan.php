<?php echo $this->include("layout_pemohon/pemohon_main_header")?>  
<body>
  <div class="card card-danger">
    <div class="card-header">
      <h5><?=$judul?></h5>
    </div>
    <div class="card-body">
      <div class="callout callout-danger">
        <h5 class="title h5"><i><u><i class="fa fa-exclamation"></i> Informasi :</u></i></h5>
        <small>
          <p>
            Ada kesalahan sistem, silahkan tekan <b>home</b> untuk kembali ke menu utama
            <?php if($error !=''){?>
            <br><br><span class="text-red"> Error : <i><?=$error?><i> </span>
            <?php }?>
          </p>
        </small>
      </div>
    </div>
    <?php if($hp==0){?>
      <div class="card-footer">
        <a href="<?=base_url()?>" class="btn btn-default"> <i class="fa fa-home"></i> Home </a>
      </div>
    <?php }?>
  </div>


  <?php echo $this->include("layout_pemohon/pemohon_main_footer")?> 

</body>