<?php 
namespace App\Modules\Master\Controllers;

use App\Modules\Master\Controllers\MasterBaseController;
use App\Libraries\Api;


class Users extends MasterBaseController
{

  public function __construct(){      
    $this->api = new Api();   
  }

  function index(){   
    #--- ambil data ref_role untuk ditampilkan di select option       
    $hit_api_role   = json_decode($this->api->kirim_api($this->URL_DATABASE.'/api_master/get_role',"POST",[],$this->header));
    // var_dump($hit_api_role);exit();

    $data = [
      "menu"            =>  'Pengaturan',
      "fiture"          =>  "User",      
      "hp"        => $this->hp,
      "data_user" => $this->data_user,
      "datatables"  => true,
      "role"        => $hit_api_role->data
    ];
    return view('App\Modules\Master\Views\Users\v_user',$data);
  } 

  function cek_list_data_user(){    
    $role_filter        = $this->request->getPost("role_filter");

    #--- body
    // $body          = get_param_list_data();         
    $body = [
      "draw"   => $this->request->getPost('draw'),
      "start"  => $this->request->getPost('start'),
      "length" => $this->request->getPost('length'),
      "search" => $this->request->getPost('search')['value'] ?? '',
      "order"  => $this->request->getPost('order')
    ];   
    $body['role_filter']  = $role_filter;      
    $data   =   $this->api->kirim_api($this->URL_DATABASE.'/api_master/list_user',"POST",$body,$this->header);        
    echo $data;
  }


  
  function list_user(){   
    // $header["user_id"]  = $this->header;
    // $role_filter        = $this->request->getPost("role_filter");

    // #--- body
    // $body          = get_param_list_data();    
    // $body['role_filter']  = $role_filter;      
    // $data   =   kirim_api_internal(URL_DATABASE.'/api_master/list_user',"POST",$body,$header);    
    
    // echo $data;
        $data = [
            [1, 'Andi', 'andi@gmail.com'],
            [2, 'Budi', 'budi@gmail.com'],
            [3, 'Citra', 'citra@gmail.com'],
            [4, 'Dedi', 'dedi@gmail.com'],
            [5, 'Eka', 'eka@gmail.com']
        ];

        return $this->response->setJSON([
            "draw" => 1,
            "recordsTotal" => 5,
            "recordsFiltered" => 5,
            "data" => $data
        ]);    
  }    

  function ajax_simpan_user(){
    $header["user_id"]  = $this->header;
    try{
      $elemen       = ['id','nama_user','nohp','email','role','aktif'];
      $post         = $this->request->getPost();

      #---- simpan data tahapan ke database melalui API internal
      $body         = null;
      foreach($elemen as $el){
        $body[$el]  = $post[$el];
      }                 
      $hit_api   = json_decode(kirim_api(URL_DATABASE.'/api_master/simpan_user',"POST",$body,$header));           
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
  
  function ajax_simpan_roles(){
    $header["user_id"]  = $this->header;
    $status             = false;
    try{      
      $post         = $this->request->getPost();

      #---- simpan data tahapan ke database melalui API internal
      $body         = [
        "nohp"        =>  $post['nohp'],
        "add_roles"   =>  array($post['role'])
      ];
      
      $hit_api   = json_decode(kirim_api(URL_DATABASE.'/api_master/simpan_roles',"POST",$body,$header));      

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
  
}