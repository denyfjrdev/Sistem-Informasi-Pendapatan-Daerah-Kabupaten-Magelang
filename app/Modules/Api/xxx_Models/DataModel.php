<?php 
namespace Modules\Api\Models;

use CodeIgniter\Model;

class DataModel extends Model
{
  // protected $table      = 'sys_user u';
  
  public function __construct() {
    parent::__construct();        
  }

  function get_transaksi($param=[]){
    $data  = $this->db->table("transaksi t")
      ->select("t.*,pos.nama_pos,pos.kode as kode_pos,kom.nama_komoditas,rt.nama_truk,u.nama_user")      
      ->join("users u","u.id=t.user_id")
      ->join("truk_komoditas truk","truk.id=t.tarif_id")
      ->join("ref_komoditas kom","kom.id=truk.komoditas_id")
      ->join("ref_truk rt","rt.id=truk.truk_id")
      ->join("ref_pos pos","pos.id=t.pos_id");
    if(isset($param['tanggal'])){
      $data   = $data->where("t.tanggal",$param['tanggal']);
    }
    if(isset($param['pos_id'])){
      $data   = $data->where("t.pos_id",$param['pos_id']);
    }
    if(isset($param['key'])){
      $data   = $data->where("u.token",$param['key']);
    }                  
    if(isset($param['limit']) && $param['limit']>0){
      $data   = $data->limit($param['limit']);
    }
    if(isset($param['transaksi_id'])){
      $data   = $data->where("t.id",$param['transaksi_id']);
    }    
    $data   = $data->orderBy("id","DESC")->get();

    return $data;
  }

  function get_total_transaksi($param=[]){
    $data  = $this->db->table("transaksi t")
      ->select("sum(t.nominal) as total_nominal")      
      ->join("users u","u.id=t.user_id")
      ->join("truk_komoditas truk","truk.id=t.tarif_id")
      ->join("ref_komoditas kom","kom.id=truk.komoditas_id")
      ->join("ref_truk rt","rt.id=truk.truk_id")
      ->join("ref_pos pos","pos.id=t.pos_id");
    if(isset($param['tanggal'])){
      $data   = $data->where("t.tanggal",$param['tanggal']);
    }
    if(isset($param['pos_id'])){
      $data   = $data->where("t.pos_id",$param['pos_id']);
    }
    if(isset($param['key'])){
      $data   = $data->where("u.token",$param['key']);
    }    
    $data   = $data->get(); 
    
    return $data;
  }

  #====make nomor urut
  function buat_nomor($param=null){
    if(isset($param['pos_id'])){
      $pos_id       = $param['pos_id'];
      $last_urut    = $this->db->table("transaksi t")
          ->select("IFNULL(max(t.urut),0) as urut")
          ->where("t.pos_id",$pos_id)->get()->getRowArray()['urut'] + 1;
      if($last_urut >=1 and $last_urut <=9){ #1 digit
        $urut_jadi  = "00000$last_urut";
      }elseif($last_urut >=10 and $last_urut <= 99){ #--2 digit
        $urut_jadi  = "0000$last_urut";
      }elseif($last_urut >=100 and $last_urut <= 999){ #--3 digit
        $urut_jadi  = "000$last_urut";
      }elseif($last_urut >=100 and $last_urut <= 999){ #--4 digit
        $urut_jadi  = "00$last_urut";
      }elseif($last_urut >=100 and $last_urut <= 999){ #--5 digit
        $urut_jadi  = "0$last_urut";
      }else{
        $urut_jadi  = "$last_urut";
      }
                      
      $kode   = $pos_id."_".$urut_jadi;
      return (["urut"=>$last_urut,"kode"=>$kode]);
    }
  }

  function test(){
    $kolom   = array('tabel.nama_user','tabel.role','tabel.nohp','tabel.email');
    $kunci    = "085737147686";
    $cari   = "";
    $array_kolom_enkrip   = ["tabel.email","tabel.nohp"];
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