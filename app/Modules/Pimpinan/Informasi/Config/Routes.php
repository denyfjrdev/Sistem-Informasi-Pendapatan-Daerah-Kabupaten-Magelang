<?php

  $routes->group('pimpinan', ['namespace' => 'App\Modules\Pimpinan\Informasi\Controllers'], function($routes){
    $routes->get('informasi', 'Informasi::index', ['as' => 'pimpinan.informasi']);
  });

?>
