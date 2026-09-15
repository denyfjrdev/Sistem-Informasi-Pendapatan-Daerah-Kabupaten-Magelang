<?php

namespace App\Modules\Admin\Target\Models;

use CodeIgniter\Model;

class TargetModel extends Model
{
    protected $table            = 'target';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields = [
        'bulan',
        'tahun',
        'target',
        'jenis_id',
        'create_at',
        'update_at',
    ];

    protected $useTimestamps = false;
}