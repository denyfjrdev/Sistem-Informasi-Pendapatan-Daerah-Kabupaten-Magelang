<?php
#===Database
namespace Modules\Api\Controllers;

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

class MssBaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */

  // protected $helpers = ['url', 'form','sys','group','foto','verif','data'];
	protected $helpers          = ['sys', 'foto','verif','url','data','security'];    
	protected $token_static     = TOKEN_STATIC;
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
    
    $status   = 0;
    $message  = "Anda belum login.....";
    $data     = [];

    #====cek bearer token
    $token    = getBearerToken();
    $lanjut   = true;
    if(is_null($token)){
      $message    = "Token Bearer null...."; $status  = 0;  $lanjut  = false;
    }else{
      if($token != $this->token_static){ #===Bearer token salah
        $message    = "Token tidak valid...."; $status  = 0;  $lanjut  = false;
      }
    }
          

    #====cek user dan password
    if($lanjut == true){
      $this->param = json_decode(file_get_contents('php://input'));
      
      $this->db = \Config\Database::connect();        
      $nohp         = enkrip($this->param->nohp);
      $cek_nohp     = $this->db->table("users")->where("nohp",$nohp)->get()->getRow();      
      if(!is_null($cek_nohp)){ $data_user = $cek_nohp; }      

      if(is_null($data_user)){        
        $lanjut   = false;
        $message  .= "<br>- user name tidak ketemu BASED ";          
      }

    }
      


    if($lanjut == false){
      echo json_encode(["status"=>$status,"message"=>$message,"data"=>$data,"nohp"=>$nohp,"nohp asli"=>$this->param->nohp]);exit();
    }

		
		$this->session = \Config\Services::session();
    
		
	}

  

}
