<?php

namespace App\Modules\Admin\Target\Models;

use CodeIgniter\Model;

class JenisPajakModel extends Model
{
    protected $table      = 'jenis_pajak';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama_pajak',
        'create_at',
        'update_at',
        'jenis',
        'kode',
    ];
}