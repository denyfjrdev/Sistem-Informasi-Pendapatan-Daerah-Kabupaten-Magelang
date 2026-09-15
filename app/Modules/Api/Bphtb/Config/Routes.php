<?php

  #-- BPHTB / PBB (sibphtbprima)
  $routes->group('api/bphtb', ['namespace' => 'App\Modules\Api\Bphtb\Controllers'], function($routes){
    $routes->get('', 'Load::index');
    $routes->get('load', 'Load::load');
    $routes->post('load', 'Load::load');
  });

?>
