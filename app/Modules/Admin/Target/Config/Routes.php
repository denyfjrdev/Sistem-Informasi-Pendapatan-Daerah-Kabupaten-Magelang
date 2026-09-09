<?php
  
  $routes->group('admin', ['namespace' => 'App\Modules\Admin\Target\Controllers'], function($routes){
    $routes->get('/', 'TargetController::index', [
            'as' => 'admin.default'
        ]);
  });

  
  $routes->group('admin/target', [
      'namespace' => 'App\Modules\Admin\Target\Controllers'
  ], function ($routes) {

    $routes->get('/', 'TargetController::index', [
        'as' => 'target.index'
    ]);

    $routes->get('data', 'TargetController::data', [
        'as' => 'target.data'
    ]);

    $routes->get('get', 'TargetController::get', [
        'as' => 'target.get'
    ]);

    $routes->post('save', 'TargetController::save', [
        'as' => 'target.save'
    ]);

    $routes->post('delete', 'TargetController::delete', [
        'as' => 'target.delete'
    ]);    

  });    

?>