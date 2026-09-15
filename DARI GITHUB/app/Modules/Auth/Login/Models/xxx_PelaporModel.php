<?php namespace Modules\Auth\Models;

use CodeIgniter\Model;


class PelaporModel extends Model
{
 
    protected $table      = 'cp_pelapor';
    protected $primaryKey = 'id_pelapor';
 
    protected $returnType     = 'object';
    // protected $useSoftDeletes = true;
 
    protected $allowedFields = ['desa_id', 'nama', 'kk', 'nik', 'id_negara', 'nmr_dokumen','email','password','salt','token', 'telepon', 'gambar', 'status_telepon','aktif', 'waktu'];
 
    protected $useTimestamps = true;
    protected $createdField  = 'tgl_post';
    protected $updatedField  = 'tgl_update';
    
    
    public function __construct() {
        parent::__construct();
		
        $this->tabel = $this->db->table($this->table);
    }


    public function cek_pelapor($nik)
    {
        $query = $this->tabel->select('*, count(cp_pelapor.id_pelapor) as count')
                            ->where('nik', $nik)
                            ->get()
                            ->getRow();
        return $query;
    }


    public function cek_nik($nik)
    {
        $query = $this->tabel->select('cp_pelapor.*, count(cp_pelapor.id_pelapor) as count')
                            ->select('cp_penduduk.nama')
                            ->join('cp_penduduk', 'cp_penduduk.nik = cp_pelapor.nik')
                            ->where('cp_pelapor.nik', $nik)
                            ->get()
                            ->getRow();
        return $query;
    }

    
    public function cek_nik_daftar($nik)
    {
        $query = $this->tabel->select('nik')
                            ->where('nik', $nik)
                            ->get()
                            ->getRow();
        return $query;
    }


    public function simpan($data)
    {
        $this->tabel->insert($data);
    }
}