<?php

  #-- Pimpinan Beranda
  $routes->group('pimpinan', ['namespace' => 'App\Modules\Pimpinan\Dashboard\Controllers'], function($routes){
    $routes->get('', 'Dashboard::index', ['as' => 'pimpinan.beranda']);
    $routes->get('beranda', 'Dashboard::index');
  });

?>
