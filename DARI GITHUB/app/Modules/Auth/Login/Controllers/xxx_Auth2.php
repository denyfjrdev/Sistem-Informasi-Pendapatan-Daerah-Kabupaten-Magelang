<?php 
namespace Modules\Auth\Controllers;

use Modules\Auth\Controllers\AuthBaseController2;
// use Modules\Auth\Models\UserloginModel;

class Auth2 extends AuthBaseController2
{

    public function __construct(){      
      // $this->UserModel    = new UserloginModel;      
    }

    function mss_pertama(){
      // var_dump(session("kode_desa"));exit();
      // var_dump($this->session);exit();
      $domain_mss   =       $this->session->get('domain');
      if(!is_null($domain_mss) && $domain_mss == 'com.magelangkab.magelang_smart_service'){
        $url        = $this->session->get("url");
        $domain     = $this->session->get("domain");        
        $roles      = $this->session->get("roles");
        $nik        = $this->session->get("nik");
        $kode_desa  = $this->session->get("kode_desa");
        $email      = $this->session->get("email");
        // var_dump($domain);exit();

        if($nik == '' || $kode_desa == ''){
          lempar_salah(1,"NIK dan Kode Desa harus diisi, silahkan buka menu profile di aplikasi MSS anda....");exit();
        }
        
        if($domain=='com.magelangkab.magelang_smart_service'){  
          $random   = randomString(3);               
          $field  = [
            "nama_user" =>  $this->session->get("fullname"),
            "nohp"  => $this->session->get("phone_number"),
            "aktif" =>  "ya",
            "created_at"  =>  sekarang(),
            "registered"  =>  "mss",
            "salt"  =>  $random,
            "email" =>  $email,
            "nik" =>  $this->session->get("nik"),
            "role"  =>  "pemohon",
            "password"  =>  $this->session->get("password"),
            "kode_desa" => $kode_desa,
            "nik" =>  $nik
          ];
          $this->db->table("users")->insert($field);
          if($this->db->affectedRows() >0 ){
            $id         = $this->db->insertID();                                                         
            $cek_user   = $this->db->table("users u")->select("u.*,u.id as user_id")->where("id",$id)->get()->getRowArray();            
            #====new session
            $newdata = [
              "logged_in"     => TRUE,
              "user_id"       => $cek_user['user_id'],
              "role"  =>  "pemohon",
              "mss" => 1
            ];
            $this->session->set($newdata);            
            return redirect()->to($url);
          }else{
            $this->session->destroy();
            lempar_salah(1,"Gagal simpan akun..., silahkan coba lagi !");            
          }
                  
        }
      }
    }    

}