<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?=env('NAMA_APLIKASI')?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?= base_url('skote/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('skote/assets/css/icons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('skote/assets/css/app.min.css') ?>" rel="stylesheet">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <?php if (isset($datatables) && $datatables == true){ ?>    
      <link rel="stylesheet" href="<?= base_url('skote') ?>/plugins/datatables/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="<?= base_url('skote') ?>/plugins/datatables/responsive.bootstrap4.min.css">
      <script src="<?= base_url('skote') ?>/plugins/datatables/jquery-3.7.0.js"></script>     
    <?php }?>
    
    <link href="<?= base_url('skote/assets/libs/select2/css/select2.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('skote/assets/css/tambahan.css') ?>" rel="stylesheet">
    <link href="<?= base_url('skote/assets/css/custom.css') ?>" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('skote/plugins/sweetalert2/sweetalert2.min.css') ?>">


    <style>
        .otp-input {
            width: 50px;
            height: 50px;
            font-size: 22px;
            font-weight: 600;
        }

        .otp-input:focus {
            border-color: #556ee6;
            box-shadow: 0 0 0 .15rem rgba(85, 110, 230, .25);
        }        
    </style>       


    <?= $this->renderSection('css') ?>
</head>

<body>

    <!----header-->
    <div class="container-fluid px-0">
      <!-- <div style="margin: 0px 0 0; padding: 20px;" class="position-relative overflow-hidden card-body-brown" >           -->
        <div style="margin: 0; padding: 5px 12px;" class="position-relative overflow-hidden card-body-brown">
          <div style="display: flex; align-items: center; gap: 10px;">
              <img src="<?= base_url('skote/assets/images/mgl.png') ?>" 
                  alt="logo" 
                  style="height: 30px; width: auto;">

              <div>
                  <h4 style="margin: 0; letter-spacing: 0.1rem;">
                    <span style="color: #288052; font-size: 11px; font-weight: 600;"><?= $menu ?> -</span>
                    <span style="color: #12378d; font-size: 11px; font-weight: 600;"><?= $fiture ?></span>                      
                  </h4>              
              </div>
          </div>          
      </div>

      <?= $this->renderSection('content') ?>
    </div>


  <?php if (isset($datatables) && $datatables == true){ ?>    
    <!-- DATATABLES BS 4-->    
    <script src="<?= base_url('skote') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('skote') ?>/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url('skote') ?>/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="<?= base_url('skote') ?>/plugins/datatables/responsive.bootstrap4.min.js"></script>
  <?php }else{ ?>  
    <script src="<?= base_url('skote/assets/libs/jquery/jquery.min.js') ?>"></script>
  <?php
    }
  ?>

  <script src="<?= base_url('skote/assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('skote/assets/js/app.js') ?>"></script>
  <script src="<?= base_url('skote/assets/libs/select2/js/select2.min.js') ?>"></script>

  <!-- JS -->
   <script src="<?= base_url('skote') ?>/plugins/sweetalert2/sweetalert2.all.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

  <script>
    $(document).ready(function() {
        $('.select2').select2();
    });
  </script>

</body>

</html>