<?php
  use CodeIgniter\HTTP\Response;
  // use Firebase\JWT\JWT;
  // use Firebase\JWT\Key;
  use App\Libraries\SaferCrypto;
  use App\Libraries\Api;
  use App\Libraries\Enkripsi;

  //SaferCrypto

  if ( ! function_exists('getBearerToken')){
    function getBearerToken() {
        $headers = getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }  
  }

  /** 
   * Get header Authorization
   * */
  if ( ! function_exists('getAuthorizationHeader')){
    function getAuthorizationHeader(){
      // var_dump($_SERVER);exit();
        $headers = null;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            // var_dump($_SERVER["HTTP_AUTHORIZATION"]);exit();
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['HTTP_AUTHORIZATION'])) {
                $headers = trim($requestHeaders['HTTP_AUTHORIZATION']);
            }
        }
        return $headers;
    } 
  } 


  #=====AUTH FOR MSS
  if ( ! function_exists('cek_login'))
  {  
    function cek_login($hp=0)
    {                

      // $url      = get_url();  
      $request = service('request');      
      $session  = \Config\Services::session(); 
      $session->set("mss",$hp);    
      $header     = getallheaders(); 
      $api        = new Api();
      $enkrip     = new Enkripsi();
      $token_slo  = null;
      $decode     = false;
      
      try{
        #---- TESTER
        if($_SERVER['X-Authorization-Lokal']){ // ini hanya untuk coba2 saja di lokal          
          $token_slo = $_SERVER['X-Authorization-Lokal'];
        }else{ #--- mode beneran, non aktifkan variable di X-Authorization-Lokal di file .env
        
          if(isset($header['X-Authorization'])){ //pertama load dari MSS, hapus session header
            if(isset($_SESSION['header'])){
              unset($_SESSION['header']);
            }        
          }      

          if($hp==0){ #----dibuka pake PC             
            if (!isset($session->logged_in) || empty($session->logged_in) || $session->logged_in != TRUE) {                        
              throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda belum login');
            }
          }else{ #===dibukak pake android MSS      
            #--cek session header
            if(is_null($session->get("header"))){ #---tidak ada session header          
              $session->remove('user_id');
              $session->remove('logged_in');
              $session->remove('nohp');
              $session->set("header",$header);            
            }else{
              $header = $session->get("header"); #===ambil dari session
            }

            #---kecilkan semua huruf header
            $header = array_change_key_case($header, CASE_LOWER);
            if (isset($header['x-authorization'])) {
              $token_slo = strtolower($header['x-authorization']);
            }
                        
            if(isset($header['x-authorization'])){          
              $token_slo  = $header['x-authorization'];
            }                
                    
          }

        }

        if(env('TOKEN_JWT')){
          $api_secret = env('TOKEN_JWT');
        }else{
          throw new \CodeIgniter\Exceptions\PageNotFoundException('Token JWT belum di set di file .env');
        }
        $decode   = $enkrip->jwt_decode($token_slo,$api_secret);
        
      } catch (Exception $e) {
        $error = $e->getMessage();
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Error...!'.$error);
      }  
      
      
      
      #---jika decode gagal maka lempar error, jika valid bikin session
      if($decode==false){
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Token tidak valid');
      }else{  #---- make session di sini
        #---cek roles, jika roles tidak ada di array maka lempar error
        // var_dump($decode);exit();        
        $array_roles = json_decode($decode->roles);        
        $rolesValid = [
          "bue_admin",
          "bue_master",          
        ];

        if (empty(array_intersect($array_roles, $rolesValid))) {
          throw new \CodeIgniter\Exceptions\PageNotFoundException('Roles tidak valid');
        }
        
        #----lolos sampai sini brarti valid, bikin session (admin,master,pimpinan)
        $db   = \Config\Database::connect();
        $cek  = $db->table("users")->where("uuid",$decode->uuid)->get()->getRow();
        if(in_array("sijaka_admin", $array_roles)){
          $role = "sijaka_admin";  
        }elseif(in_array("sijaka_master", $array_roles)){
          $role = "sijaka_master"; 
        }else{
          $role = "user";
        }
        if($role == "user"){
          throw new \CodeIgniter\Exceptions\PageNotFoundException('Roles tidak diizinkan');
        }

        $data_user_slo = [
            "uuid"          => $decode->uuid,
            "nohp"          => $enkrip->encode_custom($decode->phone_number,env('TOKEN_ENKRIP_CI')),
            "email"         => $enkrip->encode_custom($decode->email,env('TOKEN_ENKRIP_CI')),
            "email_gov"     => $enkrip->encode_custom($decode->email_gov,env('TOKEN_ENKRIP_CI')),
            "nama_user"     => $enkrip->encode_custom($decode->fullname,env('TOKEN_ENKRIP_CI')),
            "aktif"         => "ya",
            "role"          => $role,
            "created_at"    => date("Y-m-d H:i:s")
        ];        
        if(is_null($cek)){ #--user belum ada di database, maka insert user baru                    
          $db->table("users")->insert($data_user_slo);          
        }else{
          $id   = $cek->id;
          unset($data_user_slo['created_at']);
          unset($data_user_slo['uuid']);
          unset($data_user_slo['aktif']);

          $db->table("users")->where("id",$id)->update($data_user_slo);
        }

        $data_user =  $db->table("users")->where("uuid",$decode->uuid)->get()->getRowArray();
        $data_user['user_id'] = $enkrip->enkripsi_ci($data_user['id'],env('TOKEN_ENKRIP_CI'));
        $session->set("data_user", $data_user);
        $session->set("logged_in",TRUE);
        
      }
      // var_dump($decode);exit();
      
      
    } // tutup fungsi
  }


  

?>