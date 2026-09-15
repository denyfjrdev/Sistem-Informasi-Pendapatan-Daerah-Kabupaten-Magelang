<?php

namespace Modules\Sample\Controllers;

use CodeIgniter\HTTP\Response;
use Illuminate\Database\Capsule\Manager as DB;
use Modules\Sample\Controllers\BaseController as Controller;

class SampleController extends Controller
{
    protected string $feature = 'Sample Feature';

    public function index()
    {
        $data = [
            // 'user' => $this->auth->user()
          "menu"        => "Pemohon",
          "fiture"      =>  "Dashboard", 
          "data_user"       => $this->data_user,
        ];

        // return $this->render('index', $data);
        return view('App\Modules\Sample\Views\index',$data);
    }

}
