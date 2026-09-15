<?php 
namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Controllers\AuthBaseController;


class Auth extends AuthBaseController
{

  public function __construct(){      

  }

  function index(){
    echo 'Auth Index';
  } 

  function vlogin(){
    helper('captcha');
    $vals = [
        'img_path'   => FCPATH . 'captcha/',
        'img_url'    => base_url('captcha'),
        'font_path'  => FCPATH . 'fonts/arial.ttf',
        'img_width'  => 160,
        'img_height' => 50,
        'expiration' => 7200,
        'word_length'=> 5,
        'font_size'  => 20,
    ];  
    $captcha = generate_captcha($vals);
    session()->set('captcha', $captcha['word']);          

    $data  = [
      "judul"   => "Login SLO",
      "captcha" => $captcha['image'],
    ];
    
    return view('App\Modules\Auth\Views\v_login',$data);    
  }  

  #--- direk ke halaman sesuai role
  function masuk(){        
    // var_dump(session()->get('captcha'));exit();
    $pengaman = $this->request->getPost('captcha');
    if (!is_numeric($pengaman) || $pengaman != session()->get('captcha')) {            
      return redirect()->back()->with('error', 'Captcha salah');
    }        

    $phone_number = $this->request->getPost('phone_number');
    $password     = $this->request->getPost('password');        
    $login        = (object) $this->login([
      "phone_number"  => $phone_number,
      "password"      => $password
    ]);    
    // var_dump($login);exit();
    
    if($login->status == true){      
      #==== Login berhasil, arahkan ke halaman sesuai role
      $role = $login->data_user->role;      
      return redirect()->to(base_url($role.'/dashboard'));
    }else{
      #==== Login gagal, kembali ke halaman login dengan pesan error
      return redirect()->to(base_url('auth/vlogin'))->with('error', $login->pesan[1]);
    }
    
  }

  public function logout()
  {
    session()->destroy();
    return redirect()->to(base_url('auth/vlogin'))->with('success', 'Berhasil logout');
  }  

  private function login($param=[]){   
    http_response_code(200);
    header('Content-Type: application/json'); 
    $pesan[]      = 'Login SLO';  
    $status       = false;   
    $data_user    = [];     

    $param_user   = [
      "phone_number"  =>  $param['phone_number'],
      "password"      =>  $param['password']
    ];    
    $header = [
      "token" =>  $this->TOKEN_STATIC
    ];
    $json_login      = json_decode(kirim_slo($this->URL_DATABASE.'/auth/login',"POST",$param_user,$header));     
    // $json_login      = json_decode(kirim_slo());
    // var_dump($json_login);exit();       

    if($json_login->status == true){ #====AKUN sudah ada di MSS/SLO 
      $data_user = $json_login->data->detil;     
      $newdata = [
        'logged_in'    => TRUE,          
        "data_user"    =>  $data_user,
        "frame"        =>  TRUE,
      ];    
      
      $this->session->set($newdata);
      $status   = true;
      $pesan[]  = "Login berhasil....";
    }else{
      $status   = false;      
      $pesan[]  = "Error : ".json_encode($json_login->pesan);
    }

    $respon = [
      "status"    => $status,      
      "pesan"     => $pesan,
      "data_user" => $data_user
    ];    

    // echo json_encode($respon,JSON_PRETTY_PRINT);
    return $respon;

  }

  #--- auto login integrasi magelangkab
  function auto_login(){   
    $get_token      =  $this->request->getGet('token');
    $token          = dekrip($get_token,$this->TOKEN_MAGELANGKAB);
    $decode         = jwtAuth($token);

    // if($decode != false){
    //   $param_user   = [
    //     "phone_number"  =>  $decode->phone_number,
    //     "password"      =>  $decode->hash
    //   ];
    //   $header = [
    //     "token" =>  $this->TOKEN_STATIC
    //   ];
    //   $json_login      = json_decode(kirim_slo($this->URL_DATABASE.'/auth/login',"POST",$param_user,$header));
    //   if($json_login->status == true){ #====AKUN sudah ada di MSS/SLO 
    //     $data_user = $json_login->data->detil;     
    //     $newdata = [
    //       'logged_in'    => TRUE,          
    //       "data_user"    =>  $data_user,
    //       "frame"        =>  TRUE,
    //     ];    
        
    //     $this->session->set($newdata);        
    //     #==== Login berhasil, arahkan ke halaman sesuai role
    //     $role = $data_user->role;      
    //     return redirect()->to(base_url($role.'/dashboard'));
    //   }else{
    //     #==== Login gagal, kembali ke halaman login dengan pesan error
    //     echo "Login gagal, error : ".json_encode($json_login->pesan);
    //   }      
    // }

    // $test = [
    //   "asli" => $this->request->getGet('token'),
    //   "token decode" => $token,
    //   "decode jwt" => $decode
    // ];
    // echo json_encode($test);
    // exit();
    $data = [
      'judul'   => 'ini halaman test',
      "decode"  =>  $decode
    ];
    return view('App\Modules\Auth\Views\v_test',$data);  
  }


}