<?php

  $routes->group('api/esptpd', ['namespace' => 'App\Modules\Api\Esptpd\Controllers'], function($routes){
    $routes->get('', 'Load::index');

    $routes->get('realisasi/realisasi_perkelurahan_bulanan', 'Load::load');
    $routes->post('realisasi/realisasi_perkelurahan_bulanan', 'Load::load');

    $routes->get('realisasi/lra_bulanan_pajak_jenis', 'Load::load_lra');
    $routes->post('realisasi/lra_bulanan_pajak_jenis', 'Load::load_lra');

    $routes->get('load', 'Load::load');
    $routes->post('load', 'Load::load');
  });
