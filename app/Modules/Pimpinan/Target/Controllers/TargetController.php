<?php

namespace App\Modules\Pimpinan\Target\Controllers;

use App\Modules\Pimpinan\AdminBaseController;
use App\Modules\Pimpinan\Target\Models\TargetModel;
use App\Modules\Pimpinan\Target\Models\JenisPajakModel;

// use App\Controllers\BaseController;
// use App\Models\TargetModel;
// use App\Models\JenisPajakModel;

class TargetController extends AdminBaseController
{
    protected TargetModel $targetModel;
    protected JenisPajakModel $jenisPajakModel;

    public function __construct()
    {
        $this->targetModel    = new TargetModel();
        $this->jenisPajakModel = new JenisPajakModel();
    }

    /**
     * Halaman utama
     */
    public function index()
    {
        $data = [
            'data_user'   => $this->data_user,
            'role_login'  => $this->data_user->role,
            'menu'        => 'Monitoring Perpajakan',
            'fiture'      => 'Lihat Target',
            'title'       => 'Target Pajak',
            'jenis_pajak' => $this->jenisPajakModel
                ->orderBy('nama_pajak', 'ASC')
                ->findAll(),
        ];

        // return view('target/index', $data);
        return view('App\Modules\Pimpinan\Target\Views\index',$data);
    }

    /**
     * Data target
     */
    public function data()
    {
        $tahun = $this->request->getGet('tahun');

        $builder = $this->targetModel
            ->select('
                target.*,
                jenis_pajak.nama_pajak,
                jenis_pajak.kode
            ')
            ->join(
                'jenis_pajak',
                'jenis_pajak.id = target.jenis_id',
                'left'
            );

        if (!empty($tahun)) {
            $builder->where('target.tahun', $tahun);
        }

        $data = $builder
            ->orderBy('target.tahun', 'DESC')
            ->orderBy('jenis_pajak.nama_pajak', 'ASC')
            ->orderBy('target.bulan', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'data'   => $data,
        ]);
    }

    /**
     * Ambil 12 bulan berdasarkan jenis + tahun
     */
    public function get()
    {
        $jenisId = $this->request->getGet('jenis_id');
        $tahun   = $this->request->getGet('tahun');

        if (!$jenisId || !$tahun) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Jenis pajak dan tahun wajib diisi.',
            ]);
        }

        $rows = $this->targetModel
            ->where('jenis_id', $jenisId)
            ->where('tahun', $tahun)
            ->orderBy('bulan', 'ASC')
            ->findAll();

        $result = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {

            $target = 0;

            foreach ($rows as $row) {
                if ((int) $row['bulan'] === $bulan) {
                    $target = (int) $row['target'];
                    break;
                }
            }

            $result[$bulan] = [
                'bulan'  => $bulan,
                'target' => $target,
            ];
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $result,
        ]);
    }

    /**
     * Pimpinan tidak boleh input/ubah target.
     */
    public function save()
    {
        return $this->response->setStatusCode(403)->setJSON([
            'status'  => false,
            'message' => 'Pimpinan hanya dapat melihat target, tidak dapat mengubah.',
        ]);
    }

    /**
     * Pimpinan tidak boleh hapus target.
     */
    public function delete()
    {
        return $this->response->setStatusCode(403)->setJSON([
            'status'  => false,
            'message' => 'Pimpinan hanya dapat melihat target, tidak dapat menghapus.',
        ]);
    }
}