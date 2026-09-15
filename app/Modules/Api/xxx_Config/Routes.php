<?php

  $routes->group('api', ['namespace' => 'Modules\Api\Controllers'], function($routes){
      $routes->get('/', 'Auth::index');
  });

  $routes->group('publik_akses', ['namespace' => 'Modules\Api\Controllers'], function($routes){    
    $routes->post('', 'Publik::index');
    $routes->post('cekuser', 'Publik::cek_user');  
    $routes->post('tambah_user', 'Publik::add_user');  
    $routes->post('ubah_user', 'Publik::update_user');
    $routes->post('ambil_isi_tabel', 'Publik::publik_transaksi_get_tabel');  
  });  

?>