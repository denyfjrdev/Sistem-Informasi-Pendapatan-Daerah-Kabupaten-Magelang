<?php

  // #-- Admin
  // $routes->group('admin/target', ['namespace' => 'App\Modules\Admin\Target\Controllers'], function($routes){
  //   $routes->get('', 'Target::index');    
  //   $routes->post('simpan_anggaran', 'Target::simpan_anggaran');    
  //   $routes->post('ajax_load_pbb', 'Target::ajax_load_pbb');
  //   $routes->post('ajax_load_pbb_target', 'Target::ajax_load_pbb_target');
  //   $routes->post('ajax_simpan_target', 'Target::ajax_simpan_target');

  //   $routes->get('test_get_rekap_target', 'Target::test_get_rekap_target');
  // });

  // $routes->group('pdf', function ($routes) {
  $routes->group('publik', [
      'namespace' => 'App\Modules\Publik\Pdf\Controllers'
  ], function ($routes) {

    $routes->get('dokumen', 'PdfController::index', [
        'as' => 'pdf.public'
    ]);
  
  });    

?>