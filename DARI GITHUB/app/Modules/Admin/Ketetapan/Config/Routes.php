<?php
  
  // $routes->group('admin', ['namespace' => 'App\Modules\Admin\Ketetapan\Controllers'], function($routes){
  //   $routes->get('/', 'TargetController::index', [
  //           'as' => 'admin.default'
  //       ]);
  // });

  
  $routes->group('admin/ketetapan', [
      'namespace' => 'App\Modules\Admin\Ketetapan\Controllers'
  ], function ($routes) {

    $routes->get('/', 'KetetapanController::index', [
        'as' => 'ketetapan.index'
    ]);

    $routes->get('data', 'KetetapanController::data', [
        'as' => 'ketetapan.data'
    ]);

    $routes->get('get', 'KetetapanController::get', [
        'as' => 'ketetapan.get'
    ]);

    $routes->post('save', 'KetetapanController::save', [
        'as' => 'ketetapan.save'
    ]);

    $routes->post('delete', 'KetetapanController::delete', [
        'as' => 'ketetapan.delete'
    ]);  

  });    

?>