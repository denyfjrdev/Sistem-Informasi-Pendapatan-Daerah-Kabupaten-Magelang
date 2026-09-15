<?php

  #-- PBB
  $routes->group('api/esptpd', ['namespace' => 'App\Modules\Api\Esptpd\Controllers'], function($routes){
    $routes->get('', 'Load::index');
    $routes->get('load', 'Load::load_esptpd');
    // $routes->post('simpan_anggaran', 'Target::simpan_anggaran');
    // $routes->post('ajax_load_pbb', 'Target::ajax_load_pbb');
    // $routes->post('ajax_load_pbb_target', 'Target::ajax_load_pbb_target');
    // $routes->post('ajax_simpan_target', 'Target::ajax_simpan_target');

    // $routes->get('test_get_rekap_target', 'Target::test_get_rekap_target');
  });


?>