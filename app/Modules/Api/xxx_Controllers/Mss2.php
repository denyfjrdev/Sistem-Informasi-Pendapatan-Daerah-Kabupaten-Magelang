<?php 
namespace Modules\Api\Controllers;
use Modules\Api\Controllers\ApiBaseController;
// use Modules\Api\Models\DataModel;

class Mss2 extends ApiBaseController
{
    
  public function __construct(){         
    
  }
  
  function index(){
    echo 'API...Rekap';
  }

  function simpan_user_baru(){
    $status   = 0; 
    $data     = []; 
    $pesan    = '---';

    $field  = [
      "nohp"      =>  $this->param->nohp,
      "nama_user" =>  $this->param->nama_user,
      "email"     =>  $this->param->email,
      "password"  =>  $this->param->password,
      "aktif"     =>  "ya",
      "role"      =>  $this->param->role,
      "token"     =>  md5($this->param->password)
    ];
    if(isset($this->param->created_at)){
      $field["created_at"]  = $this->param->created_at;
    }
    $data   = $field;
    $this->db->table("users")->insert($field);
    if($this->db->affectedRows() > 0){ #===BERHASIL UPDATE        
      $status = 1;
      $pesan  = "Proses berhasil";
      // $data_hasil   = $this->db->table("users")->select("users.*,users.id as user_id")->where("id",$user_id)->get()->getRowArray();
    }else{ #=== GAGAL update
      $status = 0;
      $pesan  = "Proses gagal";
    }
    $data   = ["pesan"=>$pesan];

    $respon = ["status"=>$status,"data"=>$data];
    echo json_encode($respon);    

  }  

}