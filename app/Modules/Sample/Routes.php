<?php

  $routes->get('primary', 'SampleController::index', [
      'as' => 'base.sample'
  ]);

?>