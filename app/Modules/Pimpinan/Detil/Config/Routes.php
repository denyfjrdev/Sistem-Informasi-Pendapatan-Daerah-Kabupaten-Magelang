<?php

  /*
   * Route "Detail" untuk Pimpinan.
   * (Sebelumnya file ini copy-paste dari Admin dan mendaftarkan ulang
   * group 'admin/detil' dengan namespace Admin, sudah dirapikan di sini.)
   */

  $routes->group('pimpinan/detil', [
      'namespace' => 'App\Modules\Pimpinan\Detil\Controllers'
  ], function ($routes) {

    $routes->get('/', 'DetilController::index', [
        'as' => 'pimpinan.detil.index'
    ]);

    $routes->get('desa', 'DetilController::desa', [
        'as' => 'pimpinan.detil.desa'
    ]);

  });

?>
