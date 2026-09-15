<?php 

#-----by MANSUR 20-04-2022---
if(!function_exists('load_gambar'))
{
    function load_gambar($foto)
    {
        try{
            $image = file_get_contents(FCPATH.'public/'.$foto);
            if($image){
                $response = service('response');
                $response
                    ->setStatusCode(200)
                    ->setContentType('image/jpg','image/jpeg','image/png','image/svg+xml')
                    ->setBody($image)
                    ->send();
            }else{
                throw new Exception("File not found");
            }
        }catch(Exception $e){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
       }
    }
}

if(!function_exists('load_gambar2')) //load foto private
{
    function load_gambar2($path)
    {
      // $sessio = \Config\Services::session();
      if(session('logged_in') != true){
        throw new Exception("File not found");
      }else{
        try
        {          
          $image = file_get_contents(DOKUMEN_PATH.$path);
          
          if($image){
              $response = service('response');
              $response
                  ->setStatusCode(200)
                  ->setContentType('image/jpg','image/jpeg','image/png','image/svg+xml')
                  ->setBody($image)
                  ->send();
          }else{
              throw new Exception("File not found");
          }
        }catch(Exception $e){
          throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
       } //try
      }        
    }
}

if(!function_exists('load_css'))
{
    function load_css($foto)
    {
        try{
            $image = file_get_contents(FCPATH.'public/'.$foto);
            if($image){
                $response = service('response');
                $response
                    ->setStatusCode(200)
                    ->setContentType('text/css')
                    ->setBody($image)
                    ->send();
            }else{
                throw new Exception("File not found");
            }
        }catch(Exception $e){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
       }
    }
}



if(!function_exists('plugin'))
{
    function plugin($path)
    {
        try{
            $image = file_get_contents(FCPATH.'public/plugins/'.$path);
            if($image){
                $response = service('response');
                $response
                    ->setStatusCode(200)
                    ->setContentType('image/jpg','image/jpeg','image/png')
                    ->setBody($image)
                    ->send();
            }else{
                throw new Exception("File not found");
            }
        }catch(Exception $e){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
       }
    }
}


#------------UPLOAD---------------
if(!function_exists('upload_new')) {
    function upload_new($element=null,$ukuran_settingan=0,$tipe=null,$lokasi='',$identitas='_', $location='writable'){	
        $ukuranfile   = 1024 * $ukuran_settingan;
        $artipefile   =   explode("|",$tipe);
        if( isset($_FILES["$element"]) && count($_FILES["$element"]["tmp_name"])){            
        // if( isset($_FILES["$element"]) && $_FILES["$element"]['error'][0] == 0 ){     
            #CEK TIPE FILE
            $cektipe    =   1;
            for($n = 0 ; $n < count($_FILES["$element"]["tmp_name"]) ; $n++){
                $xtipefile	= 	explode(".",$_FILES["$element"]["name"][$n]);
                $tipefile	=	strtolower($xtipefile[1]);
                if(!in_array($tipefile,$artipefile))	{
                    $cektipe    =   0;
                }
            }
            if($cektipe==0){ #tipe file salah
                $data['status']		=	false;
                $data['pesan']		=	'Gagal, tipe file tidak diijinkan';	
            }else{ #tipe file benar
                $file = array();			
                for($n = 0 ; $n < count($_FILES["$element"]["tmp_name"]) ; $n++){				
                    $part_info = pathinfo($_FILES["$element"]["name"][$n]);
                    if($location == 'public'){
                      $path = dirname($_SERVER["SCRIPT_FILENAME"]).'/public/uploads/'.$lokasi;                      
                    }else{
                      $path = dirname($_SERVER["SCRIPT_FILENAME"]).'/writable/uploads/'.$lokasi;
                    }
                    
                    // echo $path;exit();

                    $unik    = "$identitas"."_".MD5($lokasi.date('His')).'_';                    
                    $nm_file = $unik.$_FILES["$element"]["name"][$n];
                    // $label   =	$_FILES["$element"]["name"][$n];
                    
                    $xtipefile	= 	explode(".",$_FILES["$element"]["name"][$n]);
                    $tipefile	=	strtolower($xtipefile[1]);
                    $ukuran		=	$_FILES["$element"]["size"][$n];
                    // var_dump($nm_file);exit();
                    if($ukuran == 0){ #===ukuran file == 0
                      $data['status']		=	false;
                      $data['pesan']		=	"ukuran file 0 byte";
                    }else{
                      if($ukuran <= $ukuranfile){                    
                          // if($tipefile == 'pdf'){			
                          if(in_array($tipefile,$artipefile))	{
                            $array_non_image  = ['pdf','zip','rar'];
                            if(in_array($tipefile, $array_non_image)){ #===bukan IMAGE
                              #==== UPLOAD LAMA
                              if( move_uploaded_file( $_FILES["$element"]["tmp_name"][$n],$path.$nm_file ) ){ #---BERHASIL
                                  $file[]             =   $nm_file;
                                  $data['status']   = true;
                                  $data['pesan']    = 'berhasil'; 
                                  $data['path']     =   $lokasi;
                                  $data['namafile'] =   $file; #array
                                  $data['tipefile'] = $tipefile;
                              }else{
                                  $data['status']   = false;
                                  $data['pesan']    = "Gagal upload ";
                              }                              
                            }else{ #===IMAGE
                              #====== UPLOAD BARU
                              $imageTemp        = $_FILES["$element"]["tmp_name"][$n];
                              $imageUploadPath  = $path.$nm_file;
                              // $imageSize        = $_FILES["image"]["size"];
                              $compressedImage  = compressImage($imageTemp, $imageUploadPath, 20); 
                              if($compressedImage){ 
                                $file[]             =   $nm_file;
                                $data['status']   = true;
                                $data['pesan']    = 'berhasil'; 
                                $data['path']     =   $lokasi;
                                $data['namafile'] =   $file; #array
                                $data['tipefile'] = $tipefile;
                              }else{
                                $data['status']   = false;
                                $data['pesan']    = "Gagal upload ";
                              }
                            }                            
                              // if( move_uploaded_file( $_FILES["$element"]["tmp_name"][$n],$path.$nm_file ) ){ #---BERHASIL
                              //     $file[]             =   $nm_file;
                              //     $data['status']		=	true;
                              //     $data['pesan']		=	'berhasil';	
                              //     $data['path']     =   $lokasi;
                              //     $data['namafile']	=   $file; #array
                              //     $data['tipefile'] = $tipefile;
                              // }else{
                              //     $data['status']		=	false;
                              //     $data['pesan']		=	"Gagal upload ";
                              // }
                          }else{ #BUKAN PDF
                              // $this->session->set_flashdata('notifikasi', 'Gagal...#Dokumen yang diupload harus PDF...#error');
                              $data['status']		=	false;
                              $data['pesan']		=	'Gagal, jenis file tidak diijinkan';                        
                          }
                      }else{
                          // $this->session->set_flashdata('notifikasi', 'Gagal...#Dokumen terlalu besar..., maksimal 256kb #error');
                          $data['status']		=	false;
                          $data['pesan']		=	"Gagal, ukuran file terlalu besar , ukuran : $ukuran, maksimal $ukuran_settingan KB";	
                      }
                    }                    	
                    
                }
            }                        
                        
        }else{ #tidak ada file yang dipilih
            $data['status']		=	false;
            $data['pesan']		=	'Tidak ada file yang dipilih library';	
        }
        return $data;
    }
}

#=== compressed image
if(!function_exists('compressImage')) {
  function compressImage($source, $destination, $quality) { 
      // Get image info 
      $imgInfo = getimagesize($source); 
      $mime = $imgInfo['mime']; 
      
      // Create a new image from file 
      switch($mime){ 
          case 'image/jpeg': 
              $image = imagecreatefromjpeg($source); 
              break; 
          case 'image/png': 
              $image = imagecreatefrompng($source); 
              break; 
          case 'image/gif': 
              $image = imagecreatefromgif($source); 
              break; 
          default: 
              $image = imagecreatefromjpeg($source); 
      } 
      
      // Save image 
      imagejpeg($image, $destination, $quality); 
      
      // Return compressed image 
      return $destination; 
  }
}

#--- keterangan ----
#--- (id) = user_id
if(!function_exists('cekfolder')) {
  function cekfolder($folder='',$id=''){
    $tahun 			=	date('Y');
    $bulan      =   date('m');
    $cekpath1		=	  dirname($_SERVER["SCRIPT_FILENAME"])."/writable/uploads/$folder";      
    $owner      = 'apache';  
    // var_dump($cekpath1);exit();

    #===jika data baru set path_file_induk
    // $path_induk_file  = cekfolder("transaksi",$id);
    // $db   = \Config\Database::connect();
    // $cek  = $db->table("transaksi t")->select("*")->where("id",$id)->get()->getRow()->path_file_induk;        
    
    
    // if(is_null($cek) || $cek==''){ #===data baru
      if(!is_dir($cekpath1)){
          mkdir($cekpath1, 0775, true);
          chmod($cekpath1, 0775);
          chownr($cekpath1, $owner);

          $myfile = fopen($cekpath1."/index.php", "w") or die("Unable to open file!");
          $txt = "larangan";
          fwrite($myfile, $txt);            
          fclose($myfile);

      }
      
      $cekpath2 		="$cekpath1/$tahun";     //dirname($_SERVER["SCRIPT_FILENAME"])."/assets/dokumen/$folder/$tahun/";				
      if(!is_dir($cekpath2)){           
          mkdir($cekpath2, 0775, true);
          chmod($cekpath2, 0775);
          chownr($cekpath2, $owner);

          $myfile = fopen($cekpath2."/index.php", "w") or die("Unable to open file!");
          $txt = "larangan";
          fwrite($myfile, $txt);            
          fclose($myfile);
      }

      $cekpath3 		=	"$cekpath2/$bulan"; 		
      if(!is_dir($cekpath3)){
          mkdir($cekpath3, 0775, true);
          chmod($cekpath3, 0775);
          chownr($cekpath3, $owner);

          $myfile = fopen($cekpath3."/index.php", "w") or die("Unable to open file!");
          $txt = "larangan";
          fwrite($myfile, $txt);            
          fclose($myfile);
      }

      if($id != ''){
        $cekpath4 		=	"$cekpath3/$id"; 
        if(!is_dir($cekpath4)){
            mkdir($cekpath4, 0775, true);
            chmod($cekpath4, 0775);
            chownr($cekpath4, $owner);

            $myfile = fopen($cekpath4."/index.php", "w") or die("Unable to open file!");
            $txt = "larangan";
            fwrite($myfile, $txt);            
            fclose($myfile);
        }   
        $lokasi_jadi  = "$folder/$tahun/$bulan/$id/";
      }else{
        $lokasi_jadi  = "$folder/$tahun/$bulan/";
      }
      // $db->table("transaksi")->where("id",$id)->update(['path_file_induk'=>$lokasi_jadi]);
    // }
    // else{
    //   return $cek;
    // }
    // $db->close();    
    if($id != ''){
      return "$folder/$tahun/$bulan/$id/";  
    }else{
      return "$folder/$tahun/$bulan/";  
    }
    
      
  }
}


?>