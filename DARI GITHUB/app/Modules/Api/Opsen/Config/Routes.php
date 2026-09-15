<?php

  #-- Opsen PKB
  $routes->group('api/opsen', ['namespace' => 'App\Modules\Api\Opsen\Controllers'], function($routes){
    $routes->get('', 'Load::index');
    $routes->get('load', 'Load::load');    
  });


?>