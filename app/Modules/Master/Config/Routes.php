<?php

  #-- Master
  $routes->group('master', ['namespace' => 'App\Modules\Master\Controllers'], function($routes){
    $routes->get('', 'Dashboard::index');    
    $routes->get('dashboard', 'Dashboard::index');    

    // controller Setting.php
    $routes->get('tahapan', 'Setting::tahapan');
    $routes->post('ajax_simpan_tahapan', 'Setting::ajax_simpan_tahapan');
    $routes->post('ajax_get_tahapan', 'Setting::ajax_get_tahapan');    
    $routes->post('ajax_hapus_tahapan', 'Setting::ajax_hapus_tahapan');

    // controller Users.php
    $routes->get('users', 'Users::index');
    $routes->post('list_user', 'Users::list_user');
    $routes->get('list_user', 'Users::list_user');
    $routes->post('ajax_simpan_user', 'users::ajax_simpan_user');
    $routes->post('cek_list_data_user', 'Users::cek_list_data_user');    

    $routes->post('ajax_simpan_roles', 'Users::ajax_simpan_roles');    
  });

  // $routes->group('master/setting/golab', ['namespace' => 'App\Modules\Master\Controllers'], function($routes){

  //   #---jenis uji
  //   $routes->get('jenis_uji', 'Setting::jenis_uji');
  // });

?>