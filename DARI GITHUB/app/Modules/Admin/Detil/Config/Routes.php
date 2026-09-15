<?php
  
  // $routes->group('admin', ['namespace' => 'App\Modules\Admin\Target\Controllers'], function($routes){
  //   $routes->get('/', 'TargetController::index', [
  //           'as' => 'admin.default'
  //       ]);
  // });

  
  $routes->group('admin/detil', [
      'namespace' => 'App\Modules\Admin\Detil\Controllers'
  ], function ($routes) {

    $routes->get('/', 'DetilController::index', [
        'as' => 'admin.detil.index'
    ]);

    $routes->get('desa', 'DetilController::desa', [
        'as' => 'admin.detil.desa'
    ]); 

  });    

?>