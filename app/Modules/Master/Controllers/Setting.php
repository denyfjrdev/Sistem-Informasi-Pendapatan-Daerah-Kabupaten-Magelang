<?php 
namespace App\Modules\Master\Controllers;

use App\Modules\Master\Controllers\MasterBaseController;
use App\Libraries\Api;

class Setting extends MasterBaseController
{

  public function __construct(){      
    $this->api = new Api();   
  }

  function tahapan(){
    // $header["user_id"] = enkripsi_ci($this->data_user->user_id); // dikirim ke end point database service

    #--- ambil data layanan untuk ditampilkan di select option
    $body = [
      "select"      => "t.*,t.id as layanan_id",
      "nama_tabel"  => "ref_layanan t",      
    ];
        
    $hit_api   = json_decode($this->api->kirim_api($this->URL_DATABASE.'/api_master/get_tabel',"POST",$body,$this->header));
    $rlayanan = [];
    if($hit_api->status == true){
      $rlayanan = $hit_api->data;
    }

    #--- ambil data ref_role untuk ditampilkan di select option       
    $hit_api_role   = json_decode($this->api->kirim_api($this->URL_DATABASE.'/api_master/get_role',"POST",[],$this->header));
    
    $data = [
      "menu"            =>  'Pengaturan',
      "fiture"          =>  "Tahapan", 
      "hp"          => $this->hp,
      "data_user"   => $this->data_user,
      "datatables"  => true,
      "rlayanan"    => $rlayanan,
      "role"        => $hit_api_role->data
    ];
    return view('App\Modules\Master\Views\Setting\v_tahapan',$data);
  }
  
  // function simpan_tahapan(){
  //   $elemen       = ['id','layanan_id','nama_tahapan','keterangan','role','proses','urutan'];
  //   $post         = $this->request->getPost();

  //   #---- simpan data tahapan ke database melalui API internal
  //   $body         = null;
  //   foreach($elemen as $el){
  //     $body[$el]  = $post[$el];
  //   }         
  //   $header = [
  //     "user_id"     => enkripsi_ci($this->data_user->user_id)
  //   ];    
  //   $hit_api   = json_decode(kirim_api_internal(URL_DATABASE.'/api_master/simpan_tahapan',"POST",$body,$header));    


  //   if($hit_api->status == true){
  //     return redirect()->to(base_url('master/tahapan'))->with('success', 'Berhasil menyimpan tahapan');
  //   }else{
  //     return redirect()->to(base_url('master/tahapan'))->with('error', 'Gagal menyimpan tahapan');
  //   }
  // }

  function ajax_simpan_tahapan(){
    try{
      $elemen       = ['id','layanan_id','nama_tahapan','keterangan','role','proses','urutan'];
      $post         = $this->request->getPost();

      #---- simpan data tahapan ke database melalui API internal
      $body         = null;
      foreach($elemen as $el){
        $body[$el]  = $post[$el];
      }                 
      $hit_api   = json_decode($this->api->kirim_api($this->URL_DATABASE.'/api_master/simpan_tahapan',"POST",$body,$this->header));           
      $response = [
        "status"    => $hit_api->status,
        "message"   => $hit_api->message
      ];      
    } catch (\Exception $e) {
      #--- output standard respon error
      $message[]  = "Error: ".$e->getMessage();
      $response = [
        'status'  => false,
        'message' => ['message' => 'Gagal simpan data', 'details' => $message]
      ];
    }   
    
    return $this->response->setJSON($response);
  }  

  function ajax_get_tahapan(){    
    $body   = [
      "layanan_id"  => $this->request->getPost("layanan_id")
    ]; 
    $hit_api   = json_decode($this->api->kirim_api($this->URL_DATABASE.'/api_master/get_tahapan',"POST",$body,$this->header));
    if($hit_api->status == true){
      $response = [
        "status"    => true,
        "data"      =>  $hit_api->data
      ];
    }else{
      $response = [
        "status"  => false,
        "data"    =>  []
      ];
    }
    return $this->response->setJSON($response);
  }

  function ajax_hapus_tahapan(){
    try{      
      $body   = [
        "id"  => $this->request->getPost("tahapan_id")
      ]; 
      $hit_api   = json_decode($this->api->kirim_api($this->URL_DATABASE.'/api_master/hapus_tahapan',"POST",$body,$this->header));  
      if($hit_api->status == true){
        $message = $hit_api->message;
      }else{
        if(isset($hit_api->error)){
          $message = $hit_api->error;
        }else{
          $message = $hit_api->message;
        }        
      }
      $response = [
        "status"    =>  $hit_api->status,
        "message"   =>  $message
      ];  
    } catch (\Exception $e) {
      #--- output standard respon error
      $message[]  = "Error: ".$e->getMessage();
      $response = [
        'status'  => false,
        'message' => ['message' => 'Gagal hapus ', 'details' => $message]
      ];
    }

    return $this->response->setJSON($response);
  }

  
}