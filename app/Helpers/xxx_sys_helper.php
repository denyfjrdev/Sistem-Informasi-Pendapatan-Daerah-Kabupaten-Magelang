<?php
  use CodeIgniter\HTTP\Response;
  use Firebase\JWT\JWT;
  use Firebase\JWT\Key;



/**
 * ESLAB
 *
 * INDONESIA
 *
 * 
 *
 * This code created by Sutriman for his personal project and for his Labs Productions (ESLAB).
 * If you find this code form the internet without Sutriman's permission maybe you
 * should have by asking before using this code.
 *
 * 
 *
 * @package    ESLAB Webcore
 * @author     Sutriman
 * @copyright  2020 Sutriman, ESLAB
 * @link       https://sutriman.com
 * @since      Version 1.0 ESLAB Webcore
 * @since      Version 4.0.0 CI4 as Base
 * @filesource
 */

if ( ! function_exists('render'))
{
    function render(string $name, array $data = [], array $options = [])
    {
        return view(
            '_layouts/layout_fo',
            [
                'content' => view($name, $data, $options),
            ],
            $options
        );
    }
}
if ( ! function_exists('render_bo'))
{
    function render_bo(string $name, array $data = [], array $options = [])
    {
        return view(
            '_layouts/layout_bo',
            [
                'content' => view($name, $data, $options),
            ],
            $options
        );
    }
}

if ( ! function_exists('render_top'))
{
    function render_top(string $name, array $data = [], array $options = [])
    {
        return view(
            '_layouts/layout_bo_top',
            [
                'content' => view($name, $data, $options),
            ],
            $options
        );
    }
}

if ( ! function_exists('site_name'))
{
    function site_name()
    {
        $settings = new \Config\Settings();
        $site_name = $settings->siteName;
        return $site_name;
    }
}

if ( ! function_exists('tag_line'))
{
    function tag_line()
    {
        $settings = new \Config\Settings();
        $tag_line = $settings->siteTagLine;
        return $tag_line;
    }
}

if ( ! function_exists('dashboard'))
{
    function dashboard()
    {
        $settings = new \Config\Settings();
        $dashboard = $settings->dashboard;
        return $dashboard;
    }
}

if ( ! function_exists('homepage'))
{
    function homepage()
    {
        $settings = new \Config\Settings();
        $homepage = $settings->homepage;
        return $homepage;
    }
}

if ( ! function_exists('current_version'))
{
    function current_version()
    {
        $settings = new \Config\Settings();
        $current_version = $settings->currentVersion;
        return $current_version;
    }
}

if ( ! function_exists('username'))
{
    function username()
    {
        $settings = new \Config\Settings();
        $session = \Config\Services::session();
        return $session->username;
    }
}

if ( ! function_exists('user_id'))
{
    function user_id()
    {
        $settings = new \Config\Settings();
        $session = \Config\Services::session();
        return $session->user_id;
    }
}

if ( ! function_exists('userphoto'))
{
    function userphoto()
    {
        $settings = new \Config\Settings();
        $session = \Config\Services::session();
        return $session->photo;
    }
}


if ( ! function_exists('user'))
{
    function user($param = '')
    {
        $settings = new \Config\Settings();
        $session = \Config\Services::session();
        $user = array(
            'username' => $session->username,
            'photo' => $session->photo
            );
        return $user[$param];
    }
}

if ( ! function_exists('protect_acct'))
{
    function protect_acct()
    {
        $session = \Config\Services::session();
        if (!isset($session->logged_in) || empty($session->logged_in) || $session->logged_in != TRUE) {
            return redirect()->to(base_url().'/auth/Auth/login'); 
        }
    }
}

if ( ! function_exists('protect_ajax'))
{
    #no direct access allowed
    function protect_ajax()
    {
        $session = \Config\Services::session();
        if (!isset($session->logged_in) || empty($session->logged_in) || $session->logged_in != TRUE) {
            return redirect()->to(base_url().'/auth/Auth/login'); 
        }
    }
}

if ( ! function_exists('restrict'))
{
    function restrict()
    {   
        protect_acct();

        $menu = new App\Libraries\Menu();
        $request = \Config\Services::request();
        
        if($menu->restrict() == FALSE){
            if ($request->isAJAX()){
                // exit("Error! Don't have permission to access on this uri.");
                print_r("Error! Don't have permission to access on this uri.");  exit();
            }else{
                // return false;
                // return redirect(base_url('auth/Auth/login'));
                print_r("Error! Don't have permission to access on this uri."); exit();
                // exit("Error! Don't have permission to access on this uri.");
                // return redirect()->to(base_url().'/auth/Auth/login');
            }
        } else {
            return TRUE;
        }
    } 
}



if ( ! function_exists('enkripsi'))
{
    function enkripsi($param)
    {
        $config         = new \Config\Encryption();
        // $config->key    = 'd1sPETE1k4N_@)@%';
        // $config->driver = 'OpenSSL';

        // $encrypter = \Config\Services::encrypter($config);
        // $enkripsi = str_replace(array('+', '/', '='), array('-', '_', '~'), base64_encode($encrypter->encrypt($param)));        
        // return $enkripsi;

        $config->driver = 'OpenSSL';        
        // Your CI3's 'encryption_key'
        $config->key = hex2bin('64c70b0b8d45b80b9eba60b8b3c8a34d0193223d20fea46f8644b848bf7ce67f');
        // Your CI3's 'cipher' and 'mode'
        $config->cipher = 'AES-128-CBC';

        $config->rawData        = false;
        $config->encryptKeyInfo = 'encryption';
        $config->authKeyInfo    = 'authentication';

        $encrypter = service('encrypter', $config);
        $ciphertext = $encrypter->encrypt($param);
        return $ciphertext;
    }
    
}

if ( ! function_exists('deskripsi'))
{
    function deskripsi($param)
    {
       
        try {
            $config         = new \Config\Encryption();
            $config->key    = 'd1sPETE1k4N_@)@%';
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
}



if ( ! function_exists('download'))
{
    function download($param) //foto display dan download
    {
        if(!session('logged_in')){
          throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if(($image = file_get_contents(WRITEPATH.'uploads/akta_kelahiran/'.$param)) === FALSE){
            show_404();
        }
        return $this->response->download(WRITEPATH.'uploads/akta_kelahiran/'.$param, NULL);
  

         

  }

}


#---ADD BY MANSUR 24-04-2022---
if ( ! function_exists('randomString'))
{
  function randomString($length = 6) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}
}


if ( ! function_exists('randomStringCampuran'))
{
  function randomStringCampuran($length = 6) {
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}
}


if ( ! function_exists('sekarang'))
{
  function sekarang() {
    $now  = date("Y-m-d H:i:s");
    return $now;
	}
}

#==== tanggal angka
function tanggal_angka($date=null,$show=null)
{
	$array_hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu','Minggu');
	$array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus',
	'September','Oktober', 'November','Desember');
	
	if($date == null) {
		$formatTanggal = '';
	} else {
		$date = strtotime($date);
		$hari = $array_hari[date('N',$date)];
		$tanggal = date ('j', $date);
		$bulan = date('n',$date); //$array_bulan[date('n',$date)];
		$tahun = date('Y',$date);
		$opt = '';
		if( $show <> null ){
			$jam = date('H:i',$date);
			$opt = ' - <span class="muted">'.$jam.'</span>';
		}
		if($bulan<=9){$bulan = "0".$bulan;}
		if($tanggal<=9){$tanggal = "0".$tanggal;}
		$formatTanggal = $tanggal ."-". $bulan ."-". $tahun.$opt ;
	}
	
	return $formatTanggal;
}

#===== tanggal huruf 
function tanggal_huruf($date=null,$show=null)
{
	$array_hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu','Minggu');
	$array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus',
	'September','Oktober', 'November','Desember');
	
	if($date == null) {
		$formatTanggal = '';
	} else {
		$date = strtotime($date);
		$hari = $array_hari[date('N',$date)];
		$tanggal = date ('j', $date);
		$bulan = $array_bulan[date('n',$date)]; //date('n',$date); //
		$tahun = date('Y',$date);
		$opt = '';
		if( $show <> null ){
			$jam = date('H:i',$date);
			$opt = ' - <span class="muted">'.$jam.'</span>';
		}
		// if($bulan<=9){$bulan = "0".$bulan;}
		if($tanggal<=9){$tanggal = "0".$tanggal;}
		$formatTanggal = $tanggal ." ". $bulan ." ". $tahun.$opt ;
	}
	
	return $formatTanggal;
}


#----CHOWN -R
function chownr($path, $owner)
{
    if (!is_dir($path))
        return chown($path, $owner);

    $dh = opendir($path);
    while (($file = readdir($dh)) !== false)
	  {
        if($file != '.' && $file != '..')
		    {
          $fullpath = $path.'/'.$file;
          if(is_link($fullpath))
              return FALSE;
          elseif(!is_dir($fullpath) && !chown($fullpath, $owner))
                  return FALSE;
          elseif(!chownr($fullpath, $owner))
              return FALSE;
        }
    }

    closedir($dh);

    if(chown($path, $owner))
        return TRUE;
    else
        return FALSE;
}

//<span class="badge badge-warning badge-pill">
if ( ! function_exists('convert_role'))
{
  function convert_role($role='') {
    switch ($role) {
      case 'umum':
        $sts  = '<span class="badge badge-pill badge-warning"> <i class="fa fa-user"></i> '.$role.'</span>';
        break;
      case 'desa':
        $sts  = '<span class="badge badge-pill badge-primary"> <i class="fa fa-user"></i> '.$role.'</span>';
        break;
      case 'puskesmas':
        $sts  = '<span class="badge badge-pill badge-info"> <i class="fa fa-user"></i> '.$role.'</span>';
        break;
      default:
        $sts  = '<span class="badge badge-pill badge-default"> <i class="fa fa-user"></i> '.$role.'</span>';
    }

    return $sts;
  }
}


if ( ! function_exists('convert_status'))
{
  function convert_status($status='') {
    switch ($status) {
    case 'menunggu':
      $sts  = '<span class="badge badge-pill badge-warning"><i class="fa fa-exclamation"></i> '.$status.'</span>';
      break;
    case 'revisi':
      $sts  = '<span class="badge badge-pill badge-primary"><i class="fa fa-edit"></i> '.$status.'</span>';
      break;
    case 'setuju':
      $sts  = '<span class="badge badge-pill badge-success"><i class="fa fa-check"></i> '.$status.'</span>';
      break;      
    case 'ditolak':
      $sts  = '<span class="badge badge-pill badge-danger"><i class="fa fa-ban"></i> '.$status.'</span>';
      break;      
    case 'selesai':
      $sts  = '<span class="badge badge-pill badge-success"><i class="fa fa-check-double"></i> '.$status.'</span>';
      break;
    case 'proses':
      $sts  = '<span class="badge badge-pill badge-primary"><i class="fa fa-spinner"></i> '.$status.'</span>';
      break; 
    case 'ya':
      $sts  = '<span class="badge badge-pill badge-success"><i class="fa fa-check-double"></i> '.$status.'</span>';
      break; 
    case 'tidak':
      $sts  = '<span class="badge badge-pill badge-danger"><i class="fa fa-ban"></i> '.$status.'</span>';
      break;                 
    default:
      $sts  = '<span class="badge badge-pill badge-info"><i class="fa fa-exclamation"></i> '.$status.'</span>';
  }

    return $sts;
	}
}


if ( ! function_exists('convert_hari'))
{
  function convert_hari($hari='') {
    switch ($hari) {
    case 7:
      $sts  = 'Minggu';
      break;
    case 1:
      $sts  = 'Senin';
      break;
    case 2:
      $sts  = 'Selasa';
      break;      
    case 3:
      $sts  = 'Rabu';
      break;      
    case 4:
      $sts  = 'Kamis';
      break;      
    case 5:
      $sts  = 'Jumat';
      break;      
    case 6:
      $sts  = 'Sabtu';
      break;      
    default:
      $sts  = '---';
  }

    return $sts;
	}
}


if ( ! function_exists('h_jarak_tanggal'))
{
  function h_jarak_tanggal($tgl) {
    $end    = strtotime($tgl);
    $now    = time();                              
    $datediff = $end - $now;
    
    return ceil(abs($datediff / 86400));
  }
}

if(!function_exists('zip'))
{
    function zip($param,$namafile)
    {
        // Path folder
        $rootPath = DOKUMEN_FISIK_PATH.$param;
        $urlpath  = DOKUMEN_PATH.$param."$namafile";
        // echo $rootPath;exit();
        
        // locasi + nama zip
        $zipcreated = $rootPath."$namafile";
        if(file_exists($zipcreated)){
          unlink($zipcreated);
        }

        // inisialisasi zip library (object)
        $zip = new ZipArchive();
        
        $zip->open($zipcreated, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        
        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $name => $file)
        {
            //skip direktori
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                // $relativePath = substr($filePath, strlen($rootPath) + 1);                
                $relativePath = basename($filePath); 
               
                // compress ke zip
                if($relativePath != 'index.php'){
                  $zip->addFile($filePath, $relativePath);
                }
               
            }
        }
        
        // Zip archive will be created only after closing object
        $zip->close();   
        // echo $urlpath;     
        
        $yourfile = $zipcreated;
        $lokasi   = $param.$namafile;
        if (file_exists($yourfile)) {                                
          return ["urlpath"=>$urlpath,"fullpath"=>$zipcreated,"lokasi"=>$lokasi];
        }else{
          return false;
        }

    }
}



  #------------------
  function image_auth( $file_location ) {
    $request = \Config\Services::request();
    // $agen   = \Config\Services::request()->getUserAgent()->getReferrer();
    try {
      // $CI 		= get_instance();
      $api_key    = base_url( $file_location );
      $api_secret = '1DD914B5B4CFC0D5M61189N69FF209D70BF17D0E24';
      $jwt 		= $request->getGet( 'key' );
      
      

      if ( empty( $jwt ) ) {
        throw new Exception( 'Unauthorized' );
      }

      // $http_refferer = $request->getUserAgent()->getReferrer() ;//$_SERVER['HTTP_REFERER'];
      // var_dump($http_refferer);exit();

      $decoded    = JWT::decode($jwt, new Key($api_secret, 'HS256'));
      // var_dump('aaaaa');exit();

      // if ( $decoded->iss !== $api_key || date('Y-m-d H:i') !== $decoded->iat || ! in_array( $http_refferer, allowed_refferer() )) {
      if ( date('Y-m-d H:i') !== $decoded->iat  ) {
        throw new Exception( 'Signature verification failed' );
      }

      return true;

    } catch ( Exception $error) {
      echo $error->getMessage();
      exit();
    }

  }

  
  if (!function_exists('nomor')) {
      function nomor($currentPage, $perPage)
      {
          if (is_null($currentPage)) {
              $nomor = 1;
          } else {
              $nomor = 1 + ($perPage * ($currentPage - 1));
          }
          return $nomor;
      }
  }

  #-----variable JS dan CSS
  if(!function_exists('load_style')) {

    function load_style()
    {
      $css  =   [  
        "/public/lte/plugins/fontawesome-free/css/all.min.css",
        "/public/lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css",
        "/public/lte/plugins/select2/css/select2.min.css",
        "/public/lte/plugins/toastr/toastr.min.css",
        "/public/lte/plugins/sweetalert2/sweetalert2.min.css",
        "/public/lte/plugins/daterangepicker/daterangepicker.css",
        "/public/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css",
        "/public/lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css",
        "/public/lte/css/adminlte.min.css",
        "/public/lte/plugins/summernote/summernote-bs4.min.css"
      ];

      $js  =   [  
        "/public/lte/plugins/jquery/jquery.min.js",
        "/public/lte/plugins/bootstrap/js/bootstrap.bundle.min.js",
        "/public/lte/plugins/select2/js/select2.full.min.js",
        "/public/lte/plugins/toastr/toastr.min.js",
        "/public/lte/plugins/sweetalert2/sweetalert2.all.min.js",        
        "/public/lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js",
        "/public/lte/plugins/moment/moment.min.js",
        "/public/lte/plugins/inputmask/min/jquery.inputmask.bundle.min.js",
        "/public/lte/plugins/daterangepicker/daterangepicker.js",
        "/public/lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js",
        "/public/lte/js/adminlte.min.js",
        "/public/lte/plugins/summernote/summernote-bs4.min.js"      
      ];
      return (["css" => $css, "js" => $js]);
    }
  }

  #------get URL from address bar
  if(!function_exists('get_url')) {
    function get_url($grup='master')
    {
      $db   = \Config\Database::connect(); 
      $current   = current_url(); 
      $params   = $_SERVER['QUERY_STRING']; 
      $full     = $current . '?' . $params; 

      $ar_link  = explode("/",$current);
      $panjang  = count($ar_link);
      $menu     = $ar_link[$panjang-1];
        if($panjang==7) $menu     = $ar_link[$panjang-2];
      // var_dump(count($ar_link));exit();
      $data_menu     = $db->table('menu')->select("menu.*,menu.id as menu_id")->where("grup",$grup)->where("link",$menu)->get()->getRow();
      $return["current"]  = $current;
      $return["params"]   = $params;
      $return["full"]     = $full;
      $return["menu"]     = $menu;
      if(!is_null($data_menu)){
        $return["grup_menu"]  = $data_menu->grup_menu;
        $return["grup"]       = $data_menu->grup;
        $return["menu_id"]    = $data_menu->menu_id;
        $return["link"]       = $data_menu->link;
      }else{
        $return["grup_menu"]  = '';
        $return["grup"]       = '';
        $return["menu_id"]    = '';
        $return["link"]       = '';
      }
      return $return;
      // return([
      //   "current" => $current, 
      //   "params" => $params, 
      //   "full" => $full, 
      //   "menu" => $menu, 
      //   "grup_menu" => (is_null($data_menu->grup_menu)) ? '' : $data_menu->grup_menu, 
      //   "grup"=>$data_menu->grup, 
      //   "menu_id" => $data_menu->menu_id, 
      //   "link"=>$data_menu->link,         
      // ]);
    }
  }


  #------force download
  if(!function_exists('force_donlot')) {
    function force_donlot($full_path_file=null,$namafile=''){
      ob_clean();
      if(!is_null($full_path_file)){        
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="'.$namafile.'"');
        readfile($full_path_file);
        exit();
      }
    }
  }


  #------convert 5 digit
  if(!function_exists('lima_digit')) {
    function lima_digit($urut_penerbitan=0){
      $lima_digit = '0';
      if($urut_penerbitan>=1 and $urut_penerbitan<=9){ $lima_digit = "0000$urut_penerbitan";}
      if($urut_penerbitan>=10 and $urut_penerbitan<=99){ $lima_digit = "000$urut_penerbitan";}
      if($urut_penerbitan>=100 and $urut_penerbitan<=999){ $lima_digit = "00$urut_penerbitan";}
      if($urut_penerbitan>=1000 and $urut_penerbitan<=9999){ $lima_digit = "0$urut_penerbitan";}
      if($urut_penerbitan>=10000 ){ $lima_digit = "$$urut_penerbitan";}
      return $lima_digit;
    }
  }  

  #----hitung masa berlaku
  #---role : jika isinya "Seumur Hidup", maka berlakunya 5 tahun, jika isinya tanggal, maka masa berlaku disesuaikan dengan tanggal masa berlaku STR
  if(!function_exists('masa_berlaku')) {
    // $param['tanggal'] =  masa berlaku    
    function masa_berlaku($param=[]){  
      $return   = '00-00-0000';
      if( isset($param['tanggal']) && isset($param['tanggal_terbit']) ){  
        $tanggal_terbit   = $param['tanggal_terbit'];              
        $tanggal = $param['tanggal'];
        $array   = explode("-",$tanggal);
        // var_dump($array);exit();
        
        if(count($array)>1){ #====diisi tanggal          
          $return  = $param['tanggal'];
        }else{ #====seumur hidup
          $return   = date("Y-m-d", strtotime(date("Y-m-d", strtotime($tanggal_terbit)) . " + 5 year"));          
        }       
      }
      return $return; 
    }
  }

  if(!function_exists('convert_date_to_luar')) {
    function convert_date_to_luar($param=[]){ 
      $return   = '00-00-0000';
      if(isset($param['tanggal'])){                
        $tanggal = $param['tanggal'];
        $array   = explode("-",$tanggal);        
        
        if(count($array)>1){ #====diisi tanggal
          $hari   = $array[0];
          $bulan  = $array[1];
          $tahun  = $array[2];
          $return  = tanggal_huruf("$tahun-$bulan-$hari");                    
        }else{ #====seumur hidup
          $return   = $tanggal;
        }       
      }
      return $return;      
    }
  }  

  if(!function_exists('radio')) {
    function radio($param=[]){ 
      $value  = $param['value'];
      $id     = $param['id'];
      $name   = $param['name'];
      $label  = $param['label'];
      $checked  = $param['checked'];
      $required = $param['required'];
      $return = '<div class="icheck-primary d-inline">
                  <input '.$checked.' value="'.$value.'" type="radio" id="'.$id.'" '. $param['required'] .' name="'.$name.'" >
                  <label for="'.$id.'"> '.$label.' </label>
                </div>';
      return $return;
    }
  }  


  //<span class="badge badge-warning badge-pill">
  if ( ! function_exists('ya_tidak'))
  {
    function ya_tidak($role='') {
      $sts  = '---';
      switch ($role) {
        case 'ya':
          $sts  = '<span class="badge badge-pill badge-success"> <i class="fa fa-check-double"></i> '.$role.'</span>';
          break;
        case 'tidak':
          $sts  = '<span class="badge badge-pill badge-danger"> <i class="fa fa-ban"></i> '.$role.'</span>';
          break;        
      }

      return $sts;
    }
  }  

if ( ! function_exists('cek_file'))
{
  function cek_file($fullpath='') {
    cek_login();
    $file   = getcwd()."/".$fullpath;
    
    if(is_file($file)){
      return true;
    }else{
      return false;
    }
  }
}



  #------convert 5 digit
  if(!function_exists('convert_romawi')) {
    function convert_romawi($angka=0){
      $romawi = '--';
      if($angka == 1){ $romawi = "Pertama (I)"; }
      if($angka == 2){ $romawi = "Kedua (II)"; }
      if($angka == 3){ $romawi = "Ketiga (III)"; }
      return $romawi;
    }
  }


  if ( ! function_exists('lempar_salah'))
  {
    function lempar_salah($hp=0,$error=''){
      $style        = load_style();
      $el['css']  = $style['css'];
      $el['js']   = $style['js'];
      $el['judul']    = '<i class="fa fa-ban"></i> Ada kesalahan sistem';
      $el['hp']   = $hp;
      $el['error']  = $error;
      echo view('Modules\Auth\Views\v_kesalahan',$el);                 
    }
  }


  if ( ! function_exists('lempar_pertanyaan'))
  {
    function lempar_pertanyaan($kode=''){
      $style        = load_style();
      $el['css']    = $style['css'];
      $el['js']     = $style['js'];
      $el['judul']  = '<i class="fa fa-question"></i> Konfirmasi Pengunjung Pertama :'.$kode;
      echo view('Modules\Auth\Views\v_pertanyaan_mss',$el);
    }
  }  

  if ( ! function_exists('maintenance'))
  {
    function maintenance(){      
      echo view('Views\maintenance');
    }
  }  



  if ( ! function_exists('micro_seconds'))
  {
    function micro_seconds() {      
      $mt = explode(' ', microtime());
      return ((int)$mt[1]) * 1000000 + ((int)round($mt[0] * 1000000));
    }
  }

  if ( ! function_exists('make_filter'))
  {
    function make_filter($data=[]){
      // $kolom                = array('tabel.grup');
      // $kunci                = $param['search'];
      $kolom                = $data['kolom'];
      $kunci                = $data['kunci'];
      $cari                 = "";
      $array_kolom_enkrip   = $data['array_kolom_enkrip'];      
      foreach($kolom as $index=>$kol){
        if(in_array($kol,$array_kolom_enkrip)){
          $key = enkrip($kunci);
        }else{
          $key  = $kunci;
        }      
        $key  = str_replace("'","",$key);
        if($index==0){
          $cari   = " $kol LIKE '%$key%' ";
        }else{
          $cari   .= " OR $kol LIKE '%$key%' ";
        }
      }

      return $cari;
    }
  }  



  if(!function_exists('enkrip',$key)){
    function enkrip($string) {
      // $key = KEY_ENKRIP;
      $result = '';
      $test = "";
      for($i=0; $i<strlen($string); $i++) {
        $char     = substr($string, $i, 1);
        $keychar  = substr($key, ($i % strlen($key))-1, 1);
        $char     = chr(ord($char)+563-5729-ord($keychar)-56511+7809-97);
        // $test[$char]= ord($char)+ord($keychar);
        $result .=$char;
      }
      return urlencode(base64_encode($result));
    }
  }

  if(!function_exists('dekrip',$key)){
    function dekrip($string) {
      // $key    = KEY_ENKRIP;
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


  // $sts  = '<span class="badge badge-pill badge-default"> <i class="fa fa-user"></i> '.$role.'</span>';  
  if(!function_exists('badge')){
    function badge($teks='',$warna='primary',$icon='list'){
      $return  = '<span class="badge badge-pill badge-'.$warna.'"> <i class="fa fa-'.$icon.'"></i> '.$teks.'</span>'; 
      return $return;
    }
  }


if ( ! function_exists('enkripsi_ci'))
{
    function enkripsi_ci($param)
    {
        $config         = new \Config\Encryption();
        $config->key    = 'U6Z4gTdwh6zqpZZRsAeLmmjEo1ZKGviLzBtyhf1m0fSB5c7pW3uFg78m0XwuSvtI';
        $config->driver = 'OpenSSL';

        $encrypter = \Config\Services::encrypter($config);
        $enkripsi = str_replace(array('+', '/', '='), array('-', '_', '~'), base64_encode($encrypter->encrypt($param)));
        return $enkripsi;
       
    }

    
}

if ( ! function_exists('dekripsi_ci'))
{
    function dekripsi_ci($param)
    {
       
        try {
            $config         = new \Config\Encryption();
            $config->key    = 'U6Z4gTdwh6zqpZZRsAeLmmjEo1ZKGviLzBtyhf1m0fSB5c7pW3uFg78m0XwuSvtI';
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
}  