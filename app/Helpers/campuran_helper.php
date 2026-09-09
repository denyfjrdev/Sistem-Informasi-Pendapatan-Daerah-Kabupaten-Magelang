<?php 
  use App\Libraries\MobileDetect;
  
  if ( ! function_exists('cek_hp'))
  {
    function cek_hp() {      
      $cek_hp     = new MobileDetect();
      $hp         = $cek_hp->isMobile();
      if($hp == '' || is_null($hp)){ $hp = 0;}
      return $hp;
    }
  }

  if ( ! function_exists('get_param_list_data')) {
    function get_param_list_data(){
      $request  = \Config\Services::request();
      $draw     = (int) $request->getVar('draw') ?? 1;
        if($draw==0){$draw=1;}

      // Order
      $order = $request->getPost('order');

      if (!empty($order)) {

          $order_column = $order[0]['column'];
          $order_dir    = $order[0]['dir'];          

      } else {

          $order_column = 0;
          $order_dir    = 'asc';          

      } 

      $column = $request->getPost('columns');
      if (!empty($column)) {
          $order_field = $column[$order_column]['data'];
      }else{
          $order_field = "id";
      }
      

      $param  = [        
        "draw"    =>  $draw,
        "length"  =>  $request->getPost('length') ?? 10,
        "start"   =>  $request->getPost('length') ?? 0,        
        "order"   => ["column" => $order_field, "dir" => $order_dir],        
        "search"  =>  $request->getPost('search')['value'] ?? "",        
      ];
      return $param;
    }
  }

  #==== tanggal angka
  if ( ! function_exists('tanggal_angka')){
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
  }

  #===== tanggal huruf 
  if ( ! function_exists('tanggal_huruf')){
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
  } 

?>