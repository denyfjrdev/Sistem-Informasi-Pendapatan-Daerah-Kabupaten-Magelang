<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
// protected $helpers = ['url','slo','security','campuran'];

abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;
    // protected $helpers = ['url','security'];

    // protected string $feature = '';

    // protected function render(string $view, $data = [], array $extra = [])
    // {
    //     $default = [
    //         'applicationName' => 'Sample',
    //         'featureName' => $data['featureName'] ?? $this->feature,
    //         // 'menus' => $this->getMenu(),
    //         // 'user' => $this->auth->user(),
    //         // 'roles' => $this->auth->getRoles()
    //     ];

    //     $module = $this->module ?? $this->getModuleName();

    //     if (!empty($data)) {
    //         $data = ['data' => $data];
    //     }

    //     return view("Modules\\{$module}\\Views\\{$view}", array_merge($default, $extra, $data));
    // }


    // protected function getModuleName(): string
    // {
    //     $class = static::class;

    //     $parts = explode('\\', $class);

    //     return $parts[1] ?? '';
    // }    

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url','security'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }
}
