<?php

  $routes->group('pimpinan/sync', ['namespace' => 'App\Modules\Pimpinan\Sync\Controllers'], function($routes){
    $routes->get('', 'Sync::index');
    $routes->get('tick', 'Sync::tick');
    $routes->post('tick', 'Sync::tick');
    $routes->get('full', 'Sync::full');
    $routes->post('full', 'Sync::full');
  });
