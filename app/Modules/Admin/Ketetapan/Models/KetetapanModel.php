<?php

namespace App\Modules\Admin\Ketetapan\Models;

use CodeIgniter\Model;

class KetetapanModel extends Model
{
    protected $table            = 'ketetapan';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields = [
        'bulan',
        'tahun',
        'ketetapan',
        'jenis_id',
        'create_at',
        'update_at',
    ];

    protected $useTimestamps = false;
}