<?php

  #-- Admin
  $routes->group('admin', ['namespace' => 'App\Modules\Admin\Dashboard\Controllers'], function($routes){
    $routes->get('', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    #-- Detail
    $routes->get('detil/ketetapan', 'Detil::ketetapan');
    $routes->get('detil/target', 'Detil::target');
    $routes->get('detil/grafik', 'Detil::grafik');
    $routes->get('detil/realisasi/kecamatan', 'Detil::realisasi_kecamatan');
    $routes->get('detil/realisasi/desa', 'Detil::realisasi_desa');

    #-- Informasi
    $routes->get('informasi', 'Informasi::index');
  });

  // $routes->group('admin/dashboard', ['namespace' => 'App\Modules\Admin\Controllers'], function($routes){
  //   $routes->get('', 'Dashboard::index');    
  //   $routes->get('index', 'Dashboard::index');
  //   $routes->post('ajax_get_dashboard', 'Dashboard::ajax_get_dashboard');
  //   $routes->post('ajax_get_permohonan', 'Dashboard::ajax_get_permohonan');
  //   $routes->post('ajax_get_row_transaksi', 'Dashboard::ajax_get_row_transaksi');
  //   $routes->post('ajax_simpan_revisi_ujilab', 'Dashboard::ajax_simpan_revisi_ujilab');
  // });

  // $routes->group('admin/transaksi', ['namespace' => 'App\Modules\Admin\Controllers'], function($routes){    
  //   $routes->post('ajax_simpan_ujilab', 'Transaksi::ajax_simpan_ujilab');
  //   $routes->post('ajax_create_pdf', 'Transaksi::ajax_create_pdf');

  //   $routes->get('ajax_create_pdf', 'Transaksi::ajax_create_pdf');
  // });



?>