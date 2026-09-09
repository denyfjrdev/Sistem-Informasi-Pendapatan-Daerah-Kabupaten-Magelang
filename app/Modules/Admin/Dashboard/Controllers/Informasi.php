<?php
namespace App\Modules\Admin\Dashboard\Controllers;

use App\Modules\Admin\AdminBaseController;

class Informasi extends AdminBaseController
{

  function index(){
    $data  = [
      "menu"        =>  "Informasi",
      "fiture"      =>  "Informasi",
      "hp"          =>  $this->hp,
      "data_user"   =>  $this->data_user,
      "datatables"  =>  false,
      "role_login"  =>  $this->data_user->role,
    ];
    return view('App\Modules\Admin\Dashboard\Views\Informasi\index', $data);
  }

}