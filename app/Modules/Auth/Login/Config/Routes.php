<?php

  $routes->group('auth', ['namespace' => 'App\Modules\Auth\Login\Controllers'], function($routes){
    $routes->post('/', 'Auth::index',['as' => 'auth.index']);
    $routes->get('vlogin', 'Auth::vlogin',['as' => 'auth.login']);
    $routes->post('masuk', 'Auth::masuk');
    $routes->get('logout', 'Auth::logout');
    $routes->get('auto_login', 'Auth::auto_login');
  });


?>