<?php

  $routes->group('admin/sync', ['namespace' => 'App\Modules\Admin\Sync\Controllers'], function($routes){
    $routes->get('', 'Sync::index');
    $routes->get('tick', 'Sync::tick');
    $routes->post('tick', 'Sync::tick');
    $routes->get('full', 'Sync::full');
    $routes->post('full', 'Sync::full');
  });
