<?php 
namespace Modules\Api\Controllers;

use Modules\Api\Controllers\LoginBaseController;

class Login extends LoginBaseController
{
    
  public function __construct(){            
    // $this->param      = json_decode(file_get_contents('php://input'));
  }
  
  function index(){
    echo 'API...';
  }
  
  function login(){    
    // $test   = dekrip("maCXpIWpaHp%2Bd7efrLmzd67IxQ%3D%3D");
    // var_dump($test);exit();

    $param      = $this->param;
    $nohp       = enkrip($this->param->nohp);      
    $password   = $param->password;

    $status     = 0;
    $message    = [];
    $data       = [];


    if(!isset($param->nohp)){
      $message[]  = "Parameter uername tidak valid....";
      echo json_encode(["status"=>$status,"message"=>$message,"data"=>$data]);exit();
    }
    if(!isset($param->password)){
      $message[]  = "Parameter password tidak valid....";
      echo json_encode(["status"=>$status,"message"=>$message,"data"=>$data]);exit();
    }



    // #===cek username    
    // // $password   = enkrip($param->password);

    $cek_username   = $this->db->table("users")->select("count(*) as total")
      ->where("nohp",$nohp)
      ->get()->getRowArray()["total"];      
    if($cek_username == 0){ #---user tidak valid
      $message[]  = "User tidak valid....";
      $status   = 0;
      $data     = [];      
    }else{ #---user ketemu
      $cek_password   = $this->db->table("users")
        ->select("email,token,nohp,nama_user,role,users.id as user_id,users.nohp,users.list_kategori_id")        
        ->where("nohp",$nohp)
        ->where("password",$password)
        ->get()->getRowArray();
      if(is_null($cek_password)){ #----password salah
        $message[]  = "Password tidak valid....";
        $status   = 0;
        $data     = [];        
      }else{ #---berhasil
        $status   = 1;
        $message[]  = "Berhasil login....";        
        $data     = [
          "key"       =>  $cek_password['token'],
          "role"      =>  $cek_password['role'],
          "nama_user" =>  dekrip($cek_password["nama_user"]),
          "user_id"   =>  $cek_password['user_id'],          
          "nohp"      =>  dekrip($cek_password['nohp']),
          "email"     =>  dekrip($cek_password['email']),
          "list_kategori_id" =>    $cek_password['list_kategori_id']        
        ];       
      }
    }
    // var_dump($data);exit();
    $respon     = ["status"=>$status,"message"=>$message,"data"=>$data];    
    echo json_encode($respon);exit();
    
  }

  function cek_user(){
    $param      = json_decode(file_get_contents('php://input'));
    $status   = 0; 
    $data     = [];    
    $nohp     = $param->nohp; 
    
    $cek  = $this->db->table("users")->select("users.*, users.id as user_id")->where("nohp",$nohp)->get()->getRow();
    if(is_null($cek)){ #=== tidak ada
      $status   = 0;
      $data     = ["pesan"=>"Data kosong..."];
    }else{ #===data sudah ada
      $status   = 1;
      $data     = ["user_id"=>$cek->user_id,"pesan"=>"Data dengan no WA $nohp , sudah ada..."];
    }
    
    $respon = ["status"=>$status,"data"=>$data];

    echo json_encode($respon);

  }  

  // function test(){
  //   // header('Content-Type: application/json');

  //   $secretKey = "your-secret-key";
  //   $originalText = "085737147686";    

  //   $key        = $secretKey;
  //   $data       = $originalText;

  //   $jwt_encode   = jwt_encode(["nohp"=>$data]);
  //   $jwt_decode   = jwt_decode($jwt_encode);

  //   // echo json_encode(["encode"=>$jwt_encode,"dekode"=>$jwt_decode]); exit();

  //   $enkrip   = encrypt($jwt_encode);
  //   $dekrip   = decrypt($enkrip);
  //   echo json_encode([
  //     "jwt_encode"  =>  $jwt_encode,
  //     "jwt_decode"  =>  $jwt_decode,
  //     "encrypt/simpan DB"=>$enkrip,
  //     "dekrip"=>$dekrip
  //   ]); 
  //   exit();

    
  


  //   // $cipher = "aes-256-cbc";
  //   // $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
  //   // $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
  //   // $enkrip =  base64_encode($iv . $encrypted);

  //   // $encryptedData  = $enkrip;
  //   // $cipher = "aes-256-cbc";
  //   // $data = base64_decode($encryptedData);
  //   // $ivLength = openssl_cipher_iv_length($cipher);
  //   // $iv = substr($data, 0, $ivLength);
  //   // $encryptedText = substr($data, $ivLength);
  //   // $dekrip =  openssl_decrypt($encryptedText, $cipher, $key, 0, $iv); 
    
  //   // $respon   = (["enkrip"=>$enkrip,"dekrip"=>$dekrip]);
  //   // echo json_encode($respon);
    
  //   // $encryptedText = encryptData($originalText, $secretKey);
  //   // echo "Encrypted: " . $encryptedText . "\n";

  //   // $decryptedText = decryptData($encryptedText, $secretKey);
  //   // echo "Decrypted: " . $decryptedText . "\n";    

  // }

  function test2(){
    // $enkrip_simpan  = "iOIFR92qmDmEDYZNax3ZVNtzUsz4awoKsjvHjMWUnYbi4G+2Q1UP1OEzv0xZGoe5z0GBwJnKGQyuEO7CC1DyPsJORqEHU5vJG4chsAxBLs0CkYUOwcErVJmNdDrZT0amDXxOV8Vbss1YLxfkbFkZDNDyd73DcL8Ya4sqwJxeQxo7Lxis4T8h90ZkeVbR51Hra7rQxW0alYKSPtbiFCsWnVqcD/OT3neWmdK34rfiJ1E=";
    $nohp           = "085737147686";
    // $nohp   = "aku sayang kamu";
    // $nohp   = "aku cinta bangsa indonesia";
    // $nohp   = "nganu....ya data ini harus aman";
    // $nohp   = "91837481874661636155";
    // $nohp   = "1";
    // $nohp = "chan56483@gmail.com";

    // $secretKey = "your-strong-secret-key";
    // $data = ["user_id" => 123, "email" => "user@example.com"];
    // $dekrip         = generateJWT($data, $secretKey);
    // var_dump($dekrip);

    // $jwt_decode     = jwt_decode($dekrip['asli']);
    // var_dump($jwt_decode);

    // $enkrip   = base64_decode(base64_encode($nohp));
    // var_dump($enkrip);
    $enkrip   = enkrip($nohp);
    $dekrip   = dekrip($enkrip);
    echo json_encode(["enkirip"=>$enkrip,"dekrip"=>$dekrip]);
    // var_dump($enkrip);
  }

  


  // function simpan_user_baru(){
  //   $status   = 0; 
  //   $data     = []; 
  //   $pesan    = '---';

  //   $field  = [
  //     "nohp"      =>  $this->param->nohp,
  //     "nama_user" =>  $this->param->nama_user,
  //     "email"     =>  $this->param->email,
  //     "password"  =>  $this->param->password,
  //     "aktif"     =>  "ya"
  //   ];
  //   $data   = $field;
  //   $this->db->table("users")->insert($field);
  //   if($this->db->affectedRows() > 0){ #===BERHASIL UPDATE        
  //     $status = 1;
  //     $pesan  = "Proses berhasil";
  //     // $data_hasil   = $this->db->table("users")->select("users.*,users.id as user_id")->where("id",$user_id)->get()->getRowArray();
  //   }else{ #=== GAGAL update
  //     $status = 0;
  //     $pesan  = "Proses gagal";
  //   }
  //   $data   = ["pesan"=>$pesan];

  //   $respon = ["status"=>$status,"data"=>$data];
  //   echo json_encode($respon);    

  // }
  

}


