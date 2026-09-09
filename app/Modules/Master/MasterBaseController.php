<?php
namespace App\Modules\Master;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\Enkripsi;


class MasterBaseController extends Controller
{

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

    // $this->URL_DATABASE         = env('URL_DATABASE');
    // $this->TOKEN_STATIC_OPD     = env('TOKEN_STATIC_OPD');    
    // $this->TOKEN_STATIC_PUBLIC  = env('TOKEN_STATIC_PUBLIC');
        
    $this->session    = \Config\Services::session();      
    $this->data_user  = (object)session()->get('data_user');    
    
    #---elemen header untuk dikirim ke API database
    $this->enkrip             = new Enkripsi(); 
    $this->user_id_enkrip     = $this->enkrip->enkripsi_ci($this->data_user->user_id,env('TOKEN_ENKRIP_CI'));           
    $this->user_id_dekrip     = $this->enkrip->dekripsi_ci($this->data_user->user_id,env('TOKEN_ENKRIP_CI'));           
    

	}

}
