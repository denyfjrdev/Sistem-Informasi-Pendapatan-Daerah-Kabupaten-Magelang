<?php

namespace App\Modules\Admin\Ketetapan\Models;

use CodeIgniter\Model;

class JenisPajakModel extends Model
{
    protected $table            = 'jenis_pajak';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'nama_pajak',
        'create_at',
        'update_at',
        'jenis',
        'kode',
    ];
}