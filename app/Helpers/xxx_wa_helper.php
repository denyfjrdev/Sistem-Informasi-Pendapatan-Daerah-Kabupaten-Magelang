<?php 
  $GLOBALS["URL_APIBOT"]      = 'http://103.115.104.149/apibot';

  #------ QUEUE -----
  if ( ! function_exists('wa'))
  {  
      function wa($data=[]){
        if(isset($data['pesan'])){ $pesan = $data['pesan'];}else{$pesan='';}
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $GLOBALS["URL_APIBOT"].'/wa/queue',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_POSTFIELDS =>json_encode($data),
          CURLOPT_HTTPHEADER => array(
            'service: laporbup',
            'token: aa9c5ffb97d053342e1af1efe22e9dfc',
            'aplikasi: Lapor Bupati queue'          
          ),
        ));

        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            $errno = curl_errno($curl);
        }
        
        curl_close($curl);

        if (isset($error_msg)) {
            $response   =   json_encode(["response" => false, "error_msg" => $error_msg, 'errno' => $errno]);
        }        

        return $response; //array
      }
  }

  
  #=== OFFICIAL
  $GLOBALS["URL_OFFICIAL"]    = "https://app.maxchat.id/api";
  $GLOBALS["TOKEN_OFFICIAL"]  = "wyk0mnc8peb6hp1b1oz0e";  
  if ( ! function_exists('wa_official_reply'))
  {
    function wa_official_reply($data=[]){
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $GLOBALS["URL_OFFICIAL"].'/messages/reply',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer '.$GLOBALS["TOKEN_OFFICIAL"],
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      return $response;
    }
  }


?>