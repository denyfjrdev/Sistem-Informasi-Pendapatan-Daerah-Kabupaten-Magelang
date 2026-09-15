<?php 
namespace App\Modules\Master\Controllers;

use App\Modules\Master\Controllers\MasterBaseController;


class Dashboard extends MasterBaseController
{

  public function __construct(){      

  }

  function index(){
    $data = [
      "menu"            =>  'Dashboard',
      "fiture"          =>  "Dashboard Master",      
      "hp"              => $this->hp,
      "data_user"       => $this->data_user,
      "datatables"      => true,
    ];
    return view('App\Modules\Master\Views\v_dashboard',$data);
  } 
  
}