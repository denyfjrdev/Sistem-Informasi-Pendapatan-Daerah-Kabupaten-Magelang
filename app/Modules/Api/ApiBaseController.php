<?php
namespace App\Modules\Api;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\Enkripsi;


class ApiBaseController extends Controller
{

  protected $helpers = ['url','slo','security','campuran'];
  protected $KEY_AUTH;
	
	/**
	 * Constructor.
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
    $this->KEY_AUTH   = env('KEY_AUTH');
    $this->get_token();
    $data     = json_decode($response->getBody(), true);       
    $lanjut   = true;

    if ($data['status'] ?? false) {
      $token = $data['token'] ?? null;
    }    
    
    // var_dump($token);exit();

    if(is_null($token)){            
      $lanjut   = false;
    }else{      
      if($token != $this->KEY_AUTH){
        $lanjut   = false;
      }else{
        $lanjut   = true;
      }      
    }

    // var_dump($data['token']);exit();

    if($lanjut == false){           
      echo json_encode(
        [
          "status"  =>  false,
          "message" =>  "Authorization tidak valid"
        ]
      );
      exit();
    }
    
    $this->param     = $this->request->getJSON(true); 

	}

  private function get_token()
  {
      $token = $this->request->getHeaderLine('Token');

      if (empty($token)) {
        return $this->response->setJSON([
            'status' => true,
            'message'  => "Authorisasi tidak ditemukan"
        ]);
      }

      $token = preg_replace('/^Bearer\s+/i', '', $token);

      return $this->response->setJSON([
          'status' => true,
          'token'  => $token
      ]);
  }   

}
