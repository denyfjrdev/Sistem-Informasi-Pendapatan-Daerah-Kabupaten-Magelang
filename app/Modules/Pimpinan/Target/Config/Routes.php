<?php

  /*
   * Route "Target" untuk Pimpinan.
   * Hanya sifatnya melihat (index, data, get) -- TIDAK ada
   * route save/delete karena Pimpinan tidak boleh input target.
   * (Sebelumnya file ini copy-paste dari Admin dan malah mendaftarkan
   * ulang group 'admin' + 'admin/target', sudah dirapikan di sini.)
   */

  $routes->group('pimpinan/target', [
      'namespace' => 'App\Modules\Pimpinan\Target\Controllers'
  ], function ($routes) {

    $routes->get('/', 'TargetController::index', [
        'as' => 'pimpinan.target.index'
    ]);

    $routes->get('data', 'TargetController::data', [
        'as' => 'pimpinan.target.data'
    ]);

    $routes->get('get', 'TargetController::get', [
        'as' => 'pimpinan.target.get'
    ]);

  });

?>
