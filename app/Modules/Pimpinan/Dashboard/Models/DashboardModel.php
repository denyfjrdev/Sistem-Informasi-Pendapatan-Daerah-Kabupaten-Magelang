<?php 
namespace App\Modules\Pimpinan\Dashboard\Models;
use CodeIgniter\Model;

class DashboardModel extends Model
{
  public function __construct() {
    parent::__construct();    
  }

  function get_target($param=[]){
    $tahun = $param['tahun'];
    $data = $this->db->table("jenis_pajak")->select("*")
      ->groupStart()
        ->where('kode !=', 'esptpd')
        ->orWhere('kode', null)
      ->groupEnd()
      ->get()->getResultArray();
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
    function get_realisasi_yoy($param = [])
  {
      $tahunIni    = $param['tahun'];       // misal 2026
      $tahunLalu   = $tahunIni - 1;         // 2025

      $hasil = [];

      foreach ([$tahunLalu, $tahunIni] as $tahun) {

          for ($bulan = 1; $bulan <= 12; $bulan++) {

              $target = $this->db->table("target t")
                  ->selectSum("t.target")
                  ->join("jenis_pajak jp", "jp.id = t.jenis_id")
                  ->groupStart()
                      ->where("jp.kode !=", "esptpd")
                      ->orWhere("jp.kode", null)
                  ->groupEnd()
                  ->where("t.bulan", $bulan)
                  ->where("t.tahun", $tahun)
                  ->get()->getRow();

              $realisasi = $this->db->table("realisasi r")
                  ->select("SUM(r.realisasi) as realisasi")
                  ->join("target t", "t.id = r.target_id")
                  ->join("jenis_pajak jp", "jp.id = t.jenis_id")
                  ->groupStart()
                      ->where("jp.kode !=", "esptpd")
                      ->orWhere("jp.kode", null)
                  ->groupEnd()
                  ->where("r.bulan", $bulan)
                  ->where("r.tahun", $tahun)
                  ->get()->getRow();

              $totalTarget    = (float) ($target->target ?? 0);
              $totalRealisasi = (float) ($realisasi->realisasi ?? 0);

              $persen = $totalTarget > 0
                  ? ($totalRealisasi / $totalTarget) * 100
                  : 0;

              $hasil[$bulan][$tahun] = [
                  "target"     => $totalTarget,
                  "realisasi"  => $totalRealisasi,
                  "persen"     => round($persen, 1),
              ];
          }
      }

      return $hasil;
      // struktur: $hasil[1]['2025']['persen'], $hasil[1]['2026']['persen'], dst
  }

  function get_top_kecamatan($param = [])
  {
      $tahun = $param['tahun'] ?? date('Y');
      $limit = (int) ($param['limit'] ?? 5);

      return $this->db->table('realisasi r')
          ->select('k.kode_kecamatan, k.nama_kecamatan, SUM(r.realisasi) AS total_realisasi')
          ->join('target t', 't.id = r.target_id')
          ->join('jenis_pajak jp', 'jp.id = t.jenis_id')
          ->join('desa d', 'd.kode_desa = r.kode_desa')
          ->join('kecamatan k', 'k.kode_kecamatan = d.kode_kecamatan')
          ->where('r.tahun', $tahun)
          ->where('r.kode_desa IS NOT NULL', null, false)
          ->where('r.kode_desa !=', '')
          ->groupStart()
              ->whereIn('jp.jenis', ['pbb', 'kendaraan'])
              ->orWhere('jp.kode', 'esptpd')
          ->groupEnd()
          ->groupBy('k.kode_kecamatan, k.nama_kecamatan')
          ->orderBy('total_realisasi', 'DESC')
          ->limit($limit)
          ->get()
          ->getResultArray();
  }
}    