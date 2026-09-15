<?php

namespace App\Modules\Admin\Detil\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Detil\Models\DetilModel;

class DetilController extends BaseController
{
    protected $detilModel;

    public function __construct()
    {
        $this->detilModel = new DetilModel();
    }

    public function index()
    {
        $tahun = $this->request->getGet('tahun')
            ?? date('Y');

        $kodeKecamatan = $this->request->getGet(
            'kode_kecamatan'
        );

        $kodeDesa = $this->request->getGet(
            'kode_desa'
        );

        $jenisIdGet = $this->request->getGet('jenis_id');

        $jenisIdGet = !empty($jenisIdGet)
            ? (int) $jenisIdGet
            : null;        

        $kecamatan = $this->detilModel
            ->getKecamatan();

        $desa = $this->detilModel
            ->getDesa($kodeKecamatan);

        $jenisPajak = $this->detilModel
            ->getJenisPajak();

        $total = $this->detilModel
            ->getTotal(
                (int) $tahun,
                $kodeKecamatan,
                $kodeDesa,
                $jenisIdGet
            );

        $realisasi = $this->detilModel
            ->getRealisasi(
                (int) $tahun,
                $kodeKecamatan,
                $kodeDesa,
                $jenisIdGet
            );

        /*
         * Bentuk data:
         *
         * [
         *   jenis_id => [
         *      nama_pajak => 'Pajak...',
         *      bulan => [
         *          1 => 100000,
         *          2 => 200000,
         *      ]
         *   ]
         * ]
         */        

        $dataRealisasi = [];

        foreach ($realisasi as $row) {

            $jenisId = $row['jenis_id'];
            $bulan   = (int) $row['bulan'];

            if (!isset($dataRealisasi[$jenisId])) {

                $dataRealisasi[$jenisId] = [
                    'jenis_id'        => $jenisId,
                    'nama_pajak'      => $row['nama_pajak'],
                    'bulan'           => [],
                    'total_target'    => 0,
                    'total_realisasi' => 0,
                    'persentase'      => 0
                ];
            }

            $target = (float) ($row['target'] ?? 0);

            $realisasiValue = (float) ($row['realisasi'] ?? 0);

            $dataRealisasi[$jenisId]['bulan'][$bulan] = [
                'target'    => $target,
                'realisasi' => $realisasiValue,
                'persentase' => $target > 0
                    ? ($realisasiValue / $target) * 100
                    : 0
            ];

            $dataRealisasi[$jenisId]['total_target'] += $target;

            $dataRealisasi[$jenisId]['total_realisasi'] +=
                $realisasiValue;
        }


        /*
        |--------------------------------------------------------------------------
        | Pastikan Januari - Desember selalu ada
        |--------------------------------------------------------------------------
        */

        foreach ($dataRealisasi as &$item) {

            for ($i = 1; $i <= 12; $i++) {

                if (!isset($item['bulan'][$i])) {

                    $item['bulan'][$i] = [
                        'target'     => 0,
                        'realisasi'  => 0,
                        'persentase' => 0
                    ];
                }
            }

            ksort($item['bulan']);


            /*
            |--------------------------------------------------------------------------
            | Persentase tahunan per jenis pajak
            |--------------------------------------------------------------------------
            */

            $item['persentase'] =
                $item['total_target'] > 0
                    ? (
                        $item['total_realisasi']
                        / $item['total_target']
                    ) * 100
                    : 0;
        }

        unset($item);

        $data = [
          'menu'  =>  'Detail',
          'fiture'  =>  'Realisasi VS Target',
          'title' => 'Detil Realisasi Pajak',
          'tahun' => $tahun,
          'kodeKecamatan' => $kodeKecamatan,
          'kodeDesa' => $kodeDesa,
          'jenisId' => $jenisIdGet,
          'kecamatan' => $kecamatan,
          'desa' => $desa,
          'jenisPajak' => $jenisPajak,
          'dataRealisasi' => $dataRealisasi,
          'total' => $total
        ];

        return view(
            'App\Modules\Admin\Detil\Views\index',
            $data
        );
    }


    /**
     * AJAX desa berdasarkan kecamatan
     */
    public function desa()
    {
        $kodeKecamatan = $this->request
            ->getGet('kode_kecamatan');

        $desa = $this->detilModel
            ->getDesa($kodeKecamatan);

        return $this->response
            ->setJSON([
                'status' => true,
                'data' => $desa
            ]);
    }
}