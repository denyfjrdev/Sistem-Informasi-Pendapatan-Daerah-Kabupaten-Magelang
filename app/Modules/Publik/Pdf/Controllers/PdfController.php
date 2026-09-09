<?php

namespace App\Modules\Publik\Pdf\Controllers;

use App\Modules\Publik\PublikBaseController;
use App\Modules\Admin\Pdf\Models\PdfModel;
use App\Modules\Admin\Pdf\Models\KategoriPdfModel;

class PdfController extends PublikBaseController
{
    protected $pdfModel;
    protected $uploadPath;
    protected $kategoriModel;

    public function __construct()
    {
        $this->pdfModel = new PdfModel();

        $this->kategoriModel = new KategoriPdfModel();        

        // $this->uploadPath = WRITEPATH . 'uploads/pdf/';

        // // Buat folder jika belum ada
        // if (!is_dir($this->uploadPath)) {
        //     mkdir($this->uploadPath, 0755, true);
        // }
    }

    public function index()
    {
        $keyword = trim($this->request->getGet('keyword'));
        $kategoriId = trim($this->request->getGet('kategori_id'));

        $builder = $this->pdfModel
            ->select('
                pdf_dokumen.*,
                kategori_pdf.nama_kategori
            ')
            ->join(
                'kategori_pdf',
                'kategori_pdf.id = pdf_dokumen.kategori_id',
                'left'
            );

        // Pencarian berdasarkan deskripsi
        if ($keyword !== '') {
            $builder->like(
                'pdf_dokumen.deskripsi',
                $keyword
            );
        }

        // Filter kategori
        if ($kategoriId !== '') {
            $builder->where(
                'pdf_dokumen.kategori_id',
                $kategoriId
            );
        }

        $data = [
          'hp'      =>  $this->hp,
          'menu'    => 'Publik',
          'fiture'  =>  'Pencarian',
          'title' => 'Pencarian Dokumen',

          'keyword' => $keyword,

          'kategori_id' => $kategoriId,

          'dokumen' => $builder
              ->orderBy('pdf_dokumen.id', 'DESC')
              ->paginate(9),

          'pager' => $this->pdfModel->pager,

          'kategori_list' => $this->kategoriModel
              ->orderBy('nama_kategori', 'ASC')
              ->findAll(),
        ];

        // return view('pdf/public_index', $data);
        return view('App\Modules\Publik\Pdf\Views\index',$data);
    }

}