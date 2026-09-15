<?php

  $routes->group('api/sync', ['namespace' => 'App\Modules\Api\Sync\Controllers'], function($routes){
    $routes->get('', 'Sync::index');
    $routes->get('full', 'Sync::full');
    $routes->post('full', 'Sync::full');
    $routes->get('tick', 'Sync::tick');
    $routes->post('tick', 'Sync::tick');
  });
