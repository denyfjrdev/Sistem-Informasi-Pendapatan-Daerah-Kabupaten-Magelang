<?php
namespace App\Modules\Publik;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\Enkripsi;


class PublikBaseController extends Controller
{

  protected $helpers = ['url','slo','security','campuran'];
  // protected $token_static;
		
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------  
    $this->hp     = cek_hp();
    #---ambil dari .ENV
    if(!is_null(env('MOBILE')) && env('MOBILE') == 1){
      $this->hp = 1;
    }
    
	}

}
