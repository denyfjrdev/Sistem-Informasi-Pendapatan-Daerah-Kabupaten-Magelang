<?php
namespace Modules\Auth\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;

class AuthBaseController2 extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['sys','url'];  
	
	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		$this->db = \Config\Database::connect();
		$this->session = \Config\Services::session();

    // var_dump($this->session->get("user_id"));exit();

    // if(!is_null(session('user_id'))){
		//   $this->data_user     = h_get_data_user(session('user_id'))['data'];      
    // }else{
    //   return redirect()->to(base_url('auth'));  
    // }
	}

}
