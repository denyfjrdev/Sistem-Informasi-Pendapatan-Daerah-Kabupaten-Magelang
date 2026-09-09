<?php

namespace App\Modules\Master\Users\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nama_user',
        'nohp',
        'aktif',
        'created_at',
        'update_at',
        'role',
        'email',
        'email_gov',
        'uuid',
        'last_login',
        'keterangan',
    ];

    protected $useTimestamps = false;
}