<?php 
namespace Modules\Auth\Models;
use CodeIgniter\Model;

class UserloginModel extends Model
{
  public function __construct() {
    parent::__construct();
    // $this->tb_pelapor = $this->db->table('cp_pelapor pelapor'); //user pelapor
    // $this->tb_user    = $this->db->table('sys_user user'); //user admin dalem
  }

  // function cek_pelapor(string $username){
  //   $sql  = $this->tb_pelapor
  //     ->select('pelapor.*,p.id_penduduk as penduduk_id')
  //     ->join("cp_penduduk p","p.pelapor_id=pelapor.id_pelapor")      
  //     ->where("pelapor.nik",$username)
  //     ->Orwhere("pelapor.email",$username)
  //     ->get()->getRow();   
  //   return $sql;
  // }

  // function cek_user(string $username){
  //   $sql  = $this->tb_user
  //     ->select('*')   
  //     ->where("UserName",$username)
  //     ->Orwhere("UserEmail",$username)
  //     ->get()->getRow();   
  //   return $sql;
  // }

}