<?php

  $routes->group('admin', ['namespace' => 'App\Modules\Admin\Informasi\Controllers'], function($routes){
    $routes->get('informasi', 'Informasi::index', ['as' => 'admin.informasi']);
  });

?>
