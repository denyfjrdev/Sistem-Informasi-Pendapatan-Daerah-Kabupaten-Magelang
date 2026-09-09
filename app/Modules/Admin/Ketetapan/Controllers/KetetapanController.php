<?php

namespace App\Modules\Admin\Ketetapan\Controllers;

use App\Modules\Admin\AdminBaseController;
use App\Modules\Admin\Ketetapan\Models\JenisPajakModel;
use App\Modules\Admin\Ketetapan\Models\KetetapanModel;

class KetetapanController extends AdminBaseController
{
    protected KetetapanModel $ketetapanModel;
    protected JenisPajakModel $jenisPajakModel;

    public function __construct()
    {
        $this->ketetapanModel = new KetetapanModel();
        $this->jenisPajakModel = new JenisPajakModel();
    }


    /**
     * Halaman utama
     */
    public function index()
    {
      
        $data = [
          'menu'  =>  'Pengaturan',
          'fiture'  =>  'Ketetapan',          
            'title'       => 'Ketetapan Pajak',
            'jenis_pajak' => $this->jenisPajakModel
                ->orderBy('nama_pajak', 'ASC')
                ->findAll(),
        ];

        return view(
            'App\Modules\Admin\Ketetapan\Views\index',
            $data
        );
    }


    /**
     * Data ketetapan
     */
    public function data()
    {
        $tahun = $this->request->getGet('tahun');

        $builder = $this->ketetapanModel
            ->select('
                ketetapan.*,
                jenis_pajak.nama_pajak,
                jenis_pajak.kode
            ')
            ->join(
                'jenis_pajak',
                'jenis_pajak.id = ketetapan.jenis_id',
                'left'
            );

        if (!empty($tahun)) {
            $builder->where(
                'ketetapan.tahun',
                $tahun
            );
        }

        $data = $builder
            ->orderBy(
                'ketetapan.tahun',
                'DESC'
            )
            ->orderBy(
                'jenis_pajak.nama_pajak',
                'ASC'
            )
            ->orderBy(
                'ketetapan.bulan',
                'ASC'
            )
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'data'   => $data,
        ]);
    }


    /**
     * Ambil 12 bulan
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

        $rows = $this->ketetapanModel
            ->where(
                'jenis_id',
                $jenisId
            )
            ->where(
                'tahun',
                $tahun
            )
            ->orderBy(
                'bulan',
                'ASC'
            )
            ->findAll();

        $result = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {

            $nilai = 0;

            foreach ($rows as $row) {

                if (
                    (int) $row['bulan'] === $bulan
                ) {

                    $nilai =
                        (int) $row['ketetapan'];

                    break;
                }
            }

            $result[$bulan] = [
                'bulan'     => $bulan,
                'ketetapan' => $nilai,
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
        $jenisId  = $this->request->getPost('jenis_id');
        $tahun    = $this->request->getPost('tahun');
        $ketetapan = $this->request->getPost('ketetapan');

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

        if (!is_array($ketetapan)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data ketetapan tidak valid.',
            ]);
        }

        $jenis = $this->jenisPajakModel
            ->find($jenisId);

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

                $nilai =
                    $ketetapan[$bulan] ?? 0;

                /*
                 * Hilangkan format Rupiah
                 */
                $nilai = str_replace(
                    '.',
                    '',
                    $nilai
                );

                $nilai = str_replace(
                    ',',
                    '',
                    $nilai
                );

                $nilai = (int) $nilai;

                $existing =
                    $this->ketetapanModel
                        ->where(
                            'jenis_id',
                            $jenisId
                        )
                        ->where(
                            'tahun',
                            $tahun
                        )
                        ->where(
                            'bulan',
                            $bulan
                        )
                        ->first();

                if ($existing) {

                    $this->ketetapanModel->update(
                        $existing['id'],
                        [
                            'ketetapan' => $nilai,
                            'update_at' => date(
                                'Y-m-d H:i:s'
                            ),
                        ]
                    );

                } else {

                    $this->ketetapanModel->insert([
                        'bulan'     => $bulan,
                        'tahun'     => $tahun,
                        'ketetapan' => $nilai,
                        'jenis_id'  => $jenisId,
                        'create_at' => date(
                            'Y-m-d H:i:s'
                        ),
                        'update_at' => date(
                            'Y-m-d H:i:s'
                        ),
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception(
                    'Gagal menyimpan data.'
                );
            }

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Ketetapan berhasil disimpan.',
                'csrfHash' => csrf_hash(),
            ]);

        } catch (\Throwable $e) {

            if ($db->transStatus() !== false) {
                $db->transRollback();
            }

            log_message(
                'error',
                $e->getMessage()
            );

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal menyimpan ketetapan.',
                'error'   => ENVIRONMENT === 'development'
                    ? $e->getMessage()
                    : null,
                'csrfHash' => csrf_hash(),
            ]);
        }
    }


    /**
     * Hapus seluruh ketetapan
     * berdasarkan jenis + tahun
     */
    public function delete()
    {
        $jenisId = $this->request->getPost(
            'jenis_id'
        );

        $tahun = $this->request->getPost(
            'tahun'
        );

        if (!$jenisId || !$tahun) {

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Parameter tidak lengkap.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $this->ketetapanModel
            ->where(
                'jenis_id',
                $jenisId
            )
            ->where(
                'tahun',
                $tahun
            )
            ->delete();

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Ketetapan berhasil dihapus.',
            'csrfHash' => csrf_hash(),
        ]);
    }
}