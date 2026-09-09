<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function() {
    return redirect()->to(route_to('base.sample'));
});


foreach (glob(APPPATH . 'Modules/*', GLOB_ONLYDIR) as $modulePath) {
    $moduleName = basename($modulePath);
    $routeFile  = $modulePath . '/Routes.php';

    if (file_exists($routeFile)) {
        /**
         * Membuat route group dengan prefix sesuai nama module
         * (contoh: /pelayanan, /kalenderkegiatan)
         */
        $routes->group(strtolower($moduleName), [
            'namespace' => "Modules\\$moduleName\\Controllers"
        ], function ($routes) use ($routeFile) {
            require $routeFile;
        });
    }
}
