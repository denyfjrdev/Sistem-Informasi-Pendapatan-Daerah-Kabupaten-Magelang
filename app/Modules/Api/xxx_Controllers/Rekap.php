<?php 
// header('Content-Type: application/json');

namespace Modules\Api\Controllers;

use Modules\Api\Controllers\ApiBaseController;
use Modules\Api\Models\DataModel;

class Rekap extends ApiBaseController
{
    
  public function __construct(){         
    // $this->response->setHeader('Content-Type', 'application/json');
    // date_default_timezone_set('Asia/Jakarta');
    // header("Content-type:application/json");    
    // $this->DataModel     = new DataModel; 
  }
  
  function index(){
    echo 'API...Rekap';
  }

  function api_rekap_dashboard(){
    $status           = 1;    
    $pesan            = '---';
    $data_hasil       = ["kategori"=>[],"device"=>[],"belum_di_proses"=>[]];

    #== kategori
    $kategori   =   $this->db->table("transaksi t")
      ->select("t.kategori,count(*)  as total")    
      ->where("status_proses != 'draft'")
      ->where("t.status_hapus='tidak'")
      ->groupBy("t.kategori")
      ->get()->getResultArray();

    #== device / pintu masuk
    $device   =   $this->db->table("transaksi t")
      ->select("t.media,count(*)  as total")    
      ->where("status_proses != 'draft'")
      ->where("t.status_hapus='tidak'")
      ->groupBy("t.media")
      ->get()->getResultArray();   
      
    #=== belum diproses
    $belum_di_proses   =   $this->db->table("transaksi t")->select("t.status_proses,count(t.id) as total");    
    if(isset($this->param->opd_id)){
      $opd_id   = $this->param->opd_id;
      $belum_di_proses  = $belum_di_proses
        // ->join("transaksi_log log "," log.transaksi_id=t.id")
        // ->where("log.id=(select max(id) from transaksi_log where kategori_log='internal' AND transaksi_id=t.id AND opd_id=$opd_id)")
        ->join("transaksi_log log "," log.transaksi_id=t.id and log.id=(select max(id) from transaksi_log where kategori_log='internal' and transaksi_id=t.id and log.opd_id=$opd_id)")
        ->where("t.status_proses in('proses')");  
    }else{
      $belum_di_proses  = $belum_di_proses->where("t.status_proses in('verifikasi','tanggapan','tanggapan_verifikasi','proses')");          
    }
           
    $belum_di_proses  = $belum_di_proses->where("t.status_hapus='tidak'");
    $belum_di_proses  = $belum_di_proses->groupBy("t.status_proses")->get()->getResultArray();        
    
    $data_hasil["kategori"]   = $kategori;
    $data_hasil["device"]     = $device;
    $data_hasil["belum_di_proses"]     = $belum_di_proses;    

    $respon           = ["status"=>$status,"pesan"=>$pesan,"data"=>$data_hasil];
    echo json_encode($respon);     
  }

  function rekap_bulan_obyek(){
    $status       = 0;    
    $pesan        = 'Default rekap bulan';
    $data_hasil   = [];

    $tahun    = $this->param->tahun;
    $bulan    = $this->param->bulan;
    $data     = $this->db->table("nota")
      ->select("nota.obyek_id,obyek.nama_obyek,sum(total) as total")      
      ->join("ref_obyek obyek "," obyek.id=nota.obyek_id")
      ->where("nota.status_lunas='ya'")
      ->where("nota.tahun",$tahun)
      ->where("month(tanggal_lunas)",$bulan)
      ->groupBy("nota.obyek_id,obyek.nama_obyek")
      ->get()->getResultArray();
    if(count($data)>0){
      $data_hasil = $data;
      $status     = 1;
      $pesan      = "Success";
    }
    $respon           = ["status"=>$status,"pesan"=>$pesan,"data"=>$data_hasil];
    echo json_encode($respon); 
  }


  function rekap_bulan_kategori(){
    $status       = 0;    
    $pesan        = 'Default rekap bulan';
    $data_hasil   = [];

    $tahun    = $this->param->tahun;
    $bulan    = $this->param->bulan;
    $data     = $this->db->table("nota")
      ->select("kat.id,kat.nama_kategori,sum(total) as total")    
      ->join("ref_obyek obyek "," obyek.id=nota.obyek_id")
      ->join("ref_kategori kat "," kat.id=obyek.ref_kategori_id")
      ->where("nota.status_lunas='ya'")
      ->where("nota.tahun",$tahun)
      ->where("month(tanggal_lunas)",$bulan)
      ->groupBy("kat.id")
      ->get()->getResultArray();
    if(count($data)>0){
      $data_hasil = $data;
      $status     = 1;
      $pesan      = "Success";
    }
    $respon           = ["status"=>$status,"pesan"=>$pesan,"data"=>$data_hasil];
    echo json_encode($respon); 
  }  

  function rekap_bulan_tarif(){
    $pesan        = 'Default rekap tarif';
    $data_hasil   = [];
    $bulan        = $this->param->bulan;
    $tahun        = $this->param->tahun;

    // from nota
    // $data   = $this->db->table("nota")
    //   ->select("tarif.kode,tarif.nama_tarif,sum(total) as total")
    //   ->join("ref_obyek obyek "," obyek.id=nota.obyek_id")
    //   ->join("ref_kategori kat "," kat.id=obyek.ref_kategori_id")
    //   ->join("ref_obyek_detil detil "," detil.obyek_id=obyek.id")
    //   ->join("ref_tarif tarif "," tarif.kode=detil.kode_tarif")
    //   ->where("month(nota.tanggal_lunas)",$bulan)
    //   ->where("year(nota.tanggal_lunas)",$tahun)
    //   ->groupBy("tarif.kode")
    //   ->get()->getResultArray();
    $data   = $this->db->table("tagihan")
      ->select("detil.kode_tarif,nota.status_lunas,tarif.nama_tarif,
          sum(tagihan.jumlah*tagihan.harga) as total")      
      ->join("nota "," nota.id=tagihan.nota_id")
      ->join("ref_obyek_detil detil "," detil.id=tagihan.obyek_detil_id")
      ->join("ref_tarif tarif "," tarif.kode=detil.kode_tarif")
      ->groupBy("detil.kode_tarif")
      ->get()->getResultArray();
    if(count($data)>0){
      $data_hasil = $data;
      $status     = 1;
    }

    $respon           = ["status"=>$status,"pesan"=>$pesan,"data"=>$data_hasil];
    echo json_encode($respon);     
  }


  #----rekap harian
  function rekap_harian_obyek(){
    $pesan        = 'Default rekap harian obyek';
    $data_hasil   = [];
    // $bulan        = $this->param->bulan;
    // $tahun        = $this->param->tahun;

    $tanggal1   = $this->param->tanggal1;
    $tanggal2   = $this->param->tanggal2;

    $data   = $this->db->table("nota")
      ->select("nota.tanggal_lunas,obyek.nama_obyek,tarif.nama_tarif,sum(total) as total")
      ->join("ref_obyek obyek "," obyek.id=nota.obyek_id")
      ->join("ref_kategori kat "," kat.id=obyek.ref_kategori_id")
      ->join("ref_obyek_detil detil "," detil.obyek_id=obyek.id")
      ->join("ref_tarif tarif "," tarif.kode=detil.kode_tarif");
      // ->where("month(nota.tanggal_lunas)",$bulan)
      // ->where("year(nota.tanggal_lunas)",$tahun);
    // if(isset($this->param->tanggal1)){      
      $data       = $data->where("nota.tanggal_lunas >= '$tanggal1' and nota.tanggal_lunas <= '$tanggal2'");
    // }
    if(isset($this->param->obyek_id)){
      $obyek_id   = $this->param->obyek_id;
      $data       = $data->where("nota.obyek_id",$obyek_id);
    }
    $data   = $data->groupBy("nota.tanggal_lunas,obyek.id")->get()->getResultArray();
    if(count($data)>0){
      $data_hasil = $data;
      $status     = 1;
      $pesan  .=  "Success";
    }
    

    $respon           = ["status"=>$status,"pesan"=>$pesan,"data"=>$data_hasil];
    echo json_encode($respon);     
  }

}