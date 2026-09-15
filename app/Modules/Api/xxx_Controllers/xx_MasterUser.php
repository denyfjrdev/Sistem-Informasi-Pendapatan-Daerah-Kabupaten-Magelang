<?php 
namespace Modules\Api\Controllers;

use Modules\Api\Controllers\ApiBaseController;

class MasterUser extends LoginBaseController
{
    
  public function __construct(){            
    
  }
  
  function index(){
    echo 'API Master User...';
  }

}