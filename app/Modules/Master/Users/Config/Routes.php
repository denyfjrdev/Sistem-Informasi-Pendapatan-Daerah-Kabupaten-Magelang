<?php

  $routes->group('master', ['namespace' => 'App\Modules\Master\Users\Controllers'], function($routes){
    $routes->get('/', 'UsersController::index', [
            'as' => 'master.default'
        ]);
  });

  $routes->group('master/users/', [
      'namespace' => 'App\Modules\Master\Users\Controllers'
  ], function ($routes) {

        $routes->get('/', 'UsersController::index', [
            'as' => 'users.index'
        ]);

        $routes->get('create', 'UsersController::create', [
            'as' => 'users.create'
        ]);

        $routes->post('store', 'UsersController::store', [
            'as' => 'users.store'
        ]);

        $routes->get('edit/(:num)', 'UsersController::edit/$1', [
            'as' => 'users.edit'
        ]);

        $routes->post('update/(:num)', 'UsersController::update/$1', [
            'as' => 'users.update'
        ]);

        $routes->post('delete/(:num)', 'UsersController::delete/$1', [
            'as' => 'users.delete'
        ]);
        
  });    

?>