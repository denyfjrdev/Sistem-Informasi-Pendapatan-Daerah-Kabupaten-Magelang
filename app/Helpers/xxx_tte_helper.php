<?php
use app\ThirdParty\phpqrcode\qrlib;

// defined('BASEPATH') or exit('No direct script access allowed');
$GLOBALS["TOKEN_TTE"]       =   "YmJtOm1hZ2VsYW5nNTY1MTEhQCMkJWJibQ==";// "ZW9mZmljZTptYWdlbGFuZzU2NTExIUAjJCU=";
$GLOBALS["URL_TTE"]         =   "http://103.115.104.181/"; //"http://sre.magelangkab.go.id/"; 

#$GLOBALS["TOKEN_TTE"]       =   "c3VyZWw6NTQzMjE=";
#$GLOBALS["URL_TTE"]         =   "http://sre-develop.magelangkab.go.id/";


#---// cek user //---
if (!function_exists('h_cek_user_tte')) {
  function h_cek_user_tte($user = null){    
    $curl = curl_init();
  
    curl_setopt_array($curl, array(
    CURLOPT_URL => $GLOBALS["URL_TTE"].'api/user/status/'.$user,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic '.$GLOBALS["TOKEN_TTE"],
        'Cookie: JSESSIONID=76476C36CF715F263307717DAA45E3E9'
    ),
    ));
  
    $response = curl_exec($curl);
  
    curl_close($curl);
    return $response;        
  }
}

#---// get TTE //---
if (!function_exists('h_get_tte')) {
  function h_get_tte($data=array()){
    // header('Content-Type: application/pdf');

    $sumber_file_pdf= $data['sumber_file_pdf'];
    $nama_file_pdf  = $data['nama_file_pdf'];    
    $nik            = $data['nik'];
    $passphrase     = $data['passphrase'];
    
    if(isset($data['linkQR'])) {$linkQR = $data['linkQR'];}else{$linkQR='';}
    if(isset($data['image'])) {$image = $data['image'];}else{$image='false';}
    if(isset($data['width'])) {$width = $data['width'];}else{$width=100;}
    if(isset($data['height'])) {$height = $data['height'];}else{$height=100;}
    if(isset($data['xAxis'])) {$xAxis = $data['xAxis'];}else{$xAxis=0;}
    if(isset($data['yAxis'])) {$yAxis = $data['yAxis'];}else{$yAxis=50;}
    if(isset($data['page'])) {$page = $data['page'];}else{$page=1;}
    if(isset($data['tampilan'])) {$tampilan = $data['tampilan'];}else{$tampilan='visible';}
    if(isset($data['tag_koordinat'])) {$tag_koordinat = $data['tag_koordinat'];}else{$tag_koordinat='#';}
    
    
    $f_file_pdf     =   curl_file_create($sumber_file_pdf,'application/pdf',$nama_file_pdf);   
    if($data['jenis'] == 'koordinat'){ #--// dengan koordinat
      $f_file_ttd     =   curl_file_create($data['imageTTD'],'image/png',$data['namafile_imageTTD']);   
      $data_post  = [
        'file'=> $f_file_pdf,  
        'imageTTD' => $f_file_ttd,
        'nik' => $nik,
        'passphrase' => $passphrase,
        'tampilan' => $tampilan,                
        'width' => $width,
        'height' => $height,
        'image' => $image,
        'tag_koordinat' => $tag_koordinat
      ];
    }
    // else{ #---// dengan QRcode
    //   $data_post = array(            
    //     'file'=> $f_file_pdf,            
    //     'nik' => $nik,
    //     'passphrase' => $passphrase,
    //     'tampilan' => $tampilan,
    //     'page' => $page,
    //     'xAxis' => $xAxis,
    //     'yAxis' => $yAxis,
    //     'width' => $width,
    //     'height' => $height,        
    //     'linkQR' => $linkQR
    //   );
    // }

    
    $curl = curl_init();
    $url    =   $GLOBALS["URL_TTE"].'api/sign/pdf';        
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST,1);                
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_post);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Authorization: Basic '.$GLOBALS["TOKEN_TTE"],
        'Cookie: JSESSIONID=07219EA7FD3181ECF91862DDD2B93C12'            
    ));        

    $response = curl_exec($curl);


    $header_size    = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header         = substr($response, 0, $header_size);
    $body           = substr($response, $header_size);

    curl_close($curl);

    return(["header_size"=>$header_size,"header"=>$header,"body"=>$body]);

  }
}

if (!function_exists('h_qrcode')) 
{
  function h_qrcode($link="")
  {

    
    // $tempDir = DOKUMEN_FISIK_PUBLIC."qrcode/";
    // $urlpath = PUBLICPATH.'uploads/qrcode/';
    // $tempDir  = DOKUMEN_FISIK_PATH."qrcode/";  

    #====cek folder qrcode
    $folder   =    DOKUMEN_FISIK_PATH."qrcode";
    if(!is_dir($folder)){
        mkdir($folder, 0775, true);
        chmod($folder, 0775);
        chownr($folder, "apache");

        $myfile = fopen($folder."/index.php", "w") or die("Unable to open file!");
        $txt = "larangan";
        fwrite($myfile, $txt);            
        fclose($myfile);
    }
    
    $codeContents = $link;
    
    // we need to generate filename somehow, 
    // with md5 or with database ID used to obtains $codeContents...
    $fileName = md5($codeContents).'.jpg';  
    $pngAbsoluteFilePath = DOKUMEN_FISIK_PATH."qrcode/".$fileName;    
    $urlRelativeFilePath = base_url("dokumen_private?tipe=jpg&path=qrcode/$fileName");
    
    // generating
    if (!file_exists($pngAbsoluteFilePath)) {
      QRcode::png($codeContents, $pngAbsoluteFilePath);      
    } else {
      unlink($pngAbsoluteFilePath);
      QRcode::png($codeContents, $pngAbsoluteFilePath);      
    }

    $data   = ["fullpath"=>$pngAbsoluteFilePath,"urlpath"=>"$urlRelativeFilePath"];
    if (file_exists($pngAbsoluteFilePath)) {
      return (["status"=>true,"data"=>$data]);
    }else{
      return (["status"=>false,"data"=>$data]);
    }
  }
}



?>