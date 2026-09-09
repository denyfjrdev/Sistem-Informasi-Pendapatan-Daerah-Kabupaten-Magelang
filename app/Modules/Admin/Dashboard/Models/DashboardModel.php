<?php 
namespace App\Modules\Admin\Dashboard\Models;
use CodeIgniter\Model;

class DashboardModel extends Model
{
  public function __construct() {
    parent::__construct();    
  }

  function get_target($param=[]){
    $tahun = $param['tahun'];
    $data = $this->db->table("jenis_pajak")->select("*")->get()->getResultArray();
    foreach($data as $index_anggaran=>$jenis){
        $bulan = $this->db->table("ref_anggaran")
          ->select("bulan,tahun,status_anggaran")
          ->where("tahun",$tahun)        
          ->get()->getResult();
        foreach($bulan as $val_bulan){
          $target = $this->db->table("target")
            ->where("bulan",$val_bulan->bulan)
            ->where("tahun",$val_bulan->tahun)
            ->where("jenis_id",$jenis['id'])
            ->where("tahun",$tahun)        
            ->get()->getRow();
          if(!is_null($target)){
            $data[$index_anggaran][$val_bulan->bulan]  = $target->target;            
          }else{
            $data[$index_anggaran][$val_bulan->bulan]  = '0';            
          }
          $data[$index_anggaran]['jenis_id']  = $jenis['id'];
        }
    }
    return ["judul"=>$bulan,"data" => $data];
    
  }

  function get_realisasi($param=[]){
    $tahun          = $param['tahun'];
    $data_target    = $this->get_target(["tahun"=>$tahun]);    
    $data_all_realisasi   = $this->db->table("realisasi r")
      ->select("r.*,jenis.id as jenis_id,jenis.nama_pajak,anggaran.status_anggaran")
      ->join("target "," target.id=r.target_id")
      ->join("jenis_pajak jenis "," jenis.id=target.jenis_id")
      ->join("ref_anggaran anggaran "," anggaran.tahun=target.tahun and anggaran.bulan=target.bulan")
      ->where("target.tahun",$tahun)      
      ->orderBy("target.id")
      ->get()->getResultArray();

// echo "<pre>";      
// var_dump($data_target['data']);
// echo "</pre>";   

// echo "<pre>";      
// var_dump($data_all_realisasi);
// echo "</pre>"; 

// exit();

    $detil = [];
    foreach($data_target['data'] as $index=>$val_target){      
      for($i=1;$i<=12;$i++){        
        $detil[$i] = 0;
        foreach($data_all_realisasi as $data_realisasi){          
          if($data_realisasi['bulan'] == $i && $val_target['jenis_id'] == $data_realisasi['jenis_id'] ){
            $detil[$i]  += $data_realisasi['realisasi']; //$i." || ".$data_realisasi['bulan']." || ".$val_target['jenis_id']." || ".$data_realisasi['jenis_id']." || ".$data_realisasi['realisasi'];
          }
          // elseif($val_target['jenis_id'] == $data_realisasi['jenis_id']){
          //   $detil[$i]  = 0;
          // }          
        }   
        
      }
      // exit();
      $realisasi[$val_target['jenis_id']]  = [
        "jenis_id"    =>  $val_target['jenis_id'],
        "kode"        =>  $val_target['kode'],
        "nama_pajak"  =>  $val_target['nama_pajak'],
        "detil"       =>  $detil
      ];            
    }

// echo "<pre>";      
// var_dump($realisasi);exit();
// echo "</pre>";      

    return [
      "judul"   =>  $data_target['judul'],
      "target"  =>  $data_target['data'],
      "realisasi" =>  $realisasi
    ];
    
  }  

  function get_last_update(){
    $data   = $this->db->table("config")->get()->getRow();
    return $data;
  }
  

}    