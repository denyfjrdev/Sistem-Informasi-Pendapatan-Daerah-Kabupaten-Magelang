<?php 

#------ QUEUE -----
if ( ! function_exists('wa'))
{  
    function wa($data=[]){
      if(isset($data['pesan'])){ $pesan = $data['pesan'];}else{$pesan='';}
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://apibot.magelangkab.go.id/Api/wa/queue',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
          'service: sidering',
          'token: 183400df87206ffb946f1871f16be42b',
          'aplikasi: sidering queue'          
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


#------ SEND LANGSUNG -----
if ( ! function_exists('wa_langsung'))
{  
    function wa_langsung($data=[]){
      if(isset($data['pesan'])){ $pesan = $data['pesan'];}else{$pesan='';}
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://apibot.magelangkab.go.id/Api/wa/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
          'service: sidering',
          'token: 183400df87206ffb946f1871f16be42b',
          'aplikasi: sidering direct'          
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


if ( ! function_exists('cek_nomor'))
{  
    function cek_nomor($nomor){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apibot.magelangkab.go.id/Api/wa/cek_nomor/'.$nomor,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_POSTFIELDS =>json_encode($data),
          CURLOPT_HTTPHEADER => array(
            'service: sidering',
            'token: 183400df87206ffb946f1871f16be42b',
            'Content-Type: text/plain',
            'Cookie: ci_session=encb1gtj6v652ggfaud7pdik2srtqlqu',
            'aplikasi: sidering cek nomor'
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



#------ QUEUE MONITORING -----
if ( ! function_exists('wa_monitor'))
{  
    function wa_monitor($data=[]){
      if(isset($data['pesan'])){ $pesan = $data['pesan'];}else{$pesan='';}
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://apibot.magelangkab.go.id/Api/wa/queue',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
          'service: monitor',
          'token: c66a6be50951007a66e3db953c2cfa45',
          'aplikasi: WA Monitoring'
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
