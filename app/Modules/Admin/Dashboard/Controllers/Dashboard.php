<?php 
namespace App\Modules\Admin\Dashboard\Controllers;

use App\Modules\Admin\AdminBaseController;
use App\Modules\Admin\Dashboard\Models\DashboardModel;

// use App\Libraries\Api;
// use App\Libraries\Pdf;
// use App\Libraries\Ftp; // FTP

class Dashboard extends AdminBaseController
{

  public function __construct(){      
    $this->DashboardModel    = new DashboardModel;
    // $this->api          = new Api();
    // $this->pdf          = new Pdf("P", "mm", "F4", true, 'UTF-8', false);  
  }

    function index(){
    // var_dump($this->data_user);exit();
    $tahun  = date("Y"); //$this->request->getPost("tahun");

    $dataYoy = $this->DashboardModel->get_realisasi_yoy(["tahun" => $tahun]);

    $data = [
      'data_user' =>  $this->data_user,
      "menu"            =>  'Monitoring Perpajakan',  // Judul kecil di samping motif header
      "fiture"          =>  "Beranda Admin",  // Judul besar di samping motif header
      "hp"              =>  $this->hp,
      "data_user"       =>  $this->data_user,
      "datatables"      =>  true,            
      "role_login"      =>  $this->data_user->role,      
      "realisasi"       =>  $this->DashboardModel->get_realisasi(["tahun"=>$tahun]),
      "config"          =>  $this->DashboardModel->get_last_update(),
      "top_kecamatan"   =>  $this->DashboardModel->get_top_kecamatan(["tahun"=>$tahun, "limit"=>5]),
      "yoyJson"         =>  json_encode($dataYoy)
    ];    
    return view('App\Modules\Admin\Dashboard\Views\index',$data);
  }

}