<?php

namespace App\Modules\Admin\Target\Models;

use CodeIgniter\Model;

class KetetapanModel extends Model
{
    protected $table            = 'ketetapan';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'bulan',
        'tahun',
        'target',
        'jenis_id',
        'create_at',
        'update_at',
    ];

    protected $useTimestamps = false;

    /**
     * Data dengan nama jenis pajak
     */
    public function getData($tahun = null)
    {
        $builder = $this->db->table('ketetapan k');

        $builder->select('
            k.id,
            k.bulan,
            k.tahun,
            k.target,
            k.jenis_id,
            k.create_at,
            k.update_at,
            jp.nama_pajak
        ');

        $builder->join(
            'jenis_pajak jp',
            'jp.id = k.jenis_id',
            'left'
        );

        if ($tahun !== null && $tahun !== '') {
            $builder->where('k.tahun', $tahun);
        }

        return $builder
            ->orderBy('k.tahun', 'DESC')
            ->orderBy('k.bulan', 'ASC')
            ->orderBy('jp.nama_pajak', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getById($id)
    {
        $builder = $this->db->table('ketetapan k');

        $builder->select('
            k.id,
            k.bulan,
            k.tahun,
            k.target,
            k.jenis_id,
            k.create_at,
            k.update_at,
            jp.nama_pajak
        ');

        $builder->join(
            'jenis_pajak jp',
            'jp.id = k.jenis_id',
            'left'
        );

        return $builder
            ->where('k.id', $id)
            ->get()
            ->getRowArray();
    }
}