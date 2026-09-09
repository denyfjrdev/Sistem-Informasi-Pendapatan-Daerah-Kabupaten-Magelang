<?php
namespace App\Modules\Auth\Login\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AuthBaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */	
  protected $helpers = ['url','slo','security'];
  // protected $token_static     = TOKEN_STATIC;
	
	/**
	 * Constructor.
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
    $this->TOKEN_STATIC       = getenv('TOKEN_STATIC');
    $this->TOKEN_MAGELANGKAB  = getenv('TOKEN_MAGELANGKAB');


    #=== Bearer Token Static
    $token    = getBearerToken();    
    
    if(is_null($token)){
      $lanjut   = false;
      $message  = "Bearer token is null....";
    }else{
      if($this->TOKEN_STATIC != $token){
        $lanjut   = false;
        $message  = "Bearer token salah....";
      }
    }    
    
    $this->param  = json_decode(file_get_contents('php://input'));  
    $this->session = \Config\Services::session();      
    $this->URL_DATABASE = getenv('URL_DATABASE');
	}

}
