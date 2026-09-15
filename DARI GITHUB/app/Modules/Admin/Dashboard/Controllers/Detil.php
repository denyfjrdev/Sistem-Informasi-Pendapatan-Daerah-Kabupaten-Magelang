<?php
namespace App\Modules\Admin\Dashboard\Controllers;

use App\Modules\Admin\AdminBaseController;
use App\Modules\Admin\Dashboard\Models\DashboardModel;

class Detil extends AdminBaseController
{

  public function __construct(){
    $this->DashboardModel = new DashboardModel;
  }

  function ketetapan(){
    $data  = [
      "menu"        =>  "Detail",
      "fiture"      =>  "Detail Ketetapan",
      "hp"          =>  $this->hp,
      "data_user"   =>  $this->data_user,
      "datatables"  =>  true,
      "role_login"  =>  $this->data_user->role,
      "realisasi"   =>  $this->DashboardModel->get_realisasi(["tahun"=>"2026"])
    ];
    return view('App\Modules\Admin\Dashboard\Views\Detil\Ketetapan', $data);
  }

  function target(){
    $data  = [
      "menu"        =>  "Detail",
      "fiture"      =>  "Detail Target",
      "hp"          =>  $this->hp,
      "data_user"   =>  $this->data_user,
      "datatables"  =>  true,
      "role_login"  =>  $this->data_user->role,
      "realisasi"   =>  $this->DashboardModel->get_realisasi(["tahun"=>"2026"])
    ];
    return view('App\Modules\Admin\Dashboard\Views\Detil\Target', $data);
  }

  function grafik(){
    $tahun = "2026";
    $data  = [
      "menu"        =>  "Detail",
      "fiture"      =>  "Detail Grafik",
      "hp"          =>  $this->hp,
      "data_user"   =>  $this->data_user,
      "datatables"  =>  true,
      "role_login"  =>  $this->data_user->role,
      "data"        =>  ["rekap" => $this->DashboardModel->get_target(["tahun"=>$tahun])],
      "realisasi"   =>  $this->DashboardModel->get_realisasi(["tahun"=>$tahun])
    ];
    return view('App\Modules\Admin\Dashboard\Views\Detil\Grafik', $data);
  }

  function realisasi_kecamatan(){
    $data  = [
      "menu"        =>  "Detail",
      "fiture"      =>  "Realisasi Per Kecamatan",
      "hp"          =>  $this->hp,
      "data_user"   =>  $this->data_user,
      "datatables"  =>  true,
      "role_login"  =>  $this->data_user->role,
    ];
    return view('App\Modules\Admin\Dashboard\Views\Detil\Realisasi\PerKecamatan', $data);
  }

  function realisasi_desa(){
    $data  = [
      "menu"        =>  "Detail",
      "fiture"      =>  "Realisasi Per Desa",
      "hp"          =>  $this->hp,
      "data_user"   =>  $this->data_user,
      "datatables"  =>  true,
      "role_login"  =>  $this->data_user->role,
    ];
    return view('App\Modules\Admin\Dashboard\Views\Detil\Realisasi\PerDesa', $data);
  }

}