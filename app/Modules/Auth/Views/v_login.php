<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Login | <?= esc(env('NAMA_APLIKASI') ?? 'SIJAKA') ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?= base_url('skote/assets/images/mgl2.png') ?>?v=2">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>?v=2">
    <link rel="apple-touch-icon" href="<?= base_url('skote/assets/images/mgl2.png') ?>?v=2">
    <link href="<?= base_url('skote/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('skote/assets/css/icons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('skote/assets/css/app.min.css') ?>" rel="stylesheet">

  <style>

    .auth-full-page-content {
        background-color: #f3f5ff;
        background-image: url('<?= base_url('skote/assets/images/bg-auth-overlay.png') ?>');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
    }

  </style>          

</head>

<body>
  <div class="auth-page">
      <div class="container-fluid p-0">
          <div class="row g-0">
              <!-- LEFT SIDE -->
              <div class="col-xl-9">
                  <div class="auth-full-page-content d-flex p-sm-5 p-4">
                      <div class="w-100">
                          <div class="d-flex flex-column h-100">
                              <div class="mb-4 mb-md-5">
                                  <a href="<?= base_url() ?>"
                                    class="d-block auth-logo">

                                      <!-- <img src="<?= base_url('skote/assets/images/logo-dark.png') ?>"
                                          alt=""
                                          height="18"> -->

                                  </a>

                              </div>

                              <div class="my-auto">
                                  <div class="text-center">
                                      <h5 class="mb-0">
                                          <!-- Si-Jakon -->
                                      </h5>

                                      <p class="text-muted mt-2">
                                          <!-- DPUPR -->
                                      </p>
                                  </div>
                              </div>

                              <div class="mt-4 mt-md-5 text-center">
                                  <p class="mb-0">
                                      © <?= date('Y') ?> <?=env('NAMA_DINAS')?>
                                  </p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- RIGHT SIDE -->
              <div class="col-xl-3">
                  <div class="auth-full-bg pt-lg-5 p-4">
                      <div class="w-100">
                          <div class="bg-overlay"></div>
                          <div class="d-flex h-100 flex-column">
                              <div class="p-4 mt-auto">
                                  <div class="row justify-content-center">

                                      <?php if (session()->getFlashdata('error')) : ?>

                                          <div class="alert alert-danger alert-dismissible fade show">

                                              <i class="mdi mdi-block-helper me-2"></i>

                                              <?= session()->getFlashdata('error') ?>

                                              <button type="button"
                                                      class="btn-close"
                                                      data-bs-dismiss="alert"></button>

                                          </div>

                                      <?php endif; ?>
                                  
                                      <div class="col-lg-12">
                                          <div class="text-center">
                                              <div class="mb-4">
                                                  <img src="<?= base_url('skote/assets/images/mgl2.png') ?>"
                                                      alt=""
                                                      height="100">
                                              </div>
                                              <h5 class="text-primary"> <?=env('NAMA_APLIKASI')?> </h5>
                                              <p class="text-muted">
                                                  Silahkan login !
                                              </p>
                                          </div>
                                          <div class="p-2 mt-4">
                                              <form action="<?=base_url('auth/masuk')?>" method="post" autocomplete="off">
                                                  <div class="mb-3">
                                                      <label class="form-label"> Username </label>
                                                      <input required name="phone_number" autocomplete="off" type="number" class="form-control" value="">
                                                  </div>

                                                  <div class="mb-3">
                                                      <!-- <div class="float-end">
                                                          <a href="#" class="text-muted"> Forgot password? </a>
                                                      </div> -->
                                                      <label class="form-label"> Password </label>
                                                      <div class="input-group auth-pass-inputgroup">
                                                          <input required name="password" autocomplete="off" type="password" class="form-control" value="">
                                                          <button class="btn btn-light" type="button"> <i class="mdi mdi-eye-outline"></i></button>
                                                      </div>

                                                  </div>

                                                  <div class="row">
                                                    <div class="col-md-6">
                                                      <img src ="<?= $captcha ?>" />
                                                    </div>
                                                    <div class="col-md-6">
                                                      <input placeholder="captcha" type="number" name="captcha" class="form-control">  
                                                    </div>           
                                                  </div>                                                                                      
                                                  

                                                  <!-- <div class="form-check">
                                                      <input class="form-check-input" type="checkbox" id="remember-check">
                                                      <label class="form-check-label" for="remember-check"> Remember me</label>
                                                  </div> -->

                                                  <div class="mt-3 d-grid">
                                                      <button class="btn btn-primary waves-effect waves-light" type="submit"> <i class="mdi mdi-login me-1"></i> Log In</button>
                                                  </div>

                                              </form>

                                              <div class="mt-5 text-center">

                                                  <!-- <h5 class="font-size-14 mb-3">
                                                      Sign in with
                                                  </h5> -->

                                                  <ul class="list-inline">

                                                      <!-- <li class="list-inline-item">
                                                          <a href="#"
                                                            class="social-list-item bg-primary text-white border-primary">

                                                              <i class="mdi mdi-facebook"></i>

                                                          </a>
                                                      </li>

                                                      <li class="list-inline-item">
                                                          <a href="#"
                                                            class="social-list-item bg-info text-white border-info">

                                                              <i class="mdi mdi-twitter"></i>

                                                          </a>
                                                      </li>

                                                      <li class="list-inline-item">
                                                          <a href="#"
                                                            class="social-list-item bg-danger text-white border-danger">

                                                              <i class="mdi mdi-google"></i>

                                                          </a>
                                                      </li> -->

                                                  </ul>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <script src="<?= base_url('skote/assets/libs/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('skote/assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('skote/assets/js/app.js') ?>"></script>

</body>
</html>