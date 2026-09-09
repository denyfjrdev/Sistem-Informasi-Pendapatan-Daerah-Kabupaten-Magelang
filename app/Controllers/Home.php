<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
      session()->destroy();
      exit();

      // echo "Index APP Sijakon";
      // helper('url');      
      // return view('v_home'); 

      // $menu = [];  
      // $data = [
      //   "menu"        => "Home",        
      //   "fiture"      => "Dashboard Master",
      //   "applicationName"  =>  env('applicationName'),
      //   "featureName"  =>  'index'
      // ];
      // return view('v_test',$data);         
    }

    function error(){
      return view('v_error');
    }

    // function keluar(){
    //   session()->destroy();
    //   echo 'Keluar...';
    // }
}
