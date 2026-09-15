<?php 

  namespace App\Libraries;
  use CodeIgniter\HTTP\Response;  

  class Api
  {
    // protected JWTConfig $config;

    public function __construct()
    {
        // $this->config = config('JWT');
    }

    
    function kirim_api($url='',$method="POST",$param=[],$header=[]){
      #--- token user bisa dikirim lewat header atau param, jika keduanya ada maka yang dipakai token di header
      $user_id      = $header['user_id'] ?? "0";
      $token_static = $header['token_static'] ?? "0";

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS =>json_encode($param),
        CURLOPT_HTTPHEADER => array(
          'Userid: '.$user_id,
          'Authorization: Bearer '.$token_static,
          'Content-Type: text/plain'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      return $response;  
    }  
      
    #----auto login dari MSS
    function api_auto_login($url='',$method="POST",$param=[],$header=[]){
      $curl = curl_init();

      $token_slo  = '';
      if(isset($header['Token_slo'])){
        $token_slo  = $header['Token_slo'];
      }

      curl_setopt_array($curl, [        
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => json_encode($param),
        CURLOPT_HTTPHEADER => [
          "Tokenslo: ".$token_slo,
          "Authorization: Bearer ".$header['Token'],
          "content-type: application/json"
        ],
      ]);

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        $respon = [
          "error" => "cURL Error #:" . $err
        ];        
      } else {
        $respon = $response;
      }  
      return $respon;    
    }    

  }

?>