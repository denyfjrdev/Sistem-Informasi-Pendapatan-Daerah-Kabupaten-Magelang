<?php

namespace App\Modules\Admin\Pdf\Models;

use CodeIgniter\Model;

class KategoriPdfModel extends Model
{
    protected $table = 'kategori_pdf';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'nama_kategori',
        'deskripsi',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';
}