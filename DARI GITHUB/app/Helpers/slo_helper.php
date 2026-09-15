<?php 
  use CodeIgniter\HTTP\Response;  



  if ( ! function_exists('kirim_api')){  
    function kirim_api($url='',$method="POST",$param=[],$header=[]){
      #--- token user bisa dikirim lewat header atau param, jika keduanya ada maka yang dipakai token di header
      if(isset($header['user_id'])){
        $user_id = $header['user_id'];
      }else{
        $user_id = "0";
      }
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
          'Authorization: Bearer '.$header['token'],
          'Content-Type: text/plain'
        ),
      ));

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

      // return $response;  
    }  
  }

  if ( ! function_exists('kirim_slo')){
    function kirim_slo($url='',$method="POST",$param=[],$header=[]){
      $curl = curl_init();

      curl_setopt_array($curl, [
        CURLOPT_PORT => "8082",
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => json_encode($param),
        CURLOPT_HTTPHEADER => [
          "authorization: Bearer ".$header['token'],
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