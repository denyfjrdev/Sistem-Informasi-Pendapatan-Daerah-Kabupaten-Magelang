

<?php 
  use CodeIgniter\HTTP\Response;  



  // if ( ! function_exists('h_cek_wajib'))
  // {
  //   function h_cek_wajib(int $izin_id,int $dokumen_id, string $kategori)
  //   {
  //     cek_login();
  //     $db   = \Config\Database::connect(); 
  //     $cek  = $db->table('setting_perizinan_dokumen')->where("perizinan_id",$izin_id)->where("dokumen_id",$dokumen_id)->where("kategori",$kategori)->get()->getRow();
  //     if(!is_null($cek)){
  //       return $cek->wajib;
  //     }else{
  //       return 'tidak';
  //     }
  //   }
  // }

  // if ( ! function_exists('h_cek_wajib_isian'))
  // {
  //   function h_cek_wajib_isian(int $izin_id,string $column_name,string $kategori)
  //   {
  //     cek_login();
  //     $db   = \Config\Database::connect(); 
  //     $cek  = $db->table('setting_perizinan_isian')->where("perizinan_id",$izin_id)->where("column_name",$column_name)->where("kategori",$kategori)->get()->getRow();
  //     if(!is_null($cek)){
  //       return $cek->wajib;
  //     }else{
  //       return 'tidak';
  //     }
  //   }
  // }  


  // if ( ! function_exists('h_get_tabel'))
  // {
  //   function h_get_tabel($data=[])
  //   {     
  //     cek_login(); 
  //     $db    = \Config\Database::connect(); 
  //     $tabel = $db->table($data['tabel']);
  //     if(isset($data['filter'])){        
  //       $tabel->where($data['filter'],$data['isi']);
  //       $cek  = $tabel->get()->getRow();
  //     }else{
  //       $cek  = $tabel->get()->getResult();
  //     }      
  //     return $cek;
  //   }
  // }

  // if ( ! function_exists('h_keterangan_dokumen'))
  // {    
  //   function h_keterangan_dokumen($perizinan_id=null,$dokumen_id=null,$kategori=null)
  //   {      
  //     cek_login();
  //     $db   = \Config\Database::connect(); 
  //     $cek  = $db->table("setting_perizinan_dokumen rpd")->select("rpd.*")->where("perizinan_id",$perizinan_id)->where("dokumen_id",$dokumen_id)->where("kategori",$kategori)->get()->getRow();
  //     return $cek;
  //   }
  // }

  // if ( ! function_exists('h_keterangan_isian'))
  // {
  //   function h_keterangan_isian($perizinan_id=null,$column_name=null,$kategori=null)
  //   {    
  //     cek_login();  
  //     $db   = \Config\Database::connect(); 
  //     $cek  = $db->table("setting_perizinan_isian rpd")->select("rpd.*")->where("perizinan_id",$perizinan_id)->where("column_name",$column_name)->where("kategori",$kategori)->get()->getRow();
  //     return $cek;
  //   }
  // }  

  //   if ( ! function_exists('h_contoh_isian'))
  // {
  //   function h_contoh_isian($perizinan_id=null,$column_name=null,$kategori=null)
  //   {    
  //     cek_login();  
  //     $db   = \Config\Database::connect(); 
  //     $cek  = $db->table("setting_perizinan_isian rpd")->select("rpd.*")->where("perizinan_id",$perizinan_id)->where("column_name",$column_name)->where("kategori",$kategori)->get()->getRow();
  //     return $cek;
  //   }
  // } 

  
  // if ( ! function_exists('h_config'))
  // {
  //   function h_config()
  //   {      
  //     cek_login();
  //     $db   = \Config\Database::connect(); 
  //     $cek  = $db->table("config")->get()->getRow();
  //     return $cek;
  //   }
  // }



  // #----tabel config
  // if ( ! function_exists('h_get_config'))
  // {
  //   function h_get_config(){
  //     cek_login();
  //     $db     = \Config\Database::connect(); 
  //     $tabel  = $db->table("config");      
  //     $cari   = $tabel->get()->getRowArray();
  //     if(is_null($cari)){
  //       return false;
  //     }else{
  //       return $cari;
  //     }
  //   }
  // }
  

  // #----get data detail user
  // if ( ! function_exists('h_get_data_user'))
  // {
  //   function h_get_data_user(int $user_id)
  //   {
  //     if(session('user_id') == ''){
  //       return false;
  //     }else{      
  //       $db   = \Config\Database::connect(); 
  //       $cek  = $db->table('users')
  //         ->select("users.*,users.id as user_id,opd.singkatan_opd,opd.kode_opd")
  //         ->join("ref_opd opd","opd.kode_opd=users.kode_opd","left")       
  //         ->where("users.id",$user_id)->get()->getRow();        
  //       if(!is_null($cek)){          
  //         return ["data"=>$cek];
  //       }else{
  //         return false;
  //       }
  //     }
      
  //   }
  // }

  // #-----array status
  // if ( ! function_exists('h_status'))
  // {
  //   function h_status()
  //   {
  //     $data[] = ["name"=>"draft","caption"=>"Draft"];
  //     $data[] = ["name"=>"baru","caption"=>"Baru"];
  //     $data[] = ["name"=>"menunggu","caption"=>"Menunggu"];
  //     $data[] = ["name"=>"revisi","caption"=>"Revisi"];
  //     $data[] = ["name"=>"setuju","caption"=>"Setuju"];
  //     $data[] = ["name"=>"selesai","caption"=>"Selesai"];

  //     return $data;
  //   }
  // }


  // #-----get menu_id
  // #---- private
  // if ( ! function_exists('h_get_menu_id'))
  // {
  //   function h_get_menu_id($param=null)
  //   {   
  //     // if(session('user_id') == ''){
  //     //   return false;
  //     // }else{
  //     cek_login();
  //     $db    = \Config\Database::connect(); 
  //     $tabel = $db->table('menu')->select("menu.*,id as menu_id");        
  //     if(!is_null($param)){
  //       if(isset($param['grup'])){
  //         $tabel  = $tabel->where("grup",$param['grup']);
  //       }
  //       if(isset($param['link'])){
  //         $tabel  = $tabel->where("link",$param['link']);
  //       }                  
  //       $cek  = $tabel->get()->getRowArray();
  //     }else{
  //       $cek  = false;
  //     }      
  //     return $cek;        
  //     // }         
  //   }
  // }

  // #----mengecek kelengkapan profile user
  // if ( ! function_exists('h_cek_user'))
  // {
  //   function h_cek_user(int $user_id,$mss=0)
  //   {      
  //     cek_login();
  //     $profile  = true;
  //     $session = \Config\Services::session();
  //     $db   = \Config\Database::connect(); 
  //     $cek  = $db->table('users')
  //       ->select("users.*")        
  //       ->where("users.id",$user_id)->get()->getRowArray(); 
  //     $pesan  = 'Silahkan lengkap data di bawah ini : <br>';       
  //     if(!is_null($cek)){
  //       if($cek['nik']==''){ $profile  = false; $pesan .= '-<br> NIK harus diisi...'; }        
  //       if($cek['kelamin']==''){ $profile  = false; $pesan .= '<br>- Jenis kelamin harus diisi...'; }   
  //       if($cek['nama_user']=='NoName' || $cek['nama_user']==''){ $profile  = false; $pesan .= '<br>- Nama harus diisi...'; }        
  //       if($cek['kode_desa']==''){ $profile  = false; $pesan .= '<br>- Desa harus diisi...'; } 
  //       if($cek['alamat']==''){ $profile  = false; $pesan .= '<br>- Alamat harus diisi....'; } 
  //       if($cek['password'] == MD5($cek['nohp'])){ $profile  = false; $pesan .= '<br>- Password masih default, <b>UNTUK LEBIH MENINGKATKAN KEAMANAN AKUN ANDA ! </b>, silahkan ganti password...., bisa gabungan angka, huruf besar dan huruf kecil'; } 
  //     }
      
  //     if($profile == false){
  //       // var_dump($mss);exit();
  //       // $pesan  = "Silahkan lengkap data diri anda  : <b>Nama lengkap, NIK, email, alamat, jenis kelamin</b>";
  //       $session->setFlashdata('notif', ["icon"=>"exclamation","alert"=>"danger","judul"=>"Profile belum dilengkapi !","isi"=>$pesan]); 
  //       header("Location: ".base_url('pemohon/profile?mss='.$mss));exit();
  //     }
      
  //   }
  // }  

  // #----mengecek kelengkapan profile user
  // if ( ! function_exists('h_cek_user_admin'))
  // {
  //   function h_cek_user_admin(int $user_id,$mss=0)
  //   {      
  //     cek_login();
  //     $profile  = true;
  //     $session = \Config\Services::session();
  //     $db   = \Config\Database::connect(); 
  //     $cek  = $db->table('users')
  //       ->select("users.*")        
  //       ->where("users.id",$user_id)->get()->getRowArray(); 
  //     $pesan  = 'Silahkan lengkap data di bawah ini : <br>';       
  //     if(!is_null($cek)){        
  //       if($cek['password'] == MD5($cek['nohp'])){ $profile  = false; $pesan .= '<br>- Password masih default, <b>UNTUK LEBIH MENINGKATKAN KEAMANAN AKUN ANDA ! </b>, silahkan ganti password...., bisa gabungan angka, huruf besar dan huruf kecil'; } 
  //     }
      
  //     if($profile == false){        
  //       $session->setFlashdata('notif', ["icon"=>"exclamation","alert"=>"danger","judul"=>"Profile belum dilengkapi !","isi"=>$pesan]); 
  //       header("Location: ".base_url('admin/profile?mss='.$mss));exit();
  //     }
      
  //   }
  // }  

  if ( ! function_exists('h_get_opd')){
    function h_get_opd($param=null){
      $db     = \Config\Database::connect();
      $data   = $db->table("ref_opd opd")->select("opd.*,opd.id as opd_id");
      if(isset($param['opd_id'])){
        $data   = $data->where("opd.id",$param['opd_id']);
      }
      if(isset($param['status_aktif'])){
        $data   = $data->where("opd.status_aktif",$param['status_aktif']);
      }
      $data   = $data->get()->getResultArray();

      return $data;
    }
  }

  if ( ! function_exists('h_get_role')){
    function h_get_role(){
      $db     = \Config\Database::connect();
      $data   = $db->table("role")->select("*")->where("role != 'master'");      
      $data   = $data->get()->getResultArray();

      return $data;
    }
  }  


?>