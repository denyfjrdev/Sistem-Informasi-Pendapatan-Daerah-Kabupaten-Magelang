<?php 
namespace App\Modules\Api\Esptpd\Controllers;

use App\Modules\Api\ApiBaseController;
// use App\Modules\Api\Esptpd\Models\TargetModel;


class Load extends ApiBaseController
{

  public function __construct(){      
    // $this->TargetModel  = new TargetModel();
    $this->db           = \Config\Database::connect();
    // $this->api          = new Api();
    // $this->pdf          = new Pdf("P", "mm", "F4", true, 'UTF-8', false);  
  }

  function index(){
    $respon = [
      'index' => "index"
    ];

    return $this->response->setJSON($respon);    
  }

  function load_esptpd(){
    $tahun  = date("Y");
    $total_loop   = 1;
    $loop         = true;
    $message[]    = "load_esptpd";
    $data         = [];
    while($loop==true and $total_loop<3){ #---maksimal loop 3 kali
      $token  = $this->db->table("config")->get()->getRow()->token_esptpd;      
      $param  = [
        "tahun"   =>  $tahun,
        "token"   =>  $token
      ];
      $load_api   = json_decode($this->api($param),true);
      // var_dump($load_api['status']);exit();

      #--jika gagal, refresh token
      if(isset($load_api['status'])){
        if($load_api['status'] == false){
          $request_token  = json_decode($this->refresh_token());          
          
          if(!isset($request_token->status)){
            DD("Error....");        
          }else{
            $token_baru   = $request_token->access_token;
          }
          $this->db->table("config")->update(["token_esptpd"=>$token_baru]);
        }else{
          $loop   = true;
        }
      }else{
        $message[]  = "$total_loop : error...";
      }

      $total_loop++;
    }
    

    if(isset($load_api['data'])){
      foreach($load_api['data'] as $val){
        //      "KODE REKENING": "41011201",
        // "JENIS PAJAK": "Pajak Air Tanah",
        $array_esptpd[1] = [          
          "target"    => $val["ANGGARAN JANUARI"],
          "realisasi" => $val["REALISASI JANUARI"]
        ];
        $array_esptpd[2] = [          
          "target"    => $val["ANGGARAN FEBRUARI"],
          "realisasi" => $val["REALISASI FEBRUARI"]
        ];
        $array_esptpd[3] = [          
          "target"    => $val["ANGGARAN MARET"],
          "realisasi" => $val["REALISASI MARET"]
        ];
        $array_esptpd[4] = [          
          "target"    => $val["ANGGARAN APRIL"],
          "realisasi" => $val["REALISASI APRIL"]
        ];
        $array_esptpd[5] = [          
          "target"    => $val["ANGGARAN MEI"],
          "realisasi" => $val["REALISASI MEI"]
        ];
        $array_esptpd[6] = [          
          "target"    => $val["ANGGARAN JUNI"],
          "realisasi" => $val["REALISASI JUNI"]
        ];
        $array_esptpd[7] = [          
          "target"    => $val["ANGGARAN JULI"],
          "realisasi" => $val["REALISASI JULI"]
        ];
        $array_esptpd[8] = [
          "target"    => $val["ANGGARAN AGUSTUS"],
          "realisasi" => $val["REALISASI AGUSTUS"]
        ];
        $array_esptpd[9] = [
          "target"    => $val["ANGGARAN SEPTEMBER"],
          "realisasi" => $val["REALISASI SEPTEMBER"]
        ];
        $array_esptpd[10] = [
          "target"    => $val["ANGGARAN OKTOBER"],
          "realisasi" => $val["REALISASI OKTOBER"]
        ];
        $array_esptpd[11] = [
          "target"    => $val["ANGGARAN NOVEMBER"],
          "realisasi" => $val["REALISASI NOVEMBER"]
        ];
        $array_esptpd[12] = [
          "target"    => $val["ANGGARAN DESEMBER"],
          "realisasi" => $val["REALISASI DESEMBER"]
        ];
                  

        $kode         = $val['KODE REKENING'];
        $nama_pajak   = $val['JENIS PAJAK'];

        $cek_tabel_jenis  = $this->db->table("jenis_pajak")->where("kode",$kode)->get()->getRow();
        if(is_null($cek_tabel_jenis)){
          $field  = [
            "kode"      =>  $kode,
            "nama_pajak"=> $nama_pajak,
            "jenis"     =>  "nonpbb"
          ];
          $this->db->table("jenis_pajak")->insert($field);
        }

        // $convert_bulan      = $this->convert_bulan($val['ANGGARAN JANUARI']);
        // $nama_bulan   = $convert_bulan['nama'];
        // var_dump($convert_bulan);exit();

        for ($i = 1; $i <= 12; $i++) {
          // var_dump($array_esptpd[$i]);exit();
          // var_dump($array_esptpd[$i]["target"]);exit();
          $bulan        = $i;
          $realisasi    = $array_esptpd[$i]["realisasi"];
          $target       = $array_esptpd[$i]["target"];
          $target_id    = $this->get_target(["jenis_id"=>$cek_tabel_jenis->id,"tahun"=>$tahun,"bulan"=>$bulan,"target"=>$target]);
          // DD($target_id);
          if($target_id != false){
            $target_id  = $target_id['id'];
            $field_realisasi  = [
              "tahun"     =>  $tahun,
              "bulan"     =>  $bulan,
              "target_id" =>  $target_id,
              "realisasi" =>  $realisasi
            ];
            $cek_data_realisasi   = $this->db->table("realisasi")
              ->where("target_id",$target_id)
              ->where("bulan",$bulan)
              ->where("tahun",$tahun)
              ->get()->getRow();
            if(is_null($cek_data_realisasi)){
              $this->db->table("realisasi")->insert($field_realisasi);
            }else{
              $this->db->table("realisasi")->where("id",$cek_data_realisasi->id)->update(["realisasi"=>$realisasi]);
            }
          }          
        }
                      
      }
    }else{
      $message[]  = "Error....elemen data tidak ada";
    }

    if(isset($load_api['data'])){
      $data   = $load_api['data'];
    }

    $respon = [
      'status'  => true,
      "message" =>  $message,
      "data"    =>  $data
    ];

    #---update last update last_update_esptpd
    $this->db->table("config")->update(["last_update_esptpd" => date("Y-m-d H:i:s")]);

    return $this->response->setJSON($respon);
  }

  // get_target(["jenis_id"=>$cek_tabel_jenis->id,"tahun"=>$tahun,"bulan"=>$bulan]);
  private function get_target($param=[]){    
    // var_dump($param);exit();
    $data   = $this->db->table("target")
      ->where("tahun",$param['tahun'])
      ->where("bulan",$param['bulan'])
      ->where("jenis_id",$param['jenis_id'])
      ->get()->getRow();
    if(is_null($data)){ 
      #---input target
      $status_anggaran  = $this->db->table("ref_anggaran")
        ->where("tahun",$param['tahun'])
        ->where("bulan",$param['bulan'])
        ->get()->getRow()->status_anggaran;
      $input  = [
        "tahun"   =>  $param['tahun'],
        "bulan"   =>  $param['bulan'],
        "target"  =>  $param['target'],
        "status_anggaran" =>  $status_anggaran,
        "jenis_id"  =>  $param['jenis_id'],
      ];     
      $this->db->table("target")->insert($input);      
      return ["id"=>$this->db->insertID()];
    }else{
      return (["id"=>$data->id]);
    }
    
  }

  private function api($param=[]){
    $tahun  = $param['tahun'];
    $token  = $param['token'];

    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => "https://esptpd.magelangkab.go.id/wspdl-kab-magelang/api/realisasi/lra_bulanan_pajak_jenis?tahun=".$tahun,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ".$token,
        "Content-Type: application/json"
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }    
  }

  private function refresh_token(){
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => "https://esptpd.magelangkab.go.id/wspdl-kab-magelang/api/auth/login",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode([
        'username' => 'api-dashboard',
        'password' => 'g,$fs6nLW5tRVUmI'
      ]),
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ",
        "Content-Type: application/json"
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }      
  }


  private function convert_bulan($nama_bulan=""){
    $kode   = 0;
    $nama   = '';
    switch ($nama_bulan) {    
        case 'ANGGARAN JANUARI':
          $kode   = 1;
          // $nama   = 'JANUARI';
          break;        
        case 'ANGGARAN FEBRUARI':
          $kode   = 2;
          // $nama   = 'FEBRUARI';
          break;
        case 'ANGGARAN MARET':
          $kode   = 3;
          // $nama   = 'MARET';
          break;
        case 'ANGGARAN APRIL':
          $kode   = 4;
          // $nama   = 'APRIL';
          break;
        case 'ANGGARAN MEI':
          $kode   = 5;
          // $nama   = 'MEI';
          break;
        case 'ANGGARAN JUNI':
          $kode   = 6;
          // $nama   = 'JUNI';
          break;
        case 'ANGGARAN JULI':
          $kode   = 7;
          // $nama   = 'JULI';
          break;
        case 'ANGGARAN AGUSTUS':
          $kode   = 8;
          // $nama   = 'AGUSTUS';
          break;
        case 'ANGGARAN SEPTEMBER':
          $kode   = 9;
          // $nama   = 'SEPTEMBER';
          break;
        case 'ANGGARAN OKTOBER':
          $kode   = 10;
          // $nama   = 'OKTOBER';
          break;
        case 'ANGGARAN NOVEMBER':
          $kode   = 11;
          // $nama   = 'NOVEMBER';
          break;
        case 'ANGGARAN DESEMBER':
          $kode   = 12;
          // $nama   = 'DESEMBER';
          break;
        default:
          $kode   = 0;
          break;
    }          
    
    return $kode;
  }

}