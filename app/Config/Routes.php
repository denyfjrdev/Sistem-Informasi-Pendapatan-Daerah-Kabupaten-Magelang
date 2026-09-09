<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/home', 'Home::index');
$routes->get('/error', 'Home::error');
$routes->get('/lokal', 'Lokal::index');

$routes->get('/', function() {
  return redirect()->to(route_to('auth.login'));
});


foreach (glob(APPPATH . 'Modules/*/*/Config/Routes.php') as $route) {
  require $route;
}
