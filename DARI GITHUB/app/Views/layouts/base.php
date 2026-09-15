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
    
    <link href="<?= base_url('skote/assets/libs/select2/css/select2.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('skote/plugins/sweetalert2/sweetalert2.css') ?>" rel="stylesheet" type="text/css" />        
    <link href="<?= base_url('skote/assets/css/custom.css') ?>" rel="stylesheet">

    <!---JQUERY INI HARUS DI ATAS--->
    <script src="<?= base_url('skote/assets/libs/jquery/jquery-3.7.1.min.js') ?>"></script>


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

<body data-sidebar="light">
    <div id="layout-wrapper">

        <?= $this->include('layouts/header'); ?>                
        <div class="main-content" style="margin-left:0 !important;">
            <div style="margin: 70px 0 0; padding: 20px;" class="position-relative overflow-hidden card-body-brown" >                
                <div>
                    <h4 style="color: #288052; font-size: 20px; font-weight: 600; margin: 0;letter-spacing: 0.1rem;"><?= $menu ?></h4>
                    <h2 style="color: #000000;font-size: 40px;font-weight: 600;margin:0;text-wrap: wrap;width: 800px;"><?= $fiture ?></h2>
                </div>

            </div>
            <?= $this->renderSection('content') ?>
        </div>

    </div>
  
  <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->  
  <script src="<?= base_url('skote/assets/libs/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('skote/assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('skote/assets/js/app.js') ?>"></script>
  <script src="<?= base_url('skote/assets/libs/select2/js/select2.min.js') ?>"></script>
  <script src="<?= base_url('skote/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>

  <script>
    $(document).ready(function() {
        $('.select2').select2();
    });
  </script>

</body>

</html>
