<?php 
namespace Modules\Api\Controllers;
use Modules\Api\Controllers\ApiBaseController;


class DatabaseCronjob extends ApiBaseController
{
    
  public function __construct(){         
    
  }

  function index(){
    echo "Cronjob Index....";
  }

  function database_cron_auto_selesai(){
    $status           = 0;    
    $pesan            = '---';
    $array_udate      = [];
    $batas_tanggapan  = 10; //hari
    

    $data_hasil       = $this->db->table("transaksi t")
      ->select("t.*,t.id as transaksi_id,
          jenis.kategori,jenis.nama_laporan as sub_kategori,
          DATE_ADD(t.tanggal_proses_tanggapan, INTERVAL $batas_tanggapan DAY) as plus_10_hari,
          datediff(CURRENT_DATE(),(DATE_ADD(t.tanggal_proses_tanggapan, INTERVAL $batas_tanggapan DAY))) as selisih,          
          pemohon.nohp as nohp_pemohon,pemohon.nama_user as nama_user_pemohon")
      ->join("ref_jenis_laporan jenis","jenis.id=t.jenis_laporan_id")
      ->join("users pemohon","pemohon.id=t.user_id_pemohon")    
      ->where("t.status_aktif","ya")
      ->where("datediff(CURRENT_DATE(),(DATE_ADD(t.tanggal_proses_tanggapan, INTERVAL $batas_tanggapan DAY))) >0")
      ->limit(10)->get()->getResultArray();    

    if(count($data_hasil)>0)  {
      foreach($data_hasil as $val){
        $transaksi_id = $val['transaksi_id'];
        $field  = [
          "status_proses"     =>  "selesai",
          "urutan_proses_id"  =>  TAHAPAN_ID_SELESAI,
          "update_at"         =>  sekarang()
        ];

        $ubah             = $this->db->table("transaksi")->where("id",$transaksi_id)->update($field);
        if($this->db->affectedRows()>0){
          $status   = 1;
          // $pesan    = "Berhasil update, $transaksi_id ";

          #== Insert tabel transaksi_log
          $field_log  = [
            "transaksi_id"      =>  $transaksi_id,
            "create_at"         =>  sekarang(),
            "role"              =>  "user",
            "ref_urutan_proses" =>  TAHAPAN_ID_SELESAI,
            "status_proses"     =>  "selesai",
            "kategori_log"      =>  "eksternal"
          ];
          
          $this->db->table("transaksi_log")->insert($field_log);
          if($this->db->affectedRows()>0){
            $array_udate[]  = ["ID"=>$transaksi_id,"status"=>"berhasil"];
            // $pesan  .=  " Insert Log berhasil";
          }else{
            $array_udate[]  = ["ID"=>$transaksi_id,"status"=>"gagal"];
            // $pesan  .=  " Insert Log gagal";
          }
        }else{
          $array_udate[]  = ["ID"=>$transaksi_id,"status"=>"gagal"];
          // $pesan    = "Gagal update, $transaksi_id ";
        }

      }
    }


    $respon           = [$array_udate];
    echo json_encode($respon);    
  }

  #======================================== WA handling
  #--- load_pesan
  function load_pesan(){
    $status           = 0;
    $pesan            = '---';
    $lanjut           = true;


    $nohp = $pesan = '';
    if(isset($this->param->nohp)) {  $field["nohp"] = $this->param->nohp; $nohp = $this->param->nohp; }
    if(isset($this->param->pesan)) {  $field["pesan"] = $this->param->pesan; $pesan = $this->param->pesan;  }
    if(isset($this->param->timestamp)) {  $field["timestamp"] = $this->param->timestamp; }
    if(isset($this->param->msgType)) {  $field["msgType"] = $this->param->msgType; }
    if(isset($this->param->pesan_id)) {  $field["pesan_id"] = $this->param->pesan_id; $pesan_id = $this->param->pesan_id; }
    if(isset($this->param->serviceId)) {  $field["serviceId"] = $this->param->serviceId; }
    if(isset($this->param->url)) {  $field["url"] = $this->param->url; }
    if(isset($this->param->mimetype)) {  $field["mimetype"] = $this->param->mimetype; }
    if(isset($this->param->thumbnail)) {  $field["thumbnail"] = $this->param->thumbnail; }
    if(isset($this->param->chatType)) {  $field["chatType"] = $this->param->chatType; }
    if(isset($this->param->attachmentUrl)) {  $field["attachmentUrl"] = $this->param->attachmentUrl; }
    $field["tanggal_masuk"]   = sekarang();

    $nohp       = $this->convert_enam_dua($nohp);
    $cek_nohp   = $this->cek_nohp($nohp);
    $lanjut     = $cek_nohp['status'];
    $nama_opd   = $cek_nohp['nama_opd'];

    // $cek_format_umum  = $this->format_umum($pesan);
    // if($cek_format_umum ==  true){ #--- format umum langsung balas
    //   if($nohp != ''  && $pesan_id != ''){
    //     $pesan_wa   = $this->pesan_salah();        
    //     $this->reply($pesan_id,$nohp,$pesan_wa); 
    //   }
    //   $status = 1;
    //   $pesan  = "Berhasil balas";
    // }else{
    
    if($lanjut == true){ #--- nomor WA valid ( rolenya = user )
      $this->db->table("inbox")->insert($field);
      if($this->db->affectedRows()>0){
        $status = 1;
        $pesan  = "Berhasil insert inbox";

        #---send auto balas
        if($nohp != ''  && $pesan_id != ''){
          $pesan_wa   = "Silahkan tunggu beberapa saat...❗\n\npesan sedang dicek oleh sistem... 👨‍💻";
          $pesan_wa  .= "\n\n_Disclaimer_";
          $pesan_wa  .= "\n*Sistem ini belum release dan masih ujicoba*";
          $this->reply($pesan_id,$nohp,$pesan_wa) ; 
        }
      }else{
        $pesan  = "Gagal insert inbox";
      }
    }else{ #---tidak bisa membuat laporan, karena admin
      #---send auto balas
      if($nohp != ''  && $pesan_id != ''){
        $pesan_wa   = "Warning...! ⚠️\n\nMaaf anda tidak bisa membuat laporan...!, karena sudah menjadi admin di sistem dari OPD : *$nama_opd*....";
        $pesan_wa  .= $this->terimakasih();
        $this->reply($pesan_id,$nohp,$pesan_wa); 
      }
      $status = 1;
      $pesan  = "Berhasil balas";      
    }

    // }
    
    $respon         = ["status"=>$status,"pesan"=>$pesan];
    echo json_encode($respon);      
  }
  


  #--- proses pesan di inbox
  function proses_inbox(){
    $notif                    = [];
    $config                   = $this->db->table("config")->get()->getRow();
    $nomor_wa_admin_kabupaten = $config->nomor_wa_admin_kabupaten;

    $data_inbox   = $this->db->table("inbox")
      ->where("status_hapus","tidak")
      // ->where("attachmentUrl is null")
      ->limit(20)->get()->getResultArray();
    if(count($data_inbox)>0){
      foreach($data_inbox as $val){
        $nohp           = $val['nohp'];
        $pesan_id       = $val['pesan_id'];
        $pesan          = $val['pesan']; 
        $inbox_id       = $val["id"];
        $attachmentUrl  = $val["attachmentUrl"];
        $user_id        = 0; 

        // #--- cek user, jika tidak ada buat user baru
        $cek_user   =   $this->db->table("users")->select("users.*,users.id as user_id")->where("nohp",$nohp)->get()->getRow();
        if(is_null($cek_user)){ #--- BARU
          $field_user_baru  = [
            "nohp"      =>  $nohp,
            "nama_user" =>  $nohp." nama",
            "password"  =>  md5($nohp.date("dmY")), 
            "aktif"     =>  "ya",
            "role"      =>  "user",
            "created_at"=>  sekarang()       
          ];     
          $this->db->table("users")->insert($field_user_baru);
          if($this->db->affectedRows()>0){
            $notif[]  =  "User baru : BERHASIL";
          }
          $user_id  = $this->db->insertID();
        }else{
          $user_id  = $cek_user->user_id;
        }

        if($attachmentUrl == ''){ #--- tidak ada document di WA
          $cek_format_awal   = $this->cek_format_wa($pesan);
          if($cek_format_awal == true){   #-- format benar  
            $array_service    = explode("#",$pesan);
            $service          = strtolower($array_service[0]);
            $isi_inti         = $array_service[1];
            
            #-------------------------------------------------- format CURHAT
            if($service == "curhat" ){
              #--- jika tidak ada laporan yang masih proses, maka respon format WA
              $array_status_proses  = ["draft","verifikasi","proses","tanggapan","verifikasi_tanggapan"];
              $cek_laporan          = $this->cek_laporan($nohp,$array_status_proses);              
              $notif[]  = $nohp;          
              if( count($cek_laporan) >= BATAS_LAPORAN_PER_NOHP ){ #--- masih ada laporan yg masih dalam proses
                $notif[]  = "Laporan tidak diproses";
                #-- PROSES
                #1. send WA ke pelapor, masih ada laporan masih dalam proses
                #2. hapus WA di server gateway   
                
                #--- send WA ke  pelapor
                $pesan_wa  = "*".NAMA_APLIKASI."* (_Pesan tidak diproses_) ⚠️";
                $pesan_wa  .=  "\n\n- *Pesan anda* : ".$pesan;    
                $pesan_wa  .= "\n- *Catatan* : Laporan anda tidak diproses karena sudah melebihi batas maksimal, masih ada ".count($cek_laporan)." laporan yang masih dalam proses";                
                $pesan_wa  .=  "\n\n_Tunggu laporan di atas diproses sampai dengan selesai, baru anda bisa membuat laporan lagi..._";   
                $pesan_wa  .=  $this->terimakasih();  
                
                $this->ubah_inbox($inbox_id,"WA dihapus, karena masih ada laporan yang belum selesai");   
                $notif[]    = $this->reply($pesan_id,$nohp,$pesan_wa) ; 

              }else{ #--- belum ada laporan
                $notif[]  = "Laporan diproses";
                #-- PROSES
                #1. cek format WA
                #2. masukkan isi laporan
                #3. jiak format salah, kirim format laporan yang benar

                $cek_format   = $this->cek_format_wa($pesan);                
                $notif[]      = ["Cek format"=>$cek_format];
                if($cek_format == true){ #-- format benar              
                  $nomor_akhir    = $this->db->table("transaksi")->select("IFNULL(max(urut),0) as urut_akhir")->get()->getRow()->urut_akhir + 1;
                  $nomor_daftar   = "wa_".date("Ymd")."_".$nomor_akhir;              
                  $field_insert   = [
                    "user_id_pemohon"   =>  $user_id,
                    "create_at"         =>  sekarang(),
                    "urutan_proses_id"  =>  TAHAPAN_ID_TULIS,
                    "tanggal_daftar"    =>  date("Y-m-d"),
                    "nomor_daftar"      =>  $nomor_daftar,
                    "urut"              =>  $nomor_akhir,
                    "media"             =>  "wa",
                    "status_proses"     =>  "draft",
                    "penjelasan"        =>  $pesan,
                    "pesan_id"          =>  $pesan_id
                  ];
                  
                  $notif[]  = "Nomor daftar : ".$nomor_daftar;
                  $this->db->table("transaksi")->insert($field_insert);
                  if($this->db->affectedRows()>0){ #--- sukses input, hapus data di tabel inbox                      
                    
                    #--- send WA ke  pelapor
                    $pesan_wa  = "*".NAMA_APLIKASI."* (_Laporan Masuk_) ✅";
                    $pesan_wa  .=  "\n\n- *Pesan anda* : ".$pesan;            
                    $pesan_wa  .=  "\n- *Waktu kirim* : ".date("d-m-Y H:i:s"); 
                    $pesan_wa  .=  "\n- *Nomor register* : ".$nomor_daftar; 
                    $pesan_wa  .=  "\n- *Pesan sistem* : Terimakasih telah menghubungi kami, aduan/laporan anda akan segera kami tindaklanjuti";
                    $pesan_wa  .= "\n\nUntuk kelengkapan laporan, silahkan kirim foto-foto di bawah ini : \n(_abaikan jika sudah mengirim_)";
                    $pesan_wa  .= $this->sarat_dokumen();
                    $pesan_wa  .=  $this->terimakasih(); 
                    
                    $this->ubah_inbox($inbox_id,"Sudah dimasukkan ke tabel TRANSAKSI");   
                    $notif[]    = $this->reply($pesan_id,$nohp,$pesan_wa) ;                                                                       

                  }
                }else{ #--- format salah
                  $field_ubah   = [
                    "status_hapus"  =>  "ya",
                    "update_at"     =>  sekarang(),
                    "keterangan"    =>  "WA dihapus, karena masih ada laporan yang belum selesai"
                  ];
                  $this->db->table("inbox")->where("id",$inbox_id)->update($field_ubah);
                  $pesan_wa   = $this->format_salah($pesan);                
                  $param  = [
                    "id"      =>  $pesan_id,
                    "msgType" =>  "text",
                    "channel" =>  "whatsapp",
                    "to"      =>  $nohp,
                    "text"    =>  $pesan_wa
                  ];
                  $wa       = json_decode(wa_official_reply($param));
                  $notif[]  = ["Notif WA"=> $wa->data->status];            

                }
              }
            }
            
            // echo 'aaa';exit();
            #------------------------------------------------- format TANGGAPAN
            if($service == "tanggapan"){  

              $cek_data   = $this->cek_data($nohp,["tanggapan"]);                            
              if($cek_data == false){
                $pesan_wa = "Tidak ada laporan dari Anda dengan tahapan *TANGGAPAN*";
              }else{
                #--- tabel TRANSAKSI
                $field_transaksi  = [
                  "status_proses"     =>  "verifikasi_tanggapan",
                  "urutan_proses_id"  =>  TAHAPAN_ID_TANGGAPAN,
                  "update_at"         =>  sekarang()
                ];
                $this->db->table("transaksi")->where("id",$cek_data->transaksi_id)->update($field_transaksi);

                #-- tabel TRANSAKSI_LOG
                #--- eksternal
                $field_log  = [
                  "transaksi_id"  =>  $cek_data->transaksi_id,
                  "status_proses" =>  "verifikasi_tanggapan",
                  "keterangan"    =>  $pesan, 
                  "role"          =>  "user",
                  "tanggal_kirim" =>  sekarang(),
                  "kategori_log"  =>  "eksternal",
                  "ref_urutan_proses" =>  TAHAPAN_ID_TANGGAPAN,
                  "update_at"     =>  sekarang()
                ];
                $this->db->table("transaksi_log")->insert($field_log);

                #--- eksternal
                $field_log_internal  = [
                  "transaksi_id"  =>  $cek_data->transaksi_id,
                  "status_proses" =>  "verifikasi_tanggapan",
                  "keterangan"    =>  $pesan, 
                  "role"          =>  "admin_kab",
                  "tanggal_kirim" =>  sekarang(),
                  "kategori_log"  =>  "internal",
                  "ref_urutan_proses" =>  TAHAPAN_ID_TANGGAPAN,
                  "update_at"     =>  sekarang()
                ];
                $this->db->table("transaksi_log")->insert($field_log_internal);              

                $pesan_wa  = "*".NAMA_APLIKASI."* _Kirim tanggapan_ ✅";
                $pesan_wa .= "\nTanggapan anda sudah masuk ke sistem";
                $pesan_wa .= "\n\nNomor register : ".$cek_data->nomor_daftar;
                $pesan_wa .=  "\nTanggapan anda : ".$isi_inti;
                $pesan_wa  .=  $this->terimakasih();
              }              
                                                

              $this->ubah_inbox($inbox_id,"WA dihapus, karena format tanggapan tidak valid");                          
              $notif[]    = $this->reply($pesan_id,$nohp,$pesan_wa) ;              
              
            }

            #------------------------------------------------- format SELESAI
            if($service == "selesai"){
              $cek_data   = $this->cek_data($nohp,["tanggapan","verifikasi_tanggapan"]);
              
              if($cek_data == false){ #--tidak ada data
                $pesan_wa = "Tidak ada laporan dengan tahapan sekarang adalah *TANGGAPAN*";
              }else{ #---ada data
                #-- tabel TRANSAKSI
                $field_transaksi  = [
                  "status_proses"     =>  "selesai",
                  "urutan_proses_id"  => TAHAPAN_ID_SELESAI,
                  "update_at"         =>  sekarang()
                ];
                $this->db->table("transaksi")->where("pesan_id",$cek_data->pesan_id)->update($field_transaksi);

                #-- tabel TRANSAKSI_LOG
                #--- eksternal
                $field_log  = [
                  "transaksi_id"  =>  $cek_data->transaksi_id,
                  "status_proses" =>  "selesai",
                  "keterangan"    =>  "Selesai by format WA", 
                  "role"          =>  "user",
                  "tanggal_kirim" =>  sekarang(),
                  "kategori_log"  =>  "eksternal",
                  "ref_urutan_proses" =>  TAHAPAN_ID_SELESAI,
                  "update_at"     =>  sekarang()
                ];
                $this->db->table("transaksi_log")->insert($field_log);

                // // sleep(1);

                #--- internal
                $field_log  = [
                  "transaksi_id"  =>  $cek_data->transaksi_id,
                  "status_proses" =>  "selesai",
                  "keterangan"    =>  "Selesai by format WA", 
                  "role"          =>  "admin_kab",
                  "tanggal_kirim" =>  sekarang(),
                  "kategori_log"  =>  "internal",
                  "ref_urutan_proses" =>  TAHAPAN_ID_SELESAI,
                  "update_at"     =>  sekarang()
                ];
                $this->db->table("transaksi_log")->insert($field_log);


                $pesan_wa  = "*".NAMA_APLIKASI."* _Laporan ditutup_ ✅";
                $pesan_wa .=  "\nLaporan anda dengan *nomor register* : ".$cek_data->nomor_daftar;
                $pesan_wa .=  "\nSudah dianggap selesai dan ditutup oleh sistem";
                $pesan_wa  .=  $this->terimakasih();
              }
              $this->ubah_inbox($inbox_id,"WA dihapus, karena proses sudah selesai"); 
              $notif[]  = $this->reply($pesan_id,$nohp,$pesan_wa) ;
            }


          }else{ #-- format salah 
            $cek_format_umum  = $this->format_umum($pesan);
            if($cek_format_umum == false){
              $this->ubah_inbox($inbox_id,"WA dihapus, karena service tidak dikenali");       
              $pesan_wa = $this->format_salah($pesan,""); //$this->format_salah($pesan,"Service tidak dikenali");                   
              $notif[]  = $this->reply($pesan_id,$nohp,$pesan_wa) ;
            }else{
              $this->ubah_inbox($inbox_id,"WA dihapus, karena kedetek format umum");       
              // $pesan_wa = "Silahkan pilih salah satu format WA di bawah ini : 👇";                 
              // $pesan_wa .=  "\n\n*aduan#ketik isi aduan anda di sini*";
              // $pesan_wa .=  "\n   atau";
              // $pesan_wa .=  "\n*masukan#ketik masukan anda di sini*";
              // $pesan_wa  .=  $this->terimakasih();
              $pesan_wa     .=  $this->format_salah();
              $notif[]  = $this->reply($pesan_id,$nohp,$pesan_wa) ;            
            }

          }        
        }else{ # --- ada dokumen di WA
          $cek_format_awal   = $this->cek_format_wa($pesan);
          if($cek_format_awal == true){
            $array_status_proses  = ["draft","verifikasi","proses","tanggapan","verifikasi_tanggapan"];
            $cek_laporan          = $this->cek_laporan($nohp,$array_status_proses);   

            if( count($cek_laporan) >= BATAS_LAPORAN_PER_NOHP ){ #--- masih ada laporan dalam proses                
              $pesan_wa  = "*".NAMA_APLIKASI."* (_Pesan tidak diproses_) ⚠️";
              $pesan_wa  .=  "\n\n- *Pesan anda* : ".$pesan;    
              $pesan_wa  .= "\n- *Catatan* : Laporan anda tidak diproses karena sudah melebihi batas maksimal, masih ada ".count($cek_laporan)." laporan yang masih dalam proses";                
              $pesan_wa  .=  "\n\n_Tunggu laporan di atas diproses sampai dengan selesai, baru anda bisa membuat laporan lagi..._";   
              $pesan_wa  .=  $this->terimakasih();

              $this->ubah_inbox($inbox_id,"WA dihapus, masuk permohonan baru");
              $notif[]  = $this->reply($pesan_id,$nohp,$pesan_wa);
            }else{ #-- lapora diproses
              $nomor_akhir    = $this->db->table("transaksi")->select("IFNULL(max(urut),0) as urut_akhir")->get()->getRow()->urut_akhir + 1;
              $nomor_daftar   = "wa_".date("Ymd")."_".$nomor_akhir;              
              $field_insert   = [
                "user_id_pemohon"   =>  $user_id,
                "create_at"         =>  sekarang(),
                "urutan_proses_id"  =>  TAHAPAN_ID_TULIS,
                "tanggal_daftar"    =>  date("Y-m-d"),
                "nomor_daftar"      =>  $nomor_daftar,
                "urut"              =>  $nomor_akhir,
                "media"             =>  "wa",
                "status_proses"     =>  "draft",
                "penjelasan"        =>  $pesan,
                "pesan_id"          =>  $pesan_id
              ];
              
              $notif[]  = "Nomor daftar : ".$nomor_daftar;
              $this->db->table("transaksi")->insert($field_insert);
              if($this->db->affectedRows()>0){ #--- sukses input, hapus data di tabel inbox                      
                
                #--- send WA ke  pelapor
                $pesan_wa  = "*".NAMA_APLIKASI."* (_Laporan Masuk_) ✅";
                $pesan_wa  .=  "\n\n- *Pesan anda* : ".$pesan;            
                $pesan_wa  .=  "\n- *Waktu kirim* : ".date("d-m-Y H:i:s"); 
                $pesan_wa  .=  "\n- *Nomor register* : ".$nomor_daftar; 
                $pesan_wa  .=  "\n- *Pesan sistem* : Terimakasih telah menghubungi kami, aduan/laporan anda akan segera kami tindaklanjuti";
                $pesan_wa  .= "\n\nKirim data-data di bawah ini : \n(_abaikan jika sudah mengirim_)";
                $pesan_wa  .= $this->sarat_dokumen();
                $pesan_wa  .=  $this->terimakasih(); 
                
                $this->ubah_inbox($inbox_id,"Sudah dimasukkan ke tabel TRANSAKSI");   
                $notif[]    = $this->reply($pesan_id,$nohp,$pesan_wa) ;                                                                       

              }
            }
          }
        }

      }
    }

    

    echo json_encode(["pesan"=>$notif,"data"=>$data_inbox]);
  }

  private function cek_id_tanggapan($nohp='0',$transaksi_id_from_wa=0){
    $valid  = true;

    #-- ID bukan angka
    if(!is_numeric($transaksi_id_from_wa)){
      $valid = false;
    }
    #-- ID tidak ditemukan
    $cek_id   = $this->db->table("transaksi")->where("id",$transaksi_id_from_wa)->get()->getRow();
    if(is_null($cek_id)){
      $valid  = false;
    }

    #-- noHP dan ID transaksi tidak cocok
    $cek_id_nohp   = $this->db->table("transaksi t")
      ->join("users u","u.id=t.user_id_pemohon")
      ->where("t.id",$transaksi_id_from_wa)
      ->where("u.nohp",$nohp)
      ->get()->getRow();
    if(is_null($cek_id_nohp)){
      $valid  = false;
    }

    


    return $valid;
  }

  private function reply($pesan_id='',$nohp='',$pesan_wa=''){
    $param  = [
      "id"      =>  $pesan_id,
      "msgType" =>  "text",
      "channel" =>  "whatsapp",
      "to"      =>  $nohp,
      "text"    =>  $pesan_wa
    ];
    $wa       = json_decode(wa_official_reply($param));
    $notif    = ["Notif WA"=> $wa->data->status];  
    return $notif;   
  }

  private function cek_format_wa($pesan=''){
    $valid      = false;      

    $pesan      = strtolower($pesan);
    $service    = explode("#",$pesan);
    $format1    = ["curhat","tanggapan","selesai"];
    if(count($service)>=2){
      if(in_array($service[0],$format1)){
        $valid    = true;
      }
    }  
    if(preg_match("/selesai/i", $pesan)) {  
      $valid  = true;
    }  
   

    // if(preg_match("/aduan/i", $wa)) {  
    // }                     
    //   #--cari service_id--
    //   $cek_service_id     =   $this->db->table('service')->where('nama_service',$service)->get()->getRow();
    //   if(!is_null($cek_service_id)){
    //     $input['service_id']    =   $cek_service_id->id;
    //   }                                                                
    // }else{                                                                                      
    //   if(preg_match("/siaba/i", $service)) {                                
    //     $input['service_id']    =   get_service_id("siaba");
    //   }  
    //   if(preg_match("/bkppd/i", $service)) {                                
    //     $input['service_id']    =   get_service_id("bkppd");
    //   }                              
    return $valid;
  }
  
  private function format_umum($wa=''){
    $valid      = false; 
    if(preg_match("/aduan/i", $wa)) {  
      $valid  = true;
    }   
    if(preg_match("/masukan/i", $wa)) {  
      $valid  = true;
    }  
    if(preg_match("/lapor/i", $wa)) {  
      $valid  = true;
    }
    if(preg_match("/halo/i", $wa)) {  
      $valid  = true;
    }    
    if(preg_match("/hallo/i", $wa)) {  
      $valid  = true;
    }   
    if(preg_match("/selamat/i", $wa)) {  
      $valid  = true;
    } 
    if(preg_match("/laporan/i", $wa)) {  
      $valid  = true;
    }                

    return $valid;
  }

  private function format_salah($pesan='',$catatan=''){
    $pesan_wa  = "*".NAMA_APLIKASI."* (_Format tidak dikenali_) ⚠️";
    $pesan_wa .=  "\n\nBerikut proses pembuatan laporan melalui WA :";
    $pesan_wa .=  "\n👉 kirim pesan dengan format : *curhat#keterangan laporan anda*\n   Contoh : *curhat#laporan jalan rusak.*";        
    $pesan_wa  .= $this->sarat_dokumen();
    if($catatan != ''){
      $pesan_wa  .= "\n\n*Catatan sistem :*";
      $pesan_wa  .= "\n_*---$catatan---*_";
    }    
    
    $pesan_wa   .=  "\n\n--------------------------------\nLaporan juga bisa dikirim melalui android atau IOS, silahkan install aplikasi *Magelang Smart Service (MSS)* pada link di bawah ini :";
    $pesan_wa   .=  "\n👉 Android : https://s.id/mss_android";
    $pesan_wa   .=  "\n👉 IOS : https://s.id/mss_ios";
    $pesan_wa   .=  $this->terimakasih();
    return $pesan_wa;   
  }

  private function ubah_inbox($inbox_id=0,$keterangan=''){
    $field_ubah   = [
      "status_hapus"  =>  "ya",
      "update_at"     =>  sekarang(),
      "keterangan"    =>  $keterangan
    ];
    $this->db->table("inbox")->where("id",$inbox_id)->update($field_ubah);    
  }

  private function cek_data($nohp='',$status_proses=['proses']){
    $cek_data   = $this->db->table("transaksi t")
      ->select("t.*,t.id as transaksi_id")
      ->join("users u "," u.id=t.user_id_pemohon")
      ->where("u.nohp",$nohp)
      ->whereIn("t.status_proses",$status_proses)
      ->get()->getRow();    
    if(is_null($cek_data)){ 
      return false;
    }else{
      return $cek_data;
    }
  }

  private function sarat_dokumen(){
    $pesan_wa  = "\n👉 Kirim identintas pelapor (KTP/SIM), (*wajib*)";
    $pesan_wa  .= "\n👉 Kirim foto/dokumen pendukung laporan lainnya, (*opsional*)";
    return $pesan_wa;
  }

  private function cek_laporan($nohp='',$array_status_proses=['xx']){
    $cek_laporan  = $this->db->table("transaksi t")
      ->select("t.id,t.nomor_daftar,t.status_proses,t.penjelasan")
      ->join("users u","u.id=t.user_id_pemohon")
      ->where("u.nohp",$nohp)
      ->whereIn("t.status_proses",$array_status_proses)
      ->where("t.status_hapus","tidak")
      ->get()->getResultArray();   
    return $cek_laporan; 
  }

  private function terimakasih(){
    $terimakasih  =  "\n\n---Terimakasih---";
    $terimakasih  .=  "\n_Dikirim otomatis dari sistem ".NAMA_APLIKASI."_ ❤️"; 
    return $terimakasih;
  }

  #---- cek nomor WA, jika role = user, maka valid
  private function cek_nohp($nohp=''){
    $return     = false;
    $nama_opd   = '---';
    $cek  = $this->db->table("users u")
      ->select("u.*,IFNULL(opd.nama_opd,'---') as nama_opd")
      ->join("ref_opd opd","opd.id=u.opd_id","left")
      ->where("nohp",$nohp)->get()->getRow();
    if(is_null($cek)){
      $return  = true;
    }else{
      $nama_opd = $cek->nama_opd;
      if($cek->role == "user"){
        $return   = true;
      }else{
        $return   = false;
      }
    }

    return (["status"=>$return,"nama_opd"=>$nama_opd]);
  }

  private function convert_nol($nohp) {
    // if($nohp == '6285737147686-1613828507'){
    //   $hp   = $nohp;
    // }else{
      // kadang ada penulisan no hp 0811 239 345
      $nohp = str_replace(" ","",$nohp);
      // kadang ada penulisan no hp (0274) 778787
      $nohp = str_replace("(","",$nohp);
      // kadang ada penulisan no hp (0274) 778787
      $nohp = str_replace(")","",$nohp);
      // kadang ada penulisan no hp 0811.239.345
      $nohp = str_replace(".","",$nohp);
  
      // cek apakah no hp mengandung karakter + dan 0-9
      // if(!preg_match('/[^+0-9]/',trim($nohp))){ #===ASLI
      if(!preg_match('/[^+0-9]/',trim($nohp))){
          // cek apakah no hp karakter 1-3 adalah +62
          if(substr(trim($nohp), 0, 3)=='+62'){
              // $hp = trim($nohp);
              $hp = '62'.substr(trim($nohp), 3);
          }
          // cek apakah no hp karakter 1 adalah 0
          elseif(substr(trim($nohp), 0, 1)=='0'){
              $hp = '62'.substr(trim($nohp), 1);
          }else{
            $hp   = $nohp;
          }
      }
    // }
    return $hp;
  } 
  
  private function convert_enam_dua($nohp) {
      // kadang ada penulisan no hp 0811 239 345
      $nohp = str_replace(" ","",$nohp);
      // kadang ada penulisan no hp (0274) 778787
      $nohp = str_replace("(","",$nohp);
      // kadang ada penulisan no hp (0274) 778787
      $nohp = str_replace(")","",$nohp);
      // kadang ada penulisan no hp 0811.239.345
      $nohp = str_replace(".","",$nohp);
  
      // cek apakah no hp mengandung karakter + dan 0-9
      // if(!preg_match('/[^+0-9]/',trim($nohp))){ #===ASLI
      if(!preg_match('/[^+0-9]/',trim($nohp))){
        // cek apakah no hp karakter 1-3 adalah +62
        if(substr(trim($nohp), 0, 3)=='+62'){
            // $hp = trim($nohp);
            $hp = '0'.substr(trim($nohp), 3);
        }
        // cek apakah no hp karakter 1 adalah 0
        elseif(substr(trim($nohp), 0, 2)=='62'){
            $hp = '0'.substr(trim($nohp), 2);
        }else{
          $hp   = $nohp;
        }
      }
    
    return $hp;
  }   

}


// $pesan_wa  .=  "\n\n- *Pesan anda* : ".$pesan;            
    // $pesan_wa  .=  "\n- *Waktu* : ".date("d-m-Y H:i:s"); 
    // $pesan_wa  .=  "\n- *Pesan sistem* : Maaf.....format laporan anda tidak dikenali oleh sistem";
    // $pesan_wa .=  "\n\nSilahkan pilih salah satu format WA di bawah ini : 👇";                 
    // $pesan_wa .=  "\n\n*aduan#ketik isi aduan anda di sini*";
    // $pesan_wa .=  "\n   atau";
    // $pesan_wa .=  "\n*masukan#ketik masukan anda di sini*";    
    // $pesan_wa  .=  "\n\nFormat pesan aduan : *aduan#diisi dengan laporan anda*";
    // $pesan_wa  .=  "\nFormat pesan untuk saran : *masukan#diisi dengan saran/masukan anda*";
