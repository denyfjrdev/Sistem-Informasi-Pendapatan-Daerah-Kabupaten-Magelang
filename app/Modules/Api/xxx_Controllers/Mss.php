<?php 
#===Database
namespace Modules\Api\Controllers;

use Modules\Api\Controllers\MssBaseController;

class Mss extends MssBaseController
{
    
  public function __construct(){            
    $this->param      = json_decode(file_get_contents('php://input'));
  }
  
  function index(){
    echo 'MSS...';
  }
  

  function mss_cek_nohp(){
    $param      = json_decode(file_get_contents('php://input'));
    $status   = 0; 
    $data     = [];    
    $nohp     = enkrip($param->nohp); 
    
    $cek  = $this->db->table("users")->select("users.*, users.id as user_id")->where("nohp",$nohp)->get()->getRow();
    if(is_null($cek)){ #=== tidak ada
      $status   = 0;
      $data     = ["pesan"=>"Data kosong..."];
    }else{ #===data sudah ada
      $status   = 1;
      $data     = ["nohp"=>$cek->nohp,"pesan"=>"Data dengan no WA $nohp , sudah ada..."];
    }
    
    $respon = ["status"=>$status,"data"=>$data];

    echo json_encode($respon);
  }  
  

  function mss_sinkron_mss(){
    // $user_id    =  $this->param->user_id;
    $nohp                   =  enkrip($this->param->nohp);
    $field["password"]      = $this->param->password;
    $field["update_at"]     = sekarang();
    if(isset($this->param->role)){
      $field['role']  = $this->param->role;
    }

    
    $this->db->table("users")->where("nohp",$nohp)->update($field); 
    if($this->db->affectedRows() > 0){ #===BERHASIL UPDATE        
      $status = 1;
      $data_hasil   = $this->db->table("users")->select("users.*,users.id as user_id")->where("nohp",$nohp)->get()->getRowArray();
      $pesan    = "Berhasil";
    }else{ #=== GAGAL update
      $status = 0;
      $data_hasil = [];
      $pesan    = "Gagal ";
    }
    
    $respon   = ["status"=>$status,"pesan"=>$pesan,"data"=>$data_hasil];   
    echo json_encode($respon); 
  }  
  

}


