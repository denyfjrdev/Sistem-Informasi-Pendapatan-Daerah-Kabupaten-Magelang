<?php 

  namespace App\Libraries;

  use Firebase\JWT\JWT;
  use Firebase\JWT\Key;
  use Config\JWT as JWTConfig;

  class Enkripsi
  {
    // protected JWTConfig $config;

    public function __construct()
    {
        // $this->config = config('JWT');
    }

    function jwt_encode( $payload,$api_secret ) {
      try {        
        if ( empty( $payload ) ) {
          throw new Exception( 'Unauthorized' );
        }
        
        $encode = JWT::encode($payload, $api_secret, 'HS256');

        return $encode;;

      } catch ( Exception $error) {
        return false;
      }

    }
      
    function jwt_decode( $payload, $api_secret ) {
      try {        
        if ( empty( $payload ) ) {
          throw new Exception( 'Unauthorized' );
        }

        if (preg_match('/Bearer\s(\S+)/', $payload, $matches)) {
          $payload = $matches[1];
        }

        $decode    = JWT::decode($payload, new Key($api_secret, 'HS256'));              
        
        return $decode;

      } catch ( Exception $error) {
        return false;
      }

    }

    #------enkrip gawan CI4
    function enkripsi_ci($param='',$token=''){
        $config         = new \Config\Encryption();
        $config->key    = $token; //'U6Z4gTdwh6zqpZZRsAeLmmjEo1ZKGviLzBtyhf1m0fSB5c7pW3uFg78m0XwuSvtI';
        $config->driver = 'OpenSSL';

        $encrypter = \Config\Services::encrypter($config);
        $enkripsi = str_replace(array('+', '/', '='), array('-', '_', '~'), base64_encode($encrypter->encrypt($param)));
        return $enkripsi;
      
    }
        
    #------dekrip gawan CI4
    function dekripsi_ci($param='',$token=''){
      
        try {
            $config         = new \Config\Encryption();
            $config->key    = $token; //'U6Z4gTdwh6zqpZZRsAeLmmjEo1ZKGviLzBtyhf1m0fSB5c7pW3uFg78m0XwuSvtI';
            $enkripsi = \Config\Services::encrypter($config)->decrypt(base64_decode(str_replace(array('-', '_', '~'), array('+', '/', '='), $param)));
            if(!$enkripsi){
                throw new Exception("Error");
            }else{
                return $enkripsi;
            }
        }catch(Exception $e){
            //error tidak melakukan apa-apa
        }
    }

    
    function encode_custom($string,$key) {      
      $result = '';
      $test = "";
      for($i=0; $i<strlen($string); $i++) {
        $char     = substr($string, $i, 1);
        $keychar  = substr($key, ($i % strlen($key))-1, 1);
        $char     = chr(ord($char)+563-5729-ord($keychar)-56511+7809-97);        
        $result .=$char;
      }
      return urlencode(base64_encode($result));
    }
  
  
    function decode_custom($string,$key) {      
      $result = '';
      $string = base64_decode(urldecode($string));
      for($i=0; $i<strlen($string); $i++) {
        $char     = substr($string, $i, 1);
        $keychar  = substr($key, ($i % strlen($key))-1, 1);
        $char     = chr(ord($char)-563+5729+ord($keychar)+56511-7809+97);
        $result .=$char;
      }
      return $result;
    }     
      

  }

?>