<?php

namespace App\Modules\Admin\Target\Controllers;

use App\Modules\Admin\AdminBaseController;
use App\Modules\Admin\Target\Models\TargetModel;
use App\Modules\Admin\Target\Models\JenisPajakModel;

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
          'menu'  =>  'Pengaturan',
          'fiture'  =>  'Target',
            'title'       => 'Target Pajak',
            'jenis_pajak' => $this->jenisPajakModel
                ->orderBy('nama_pajak', 'ASC')
                ->findAll(),
        ];

        // return view('target/index', $data);
        return view('App\Modules\Admin\Target\Views\index',$data);
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
     * Simpan 12 bulan sekaligus
     */
    public function save()
    {
        $jenisId = $this->request->getPost('jenis_id');
        $tahun   = $this->request->getPost('tahun');
        $targets = $this->request->getPost('target');

        if (empty($jenisId)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Jenis pajak wajib dipilih.',
            ]);
        }

        if (empty($tahun)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Tahun wajib diisi.',
            ]);
        }

        if (!is_array($targets)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data target tidak valid.',
            ]);
        }

        $jenis = $this->jenisPajakModel->find($jenisId);

        if (!$jenis) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Jenis pajak tidak ditemukan.',
            ]);
        }

        $db = db_connect();

        try {

            $db->transStart();

            for ($bulan = 1; $bulan <= 12; $bulan++) {

                $nilai = $targets[$bulan] ?? 0;

                // Hilangkan format rupiah
                $nilai = str_replace('.', '', $nilai);
                $nilai = str_replace(',', '', $nilai);

                $nilai = (int) $nilai;

                $existing = $this->targetModel
                    ->where('jenis_id', $jenisId)
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->first();

                if ($existing) {

                    $this->targetModel->update(
                        $existing['id'],
                        [
                            'target'    => $nilai,
                            'update_at' => date('Y-m-d H:i:s'),
                        ]
                    );

                } else {

                    $this->targetModel->insert([
                        'bulan'     => $bulan,
                        'tahun'     => $tahun,
                        'target'    => $nilai,
                        'jenis_id'  => $jenisId,
                        'create_at' => date('Y-m-d H:i:s'),
                        'update_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan data.');
            }

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Target pajak berhasil disimpan.',
            ]);

        } catch (\Throwable $e) {

            if ($db->transStatus() !== false) {
                $db->transRollback();
            }

            log_message('error', $e->getMessage());

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal menyimpan target pajak.',
                'error'   => ENVIRONMENT === 'development'
                    ? $e->getMessage()
                    : null,
            ]);
        }
    }

    /**
     * Hapus seluruh target jenis + tahun
     */
    public function delete()
    {
        $jenisId = $this->request->getPost('jenis_id');
        $tahun   = $this->request->getPost('tahun');

        if (!$jenisId || !$tahun) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Parameter tidak lengkap.',
            ]);
        }

        $this->targetModel
            ->where('jenis_id', $jenisId)
            ->where('tahun', $tahun)
            ->delete();

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Target berhasil dihapus.',
        ]);
    }
}