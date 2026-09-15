<?php 
// header('Content-Type: application/json');

namespace Modules\Api\Controllers;

use Modules\Api\Controllers\ApiBaseController;
use Modules\Api\Models\DataModel;

class Transaksi extends ApiBaseController
{
    
  public function __construct(){         
    // $this->response->setHeader('Content-Type', 'application/json');
    // date_default_timezone_set('Asia/Jakarta');
    // header("Content-type:application/json");    
    $this->DataModel     = new DataModel; 
  }
  
  function index(){
    echo 'API...Transaksi';
  }
  
  function transaksi_get_url(){
    $status   = 0; $data = [];
    $respon   = [ "status"  =>  $status, "data"    =>  $data ];

    $current    = $this->param->current; 
    $params     = $this->param->params;
    $full       = $current . '?' . $params; 
    $grup       = $this->param->grup;

    $ar_link  = explode("/",$current);
    $panjang  = count($ar_link);
    $menu     = $ar_link[$panjang-1];
      // if($panjang==7) $menu     = $ar_link[$panjang-2];  // versi online  root saja 
      if($panjang==8) $menu     = $ar_link[$panjang-2];   // versi localhost 1 root, 2 aplikasi sub /folder_root/aplikasi1, /folder_root/aplikasi2

    

    $data_menu     = $this->db->table('menu')->select("menu.*,menu.id as menu_id")->where("grup",$grup)->where("link",$menu)->get()->getRow();
    // var_dump($data_menu);exit();
    $return["current"]  = $current;
    $return["params"]   = $params;
    $return["full"]     = $full;
    $return["menu"]     = $menu;
    if(!is_null($data_menu)){
      $return["grup_menu"]  = $data_menu->grup_menu;
      $return["grup"]       = $data_menu->grup;
      $return["menu_id"]    = $data_menu->menu_id;
      $return["link"]       = $data_menu->link;
      $status = 1;
    }else{
      $return["grup_menu"]  = '';
      $return["grup"]       = '';
      $return["menu_id"]    = '';
      $return["link"]       = '';
      $status = 0;
    }      
    $respon = ["status"=>$status,"data"=>$return];
    echo json_encode($respon);
    
  }

  function transaksi_get_menu_id(){
    $status   = 0; $data = [];
    $respon   = [ "status"  =>  $status, "data"    =>  $data ];  
    $grup     = $this->param->grup;
    $link     = $this->param->link;
    // var_dump($this->param);exit();

    $cek    = $this->db->table('menu')->select("menu.*,id as menu_id")
          ->where("grup",$grup)
          ->where("link",$link)
          ->get()->getRowArray();
    if(is_null($cek)){
      $hasil    = false;
      $status   = 0;
    }else{
      $hasil    = $cek;
      $status   = 1;
    }
    $respon = ["status"=>$status,"data"=>$hasil];

    echo json_encode($respon);
  }



  function transaksi_get_role(){
    $status   = 0; $data = [];
    $respon   = [ "status"  =>  $status, "data"    =>  $data ];      
    
    $cek    = $this->db->table('role')->select("*")->get()->getResultArray();
    if(is_null($cek)){
      $hasil    = false;
      $status   = 0;
    }else{
      $hasil    = $cek;
      $status   = 1;
    }
    $respon = ["status"=>$status,"data"=>$hasil];

    echo json_encode($respon);    
  }  


  function transaksi_get_tabel(){
    $status   = 0; 
    $data     = [];    
    $pesan    = '---';
    
    $tabel  = $this->param->tabel;
    $cek    = $this->db->table("$tabel t")->select("t.*");
    if(isset($this->param->id)){
      $id   = $this->param->id;
      $cek  = $cek->where("id",$id);
      $data   = $cek->get()->getRowArray();
    }else{
      if(isset($this->param->kolom_filter)){
        $kolom_filter   = $this->param->kolom_filter;
        $value_filter   = $this->param->value_filter;
        $cek  = $cek->where("$kolom_filter",$value_filter);
      }
      $data   = $cek->get()->getResultArray();
    }
    
    if(is_null($data)){      
      $status   = 0;
      $pesan    = "Gagal get data";
    }else{      
      $status   = 1;
      $pesan    = "Berhasil get data";
    }
    $respon = ["status"=>$status,"pesan"=>$pesan,"data"=>$data];

    echo json_encode($respon);     
  }


  function transaksi_cari_opd(){
    $status   = 0; 
    $data     = [];    
    $pesan    = '---';
    
    $q      = $this->param->q;
    $kolom  = $this->param->kolom;

    $data    = $this->db->table("ref_opd opd")
      ->select("opd.*,opd.id as opd_id,IFNULL(induk.nama_opd,'---') as nama_induk,IFNULL(users.nama_user,'---') as nama_admin_opd")
      ->join("ref_opd induk","induk.id=opd.induk_id","left")
      ->join("users","users.opd_id=opd.id","left")      
      ->select("opd.*,opd.id as opd_id")
      ->where("opd.status_aktif","ya")      
      ->where("opd.$kolom like '%$q%'")
      ->groupBy("opd.id")
      ->limit(25)->get()->getResultArray();
    
    if(count($data)==0){      
      $status   = 0;
      $pesan    = "Gagal get data $q, $kolom";
    }else{      
      $status   = 1;
      $pesan    = "Berhasil get data $q, $kolom";
    }
    $respon = ["status"=>$status,"pesan"=>$pesan,"data"=>$data];

    echo json_encode($respon); 
  }


  function transaksi_get_syarat_jenis(){
    $status   = 0; 
    $data     = [];    
    $pesan    = '---';
    
    $jenis_id  = $this->param->jenis_id;
    
    $data    = $this->db->table("ref_jenis_laporan_syarat jenis")
      ->select("jenis.*,jenis.id as jenis_id")
      ->where("jenis_id",$jenis_id)
      ->get()->getResultArray();    
    // var_dump($data);exit();
    
    if(count($data)==0){      
      $status   = 0;
      $pesan    = "Data kosong....";
    }else{      
      $status   = 1;
      $pesan    = "Berhasil get data";
    }
    $respon = ["status"=>$status,"pesan"=>$pesan,"data"=>$data];

    echo json_encode($respon);    
  }

  function transaksi_get_jenis_laporan(){
    $status   = 0; $data = [];
    $respon   = [ "status"  =>  $status, "data"    =>  $data ];

    $hasil  = $this->db->table('ref_jenis_laporan jenis')
      ->select("jenis.*,jenis.id as jenis_id,concat('') as syarat");    
    $hasil  = $hasil->get()->getResultArray();
    foreach($hasil as $index=>$key){
      $syarat                   = $this->db->table("ref_jenis_laporan_syarat syarat")->where("jenis_id",$key['id'])->get()->getResultArray();
      $hasil[$index]['syarat']  = $syarat;
    }
    if(count($hasil)>0){
      $status = 1;
      $data   = $hasil;
    }

    $respon = ["status"=>$status,"data"=>$data];
    echo json_encode($respon);
  }



  function api_transaksi_get_log(){
    $status           = 0;    
    $pesan            = '---';
    $data_hasil       = [];
    $transaksi_id     = $this->param->transaksi_id;    
    

    // if($transaksi_id != ){
    $data_hasil = $this->db->table("transaksi_log log")
      ->select("log.*,log.id as log_id,concat('') as lampiran_log,
        DATE_FORMAT(log.create_at,'%d-%m-%Y') as tanggal_create_tanggal,
        DATE_FORMAT(log.create_at,'%H:%i') as tanggal_create_jam,
        users.nama_user as nama_penyimpan_data,users.role as role_penyimpan_data,
        IFNULL(opd.nama_opd,'---') as nama_opd_tl,
        IFNULL(opd_pengirim.nama_opd,'---') as nama_opd_pengirim,
        t.path_file_induk
        ")
      ->join("transaksi t "," t.id=log.transaksi_id")
      ->join("users","users.id=log.user_id")
      ->join("ref_opd opd","opd.id=log.opd_id","left")
      ->join("ref_opd opd_pengirim","opd_pengirim.id=log.opd_id_pengirim","left")
      ->where("log.transaksi_id",$transaksi_id);
      // ->where("log.opd_id <> log.opd_id_pengirim");        
    #=== filter urutan proses
    if(isset($this->param->ref_urutan_proses)){
      $data_hasil = $data_hasil->where("log.ref_urutan_proses",$this->param->ref_urutan_proses);
    }
    
    #=== filter status_proses
    #=== Array
    if(isset($this->param->status_proses)){ 
      $data_hasil = $data_hasil->whereIn("log.status_proses",$this->param->status_proses);
    }

    #=== filter role penerima
    #=== Array
    if(isset($this->param->role)){ 
      $data_hasil = $data_hasil->whereIn("log.role",$this->param->role);
    }    

    #=== filter role pengiirm
    #=== Array
    if(isset($this->param->role_pengirim)){ 
      $data_hasil = $data_hasil->whereIn("users.role",$this->param->role_pengirim);
    }  

    #=== filter kategori_log
    if(isset($this->param->kategori_log)){
      $data_hasil = $data_hasil->where("log.kategori_log",$this->param->kategori_log);
    } 
    
    #=== filter status_proses tabel transaksi
    #=== Array
    if(isset($this->param->status_proses_transaksi)){ 
      $data_hasil = $data_hasil->whereIn("t.status_proses",$this->param->status_proses_transaksi);
    }    
      

    $data_hasil   = $data_hasil->get()->getResultArray();
    

    if(count($data_hasil)==0) { 
      $data_hasil = [];
      $pesan      = "Data kosong";
    }else{
      $status     = 1;
      $pesan      = "Data ketemu";
      foreach($data_hasil as $index=>$key){
        $lampiran_log   = $this->db->table("transaksi_log_lampiran lamp")->where("log_id",$key['log_id'])->get()->getResultArray();
        $data_hasil[$index]['lampiran_log'] = $lampiran_log;
      }
    }            
    // }

    $respon   = ["status"=>$status,"pesan"=>$pesan,"data"=>$data_hasil,"param"=>[]];
    echo json_encode($respon);      
  } 
  
  
  function api_transaksi_log_terakhir(){
    $status           = 0;    
    $pesan            = '---';
    $data_hasil       = [];
    $transaksi_id     = $this->param->transaksi_id; 
    

    # draft = belum dikirim ke OPD lain atau ke admin kab
    // if(!isset($this->param->status_proses)){
    //   $status_proses  = 'proses';
    // }else{
    //   $status_proses  = $this->param->status_proses;
    // }
    $data_hasil       = $this->db->table("transaksi_log log")
        ->select("log.*,log.id as log_id,IFNULL(opd.nama_opd,'---') as nama_opd_tl")
        ->join("ref_opd opd","opd.id=log.opd_id","left");
    if(isset($this->param->kategori_log)){
      $kategori_log   = $this->param->kategori_log;
    }else{
      $kategori_log   = "eksternal";
    }

    if(isset($this->param->status_proses)){
      $status_proses    = $this->param->status_proses;
      if($status_proses == "'draft'"){
        $data_hasil = $data_hasil->where("log.id=(select max(id) from transaksi_log lg where log.transaksi_id=$transaksi_id AND lg.kategori_log = '$kategori_log'  AND opd_id = opd_id_pengirim  )");      
      }else{
        $data_hasil = $data_hasil->where("log.id=(select max(id) from transaksi_log lg where lg.transaksi_id=$transaksi_id AND lg.kategori_log = '$kategori_log' AND lg.status_proses in ($status_proses)  )");      
      }
    }else{
      $data_hasil = $data_hasil->where("log.id=(select max(id) from transaksi_log lg where lg.transaksi_id=$transaksi_id AND lg.kategori_log = '$kategori_log'  )");
    }

    // if($status_proses == 'proses'){
    //   $data_hasil = $data_hasil->where("log.id=(select max(id) from transaksi_log where log.transaksi_id=$transaksi_id AND (opd_id <> opd_id_pengirim OR opd_id is null) )");
    // }else{
    //   $data_hasil = $data_hasil->where("log.id=(select max(id) from transaksi_log where log.transaksi_id=$transaksi_id AND opd_id = opd_id_pengirim)");
    // }        
    $data_hasil = $data_hasil->get()->getRowArray();

    if(!is_null($data_hasil)){
      $status = 1;
      $pesan  = 'Berhasil load data';
    }else{
      $pesan  = "Gagal load data, $transaksi_id";
    }
    
    $respon           = ["status"=>$status,"pesan"=>$pesan,"data"=>$data_hasil];
    echo json_encode($respon);    
  }

  function api_transaksi_get_lampiran_log(){
    $status           = 0;    
    $pesan            = '---';
    $data_hasil       = [];

    $data_hasil       = $this->db->table("transaksi_log_lampiran lamp")
      ->select("lamp.*,lamp.id as lampiran_id,t.path_file_induk,IFNULL(u.nama_user,'---') as nama_user_upload,IFNULL(opd.nama_opd,'---') as nama_opd_upload")
      ->join("transaksi_log log","log.id=lamp.log_id")
      ->join("transaksi t","t.id=log.transaksi_id")      
      ->join("users u","u.id=lamp.user_id_upload","left")
      ->join("ref_opd opd","opd.id=u.opd_id","left");
    if(isset($this->param->lampiran_id)){ #===ROW
      $data_hasil = $data_hasil->where("lamp.id",$this->param->lampiran_id);
      $data_hasil = $data_hasil->get()->getRowArray(); 
      if(!is_null($data_hasil)){ $status=1; }
    }elseif(isset($this->param->transaksi_id)){
      $data_hasil = $data_hasil->where("t.id",$this->param->transaksi_id);
      if(isset($this->param->kirim_ke_pelapor)){
        $data_hasil = $data_hasil->where("lamp.kirim_ke_pelapor",$this->param->kirim_ke_pelapor);
      }
      $data_hasil = $data_hasil->get()->getResultArray();
      if(count($data_hasil)>0){ 
        $status=1; 
      }
      $pesan  = "Filter transaksi_id dan kirim_ke_pelapor";
    }else{ #= Result
      $data_hasil = $data_hasil->where("lamp.log_id",$this->param->log_id);
      $data_hasil = $data_hasil->get()->getResultArray();
      if(count($data_hasil)>0){ $status=1; }
    }

    $respon           = ["status"=>$status,"pesan"=>$pesan,"data"=>$data_hasil];
    echo json_encode($respon);     
  }

  function api_transaksi_get_user(){    
    // $this->response->setHeader('Content-Type', 'application/json');
    $status           = 0;    
    $pesan            = '---';
    $data_hasil       = [];
    
    
    $data_hasil         = $this->db->table("users");
    if(isset($this->param->token_user_filter)){
      $data_hasil = $data_hasil->where("token",$this->param->token_user_filter)->get()->getRowArray();      
      unset($data_hasil['password']);
      unset($data_hasil['token']);      
    }elseif(isset($this->param->role)){
      $data_hasil = $data_hasil->where("role",$this->param->role);
      if(isset($this->param->notif_wa)){
        $data_hasil = $data_hasil->where("notif_wa",$this->param->notif_wa);
      }      
      if(isset($this->param->opd_id)){
        $data_hasil = $data_hasil->where("opd_id",$this->param->opd_id);
      }
      if(isset($this->param->nohp)){
        $data_hasil = $data_hasil->where("nohp",$this->param->nohp);
      }      

      $data_hasil = $data_hasil->get()->getResultArray();
      foreach($data_hasil as $index=>$key){
        unset($data_hasil[$index]['password']);
        unset($data_hasil[$index]['token']);
      }
    }elseif(isset($this->param->nohp)){
      $data_hasil = $data_hasil->where("nohp",$this->param->nohp)->get()->getResultArray();
      foreach($data_hasil as $index=>$key){
        unset($data_hasil[$index]['password']);
        unset($data_hasil[$index]['token']);
      }      
    }else{    
      $data_hasil = [];      
    }     

    if(!is_null($data_hasil) && count($data_hasil)>0){
      $status   = 1;
      $pesan    = "Data ketemu";
    }else{
      $pesan    = "Data tidak ketemu";
    }


    $respon           = ["status"=>$status,"pesan"=>$pesan,"data"=>$data_hasil];
    echo json_encode($respon);     
  }

  function api_transaksi_get_transaksi(){
    $status           = 0;    
    $pesan            = '---';
    $data_hasil       = []; 
    
    $data_hasil       = $this->db->table("transaksi t")
      ->select("t.*,t.id as transaksi_id,
          jenis.kategori,jenis.nama_laporan as sub_kategori,
          DATE_ADD(t.tanggal_proses_tanggapan, INTERVAL 10 DAY) as plus_10_hari,
          datediff(CURRENT_DATE(),(DATE_ADD(t.tanggal_proses_tanggapan, INTERVAL 10 DAY))) as selisih,          
          pemohon.nohp as nohp_pemohon,pemohon.nama_user as nama_user_pemohon,
          IFNULL(inbox.attachmentUrl,'---') as attachmentUrl_formated")
      ->join("ref_jenis_laporan jenis","jenis.id=t.jenis_laporan_id")
      ->join("users pemohon","pemohon.id=t.user_id_pemohon")
      ->join("inbox","inbox.pesan_id=t.pesan_id","left");   
    if(isset($this->param->status_aktif)){
      $data_hasil = $data_hasil->where("t.status_aktif",$this->param->status_aktif);
    }
    #=== ada filter transaksi_id
    if(isset($this->param->transaksi_id)){
      $data_hasil = $data_hasil->where("t.id",$this->param->transaksi_id);
      $data_hasil = $data_hasil->get()->getRowArray();
      if(!is_null($data_hasil)){
        $status = 1;
        $pesan  = "Data ketemu";
      }
    #== hanya list yang masa tanggapan sudah habis ( > 10 hari dari tanggal dikirim oleh admin OPD ke pelapor )
    }elseif(isset($this->param->filter_tanggapan_batas_akhir)){ 
      $data_hasil = $data_hasil->where("datediff(CURRENT_DATE(),(DATE_ADD(t.tanggal_proses_tanggapan, INTERVAL 10 DAY))) >0")->limit(10)->get()->getResultArray();
      if(!is_null($data_hasil)){
        $status = 1;
        $pesan  = "Data ketemu";
      }      
    }else{
      $data_hasil = $data_hasil->limit(100)->get()->getResultArray();
      if(!is_null($data_hasil)){
        $status = 1;
        $pesan  = "Data ketemu";
      }
    }
    $respon           = ["status"=>$status,"pesan"=>$pesan,"data"=>$data_hasil];
    echo json_encode($respon);
  }

  function api_transaksi_get_opd(){
    $status   = 0; 
    $data     = [];    
    $pesan    = '---';
    
    $key      = $this->param->key;
    $kolom    = $this->param->kolom;

    $data    = $this->db->table("ref_opd opd")
      ->select("opd.*,opd.id as opd_id,IFNULL(induk.nama_opd,'---') as nama_induk,IFNULL(users.nama_user,'---') as nama_admin_opd")
      ->join("ref_opd induk","induk.id=opd.induk_id","left")
      ->join("users","users.opd_id=opd.id","left")      
      ->select("opd.*,opd.id as opd_id")
      ->where("opd.status_aktif","ya");
    if(isset($this->param->opd_id)){
      $opd_id   = $this->param->opd_id;
      $data     = $data->where("opd.id",$opd_id);
    }
    $data = $data->groupBy("opd.id")
      ->get()->getResultArray();
    
    if(count($data)==0){      
      $status   = 0;
      $pesan    = "Gagal get data $q, $kolom";
    }else{      
      $status   = 1;
      $pesan    = "Berhasil get data $q, $kolom";
    }
    $respon = ["status"=>$status,"pesan"=>$pesan,"data"=>$data];

    echo json_encode($respon); 
  }  


  function transaksi_test(){
    // $search = "yahoo";
    // $kolom   = array('tabel.nama_user','tabel.role','tabel.nohp','tabel.email');
    // $cari   = "";
    // foreach($kolom as $index=>$kol){
    //   if($kol == "tabel.email" || $kol == "tabel.nohp"){
    //     $key = enkrip($search);
    //   }else{
    //     $key  = $search;
    //   }
    //   $key  = str_replace("%","",$key);
    //   if($index==0){
    //     $cari   = " $kol like '%$key%' ";
    //   }else{
    //     $cari   .= " OR $kol like '%$key%' ";
    //   }
    // }
    
    $cari   = $this->DataModel->test();
    var_dump($cari);    
  }

  function convert_kategori_id(){
    $status   = 0; 
    $data     = '--default data--';    
    $pesan    = '---';
    $kategori_id  = $this->param->kategori_id;

    if($kategori_id == 'null'){
      $data   = 'Belum disetting DB';
    }else{  
      $filter = json_decode($kategori_id, true);
      $data_cek   = $this->db->table("ref_kategori")
        ->whereIn("id",$filter)
        ->get()->getResultArray();
      if(count($data_cek)>0){
        foreach($data_cek as $index=>$val){
          if($index==0){
            $data   = badge($val['nama_kategori'],'info','check');
          }else{
            $data   .= "|".badge($val['nama_kategori'],'info','check');
          }
        }      
        $status   = 1;
      }
    }    
    

    $respon = ["status"=>$status,"pesan"=>$pesan,"data"=>$data];
    echo json_encode($respon); 
  }

 
}


