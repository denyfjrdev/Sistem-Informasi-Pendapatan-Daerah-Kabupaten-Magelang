<?php 
namespace Modules\Api\Controllers;

// use Modules\Api\Controllers\LoginBaseController;
use Modules\Api\Controllers\PublikBaseController;

class Publik extends PublikBaseController
{
  public function __construct(){            
    // $this->param      = json_decode(file_get_contents('php://input'));
  }
  
  function index(){
    echo json_encode(["status"=>true]);
  }

  function publik_transaksi_get_tabel(){
    $status   = 0; 
    $data     = [];    
    $pesan    = '---';
    
    $tabel  = $this->param->tabel;
    $cek    = $this->db->table("$tabel t")->select("t.*");
    if(isset($this->param->id)){
      $id   = $this->param->id;
      $cek  = $cek->where("id",$id);
      $data   = $cek->get()->getRowArray();
    }else{
      $data   = $cek->get()->getResultArray();
    }
    
    if(is_null($data)){      
      $status   = 0;
      $pesan    = "Gagal get data";
    }else{      
      $status   = 1;
      $pesan    = "Berhasil get data";
    }

    $respon = ["status"=>$status,"pesan"=>$pesan,"data"=>$data];
    echo json_encode($respon);     
  }

  function cek_user(){
    $param    = $this->param;
    $status   = 0; 
    $data     = [];    
    $pesan    = '---';


    // #---cek user di DB
    // $nohp   = enkrip($this->param->nohp);
    // $cek_data    = $this->db->table("users u")->select("u.*,u.id as user_id")->where("nohp",$nohp)->get()->getRowArray();
    // if(!is_null($cek_data)){ #---ketemu            
    //   $data     = [
    //     "key"       =>  $cek_data['token'],
    //     "role"      =>  $cek_data['role'],
    //     "nama_user" =>  dekrip($cek_data["nama_user"]),
    //     "user_id"   =>  $cek_data['user_id'],          
    //     "nohp"      =>  dekrip($cek_data['nohp']),
    //     "email"     =>  dekrip($cek_data['email']),
    //     "list_kategori_id" =>    $cek_data['list_kategori_id'],
    //     "roles"     =>  $cek_data['roles'],
    //     "aktif"     =>  $cek_data['aktif']
    //   ];      
    //   $pesan    = "Data ketemu...";
    //   $status   = 1;
    // }else{ #---tidak ketemu
    //   $pesan    = "Data tidak ketemu...";      
    // }

    $respon = ["status"=>$status,"pesan"=>$pesan,"data"=>$data];
    echo json_encode($respon);   
  }

  function add_user(){
    $param  = $this->param;
    $status   = 0; 
    $data     = [];    
    $pesan    = '---';

    if(isset($param->nama_user)){ $field['nama_user'] = $param->nama_user; }
    if(isset($param->nohp)){ $field['nohp'] = $param->nohp; }
    if(isset($param->aktif)){ $field['aktif'] = $param->aktif; }    
    if(isset($param->update_at)){ $field['update_at'] = $param->update_at; }
    if(isset($param->token)){ $field['token'] = $param->token; }
    if(isset($param->email)){ $field['email'] = $param->email; }
    if(isset($param->created_at)){ $field['created_at'] = $param->created_at; }
    if(isset($param->update_at)){ $field['update_at'] = $param->update_at; }
    $this->db->table("users")->insert($field);
    if($this->db->affectedRows() > 0){ #===BERHASIL UPDATE        
      $status       = 1;
      $cek_data         = $this->db->table("users")->select("users.*,users.token as key,users.id as user_id")->where("nohp",$field['nohp'])->get()->getRowArray();
      $data     = [
        "key"       =>  $cek_data['token'],
        "role"      =>  $cek_data['role'],
        "nama_user" =>  dekrip($cek_data["nama_user"]),
        "user_id"   =>  $cek_data['user_id'],          
        "nohp"      =>  dekrip($cek_data['nohp']),
        "email"     =>  dekrip($cek_data['email']),
        "list_obyek_id" =>    $cek_data['list_obyek_id'],
        "roles"     =>  $cek_data['roles'],
        "aktif"     =>  $cek_data['aktif']
      ];      
      $pesan        = "Berhasil";
    }else{ #=== GAGAL update
      $status       = 0;      
      $pesan        = "Gagal ";
    }

    $respon = ["status"=>$status,"pesan"=>$pesan,"data"=>$data];
    echo json_encode($respon);    

  }

  function update_user(){
    $param  = $this->param;
    $status   = 0; 
    $data     = [];    
    $pesan    = '---';

    if(isset($param->nama_user)){ $field['nama_user'] = $param->nama_user; }
    if(isset($param->nohp)){ $field['nohp'] = $param->nohp; }
    if(isset($param->aktif)){ $field['aktif'] = $param->aktif; }    
    if(isset($param->update_at)){ $field['update_at'] = $param->update_at; }
    if(isset($param->token)){ $field['token'] = $param->token; }
    if(isset($param->email)){ $field['email'] = $param->email; }
    if(isset($param->role)){ $field['role'] = $param->role; }
    if(isset($param->update_at)){ $field['update_at'] = $param->update_at; }

    $this->db->table("users")->where("id",$param->user_id)->update($field);
    if($this->db->affectedRows() > 0){ #===BERHASIL UPDATE        
      $status       = 1;
      $cek_data         = $this->db->table("users")->select("users.*,users.token as key,users.id as user_id")->where("id",$param->user_id)->get()->getRowArray();
      $data     = [
        "key"       =>  $cek_data['token'],
        "role"      =>  $cek_data['role'],
        "nama_user" =>  dekrip($cek_data["nama_user"]),
        "user_id"   =>  $cek_data['user_id'],          
        "nohp"      =>  dekrip($cek_data['nohp']),
        "email"     =>  dekrip($cek_data['email']),
        "list_obyek_id" =>    $cek_data['list_obyek_id'],
        "roles"     =>  $cek_data['roles'],
        "aktif"     =>  $cek_data['aktif']
      ];       
      $pesan        = "Berhasil update";
    }else{ #=== GAGAL update
      $status       = 0;      
      $pesan        = "Gagal update";
    }

    $respon = ["status"=>$status,"pesan"=>$pesan,"data"=>$data];
    echo json_encode($respon);    

  }
  
  
}