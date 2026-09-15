<?php

  #-- Admin Beranda
  $routes->group('admin', ['namespace' => 'App\Modules\Admin\Dashboard\Controllers'], function($routes){
    $routes->get('', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');
  });

?>
