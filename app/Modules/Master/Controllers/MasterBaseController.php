<?php
namespace App\Modules\Master\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\Enkripsi;


class MasterBaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */	
  protected $helpers = ['url','slo','security','campuran'];
  // protected $token_static;
	
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
    $this->hp     = cek_hp();        
    cek_login($this->hp);     

    $this->URL_DATABASE = env('URL_DATABASE');
    $this->TOKEN_STATIC_OPD = env('TOKEN_STATIC_OPD');    
        
    $this->session    = \Config\Services::session();      
    $this->data_user  = session()->get('data_user');   
    
    #---elemen header untuk dikirim ke API database
    $enkrip             = new Enkripsi(); 
    $user_id_enkrip     = $enkrip->enkripsi_ci($this->data_user->user_id,env('TOKEN_ENKRIP_CI'));       
    $this->header = [      
      "user_id"           => $user_id_enkrip,
      "token_static"      =>  $this->TOKEN_STATIC_OPD
    ]; 
    set_parent_url_session();

	}

}
