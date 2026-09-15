<?php
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

class ApiBaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */

  // protected $helpers = ['url', 'form','sys','group','foto','verif','data'];
	// protected $helpers  = ['sys','url','data'];
  // protected $token    = "d03384b91b41000212eedb8a7ee767de4eaf8a7f";

	protected $helpers          = ['sys','url','data','security','wa'];
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
    $lanjut   = true;

    #=== Bearer Token
    $token    = getBearerToken();
    if(is_null($token)){
      $lanjut   = false;
      $message  = "Bearer token is null....";
    }else{
      if($this->token_static != $token){
        $lanjut   = false;
        $message  = "Bearer token salah....";
      }
    }

    if($lanjut == true){
      $headers    = apache_request_headers();
      // var_dump($headers);exit();
      $key        = $headers['Key'];        
        if(is_null($key)){ $key        = $headers['key']; }        
      if(is_null($key) || $key==''){ 
        $lanjut = false; 
        $message  .=  "<br>- cek header KEY";
      }        
      

      if($lanjut == true){
        $this->db = \Config\Database::connect();
        $cek_user_password  = $this->db->table("users")->where("token",$key)->where("token is not null")->get()->getRow();
        if(is_null($cek_user_password)){
          $lanjut   = false;
          $message  .= "<br>- KEY salah/tidak ketemu";
        }
      }

    }
    
    
    // if(!isset($this->param->token_user)){
    //   $message  = "Token User is null....";
    //   $lanjut   = false;
    // }else{ #===cek token di tabel users
    //   $this->db = \Config\Database::connect();
    //   $cek_token  = $this->db->table("users")->where("token",$token_user)->get()->getRow();
    //   if(is_null($cek_token)){ #=== token tidak valid
    //     $message  = "Token tidak valid....";
    //     $lanjut   = false;
    //   }
    // }
    
		if($lanjut == false){
      echo json_encode(["status"=>$status,"message"=>$message,"data"=>$data]);
      exit();
    } 
    $this->param = json_decode(file_get_contents('php://input'));  
    $this->response->setHeader('Content-Type', 'application/json'); 
		
	}

}
