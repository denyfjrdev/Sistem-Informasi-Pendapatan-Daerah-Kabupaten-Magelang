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

class LoginBaseController extends Controller
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
    $this->param    = json_decode(file_get_contents('php://input'));
    header('Content-Type: application/json');
		
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
    
    $status   = 0;
    $message[]  = "Anda belum login.....";
    $data     = [];

    #====cek bearer token    
    $token    = getBearerToken();
    // var_dump($token);exit();
    $lanjut   = true;
    if(is_null($token)){
      $message[]    = "Token Bearer null...."; $status  = 0;  $lanjut  = false;
    }else{
      if($token != $this->token_static){ #===Bearer token salah
        $message[]    = "Token tidak valid...."; $status  = 0;  $lanjut  = false;
      }
    }
    

    #====cek user dan password
    if($lanjut == true){                  
      if(is_null($this->param)){
        $lanjut   = false;
        $message[]  = "Payload body tidak dikenali Basecontroller ";
      }else{        
        if(isset($this->param->nohp) || isset($this->param->user_id)){
          $this->db = \Config\Database::connect();              
          if(isset($this->param->nohp)){              
            $nohp         = enkrip($this->param->nohp);
            // var_dump($nohp);exit();
            $cek_user     = $this->db->table("users")->where("nohp",$nohp)->get()->getRow();               
          }  
          
          if(isset($this->param->user_id)){
            $cek_user_id  = $this->db->table("users")->where("id",$this->param->user_id)->get()->getRow();
          }          
          if(!is_null($cek_user)){ $data_user = $cek_user; }
          if(!is_null($cek_user_id)){ $data_user = $cek_user_id; }
          
          // $test  = [
          //   "nohp"  =>  $nohp,
          //   "pass"  =>  $this->param->password,
          //   "nohp kiriman"  =>  $this->param->nohp
          // ];
          // var_dump($test);exit();
          // var_dump($data_user);exit();

          if(!is_null($data_user)){            
            $cek_user_password      = $this->db->table("users")->where("nohp",$nohp)->where("password",$this->param->password)->get()->getRow();
            $cek_user_password_id   = $this->db->table("users")->where("id",$this->param->user_id)->where("password",$this->param->password)->get()->getRow();
            
            if(!is_null($cek_user_password)){ $cek_password = $cek_user_password; }
            if(!is_null($cek_user_password_id)){ $cek_password = $cek_user_password_id; }            

            if(is_null($cek_password)){
              $lanjut     = false;
              $message[]  = "Password salah Based ";
            }
          }else{
            $lanjut     = false;
            $message[]  = "Gagal login BASED";       
          }
        }
      }
    }
      

    if($lanjut == false){      
      echo json_encode(["status"=>$status,"message"=>$message,"data"=>$data]);exit();
    }    
    
		
		$this->session  = \Config\Services::session();    
    
		
	}

  

}
