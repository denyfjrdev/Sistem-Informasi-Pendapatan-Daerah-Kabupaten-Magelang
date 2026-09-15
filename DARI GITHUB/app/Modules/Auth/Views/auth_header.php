
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIDERING | Login</title>

  <?php if (isset($datatables)) { ?>
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url() ?>/public/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/public/lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <?php } ?>

  <?php 
  if(isset($css)){
    foreach($css as $cs){
      echo '<link rel="stylesheet" href="'.base_url($cs).'">';
    }
  }
  if(isset($css_tambahan)){
    foreach($css_tambahan as $cs2){
      echo '<link rel="stylesheet" href="'.base_url($cs2).'">';
    }
  }
  ?>  

  <style>
      .loader {
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid #3498db;
      width: 50px;
      height: 50px;
      -webkit-animation: spin 2s linear infinite; /* Safari */
      animation: spin 2s linear infinite;
      }

      /* Safari */
      @-webkit-keyframes spin {
      0% { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
      }

      @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
      }
  </style>
  <style>
      body {
          /* background: #FFF url("http://i.imgur.com/KheAuef.png") top left repeat-x;             */
      }       
      #page {
          display: none;
      }
      #loading {
          display: block;
          position: absolute;
          top: 0;
          left: 0;
          z-index: 100;
          width: 100vw;
          height: 100vh;
          background-color: rgba(192, 192, 192, 0.5);
          background-image: url("http://i.stack.imgur.com/MnyxU.gif");
          background-repeat: no-repeat;
          background-position: center;
      }
  </style> 

  <style type="text/css">
    .bg-image {
      /* min-height: 472.6px;  */
      background-image: url('<?=base_url('public/bg.jpeg')?>'); 
      background-repeat: no-repeat; 
      background-attachment: fixed; 
      background-position: center; 
      width: 100%; 
      background-size: cover;
    }
  </style> 
  
  <style>
    .login-box, .register-box {
      width: 500px;
    }
  </style>  

</head>