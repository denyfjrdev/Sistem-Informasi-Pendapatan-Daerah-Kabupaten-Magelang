<?php

namespace App\Modules\Admin\Pdf\Models;

use CodeIgniter\Model;

class PdfModel extends Model
{
    protected $table = 'pdf_dokumen';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'kategori_id',
        'deskripsi',
        'nama_file',
        'nama_file_asli',
        'ukuran_file',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';
}