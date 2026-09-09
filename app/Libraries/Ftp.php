<?php
  namespace App\Libraries;
  class Ftp
  {
    public function __construct()
    {
      $this->session = \Config\Services::session();
    }

    private function ftp_konek(){
      $message    = "Default/";
      $success    = false;
      // if($this->session->get('logged_in') == true){
        $ftp_server = env('FTP_HOST');
        $ftp_user   = env('FTP_AKUN');
        $ftp_pass   = env('FTP_PASS');        
        $conn_id    = false;
        
        try {   
          if(env('APP_SERVER') == 'localhost'){
            $conn_id = ftp_ssl_connect($ftp_server) or die("Tidak bisa konek ke $ftp_server"); //-- localhost
          }else{
            $conn_id = ftp_connect($ftp_server) or die("Tidak bisa konek ke $ftp_server"); // server
          }                   
          if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {  
            ftp_pasv($conn_id, true);        
            $success  = true;
            $message  .=  "berhasil konek";
          }else{
            $conn_id  = false;
            $message  .=  "Gagal konek";
          }                        
        } catch (\Exception $e) {
          $success  = false;
          $message  .= $e->getMessage();
        } 
      // }else{
      //   $message  .=  "Anda tidak login";
      // }

      return (['success'=>$success,"message"=>$message,"conn_id"=>$conn_id]);
    }

    private function make_sub_folder($array_folder=[],$index=0){
      $path   = '';
      for($i=0;$i<=$index;$i++){
        $path   .= '/'.$array_folder[$i];
      }    
      return $path;
    }    

    private function copy_sem($ftp_path=''){    
      $konek  = $this->ftp_konek(); 
      if($konek['success'] == true){
        $conn_id              = $konek['conn_id'];

        $local_file_index     = ROOTPATH.'writable/tmp/index.html';
        $local_file_htaccess  = ROOTPATH.'writable/tmp/.htaccess';  
        
        $remote_file_index     = $ftp_path.'/index.html';
        $remote_file_htaccess  = $ftp_path.'/.htaccess';
        
        ftp_put($conn_id, $remote_file_index, $local_file_index, FTP_ASCII);
        ftp_put($conn_id, $remote_file_htaccess, $local_file_htaccess, FTP_ASCII);  
        
        ftp_close($conn_id);    
      }
    }    

    #----------------------public function
    function ftp_make_folder($folder='',$root_upload='writable/uploads'){
      $konek        = $this->ftp_konek();
      $root_server  = ROOTPATH;
      $root_ftp     = env('ROOT_APP_FTP');
      $status       = false;
      $pesan[]      = '';      
      
      $lanjut     = true;
      if($konek['success'] == true){        
        $conn_id         = $konek['conn_id'];
        $array_folder   = explode("/",$folder);
        for($i=0;$i < count($array_folder);$i++){
          $path       = $this->make_sub_folder($array_folder,$i);
          $real_path  = $root_server.$root_upload.$path;          
          $path_ftp   = $root_ftp.$root_upload.$path;


          if(!is_dir($real_path)){            
            ftp_mkdir($conn_id, $path_ftp);
            $this->copy_sem($path_ftp);              
          }  
          if(!is_dir($real_path)){
            $pesan[]  = 'Folder gagal '.$real_path;
            $lanjut   = false;
            ftp_close($conn_id);
            break;
          }
        }
        
        if($lanjut==true){
          $real_folder  = $root_server.'/'.$root_upload.'/'.$folder;
          if(is_dir($real_folder)){
            $status   = true;
            $pesan[]  = 'Success '.$real_folder;
          }else{
            $pesan[]  = 'Folder gagal';
          }            
        }
        ftp_close($conn_id);  
      }else{
        $pesan[]  = 'Gagal konek FTP';
      }
      return (["status"=>$status,"pesan"=>$pesan,"folder"=>$folder]);
    }    

    function ftp_upload($local_file='',$remote_file=''){
      $status     = false;
      if(is_file($local_file)){      
        $konek      = $this->ftp_konek();      
        $pesan[]    = 'Upload';
        if($konek['success'] == true){          
          $pesan[]  = $local_file;
          $conn_id  = $konek['conn_id'];
          if (ftp_put($conn_id, $remote_file, $local_file, FTP_ASCII)) {
            $status   = true;
            $pesan[]  = 'Berhasil upload';
          }else{
            $pesan[]  = 'Gagal upload';  
          }                                   
          ftp_close($conn_id);
        }else{        
          $pesan[]  = 'Gagal konek FTP';
        }
      }else{
        $pesan[]  = 'Local file tidak tersedia';
      }

      return (["status"=>$status,"pesan"=>$pesan]);
    }

    function ftp_delete($file=''){
      $konek  = $this->ftp_konek();
      if($konek['success']==true){
        $conn_id  = $konek['conn_id'];        
        if (ftp_delete($conn_id, $file)) {      
          return true;
        }else{
          return false;
        }
        ftp_close($conn_id);        
      }else{
        return false;
      }
    }

  }
?>