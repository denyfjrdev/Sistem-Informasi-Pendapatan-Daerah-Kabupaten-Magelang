<?php

namespace App\Modules\Admin\Detil\Controllers;

use App\Modules\Admin\AdminBaseController;
use App\Modules\Admin\Detil\Models\DetilModel;

class DetilController extends AdminBaseController
{
    protected $detilModel;

    public function __construct()
    {
        $this->detilModel = new DetilModel();
    }

    public function index()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $kodeKecamatan = $this->request->getGet('kode_kecamatan') ?: null;
        $kodeDesa = $this->request->getGet('kode_desa') ?: null;
        $jenisIdGet = $this->request->getGet('jenis_id');
        $jenisIdGet = !empty($jenisIdGet) ? (int) $jenisIdGet : null;

        $kecamatan = $this->detilModel->getKecamatan();
        $desa = $this->detilModel->getDesa($kodeKecamatan);
        $jenisPajak = $this->detilModel->getJenisPajak();

        $jenisIdsValid = array_map('intval', array_column($jenisPajak, 'id'));
        if ($jenisIdGet !== null && !in_array($jenisIdGet, $jenisIdsValid, true)) {
            $jenisIdGet = null;
        }
        $jenisPakaiKelurahan = $this->detilModel->jenisPakaiKelurahan($jenisIdGet);

        $total = $this->detilModel->getTotal(
            (int) $tahun,
            $kodeKecamatan,
            $kodeDesa,
            $jenisIdGet
        );

        $realisasi = $this->detilModel->getRealisasi(
            (int) $tahun,
            $kodeKecamatan,
            $kodeDesa,
            $jenisIdGet
        );

        $realisasiKecamatanRaw = $this->detilModel->getRealisasiPerKecamatan(
            (int) $tahun,
            $kodeKecamatan,
            $kodeDesa,
            $jenisIdGet
        );

        $realisasiDesaRaw = $this->detilModel->getRealisasiPerDesa(
            (int) $tahun,
            $kodeKecamatan,
            $kodeDesa,
            $jenisIdGet
        );

        /*
        |--------------------------------------------------------------------------
        | Map jenis_id → kategori (PBB / Opsen / STPD) & kolom tampilan
        |--------------------------------------------------------------------------
        */
        $mapJenisKategori = [];
        $mapJenisNama = [];
        foreach ($jenisPajak as $jp) {
            $mapJenisNama[(int) $jp['id']] = $jp['nama_pajak'];
            $jenis = strtolower((string) ($jp['jenis'] ?? ''));
            if ($jenis === 'pbb') {
                $mapJenisKategori[(int) $jp['id']] = 'pbb';
            } elseif ($jenis === 'kendaraan') {
                $mapJenisKategori[(int) $jp['id']] = 'opsen';
            } else {
                $mapJenisKategori[(int) $jp['id']] = 'stpd';
            }
        }

        $showTotalKolom = ($jenisIdGet === null);
        if ($jenisIdGet !== null) {
            $kolomPajak = [[
                'key'   => (string) $jenisIdGet,
                'label' => $mapJenisNama[$jenisIdGet] ?? 'Jenis Pajak',
            ]];
        } else {
            $kolomPajak = [
                ['key' => 'pbb',   'label' => 'PBB'],
                ['key' => 'opsen', 'label' => 'Opsen'],
                ['key' => 'stpd',  'label' => 'STPD'],
            ];
        }

        $emptyPajak = [];
        foreach ($kolomPajak as $kol) {
            $emptyPajak[$kol['key']] = 0.0;
        }

        $resolveKey = function (int $jenisId) use ($jenisIdGet, $mapJenisKategori) {
            if ($jenisIdGet !== null) {
                return (string) $jenisId;
            }
            return $mapJenisKategori[$jenisId] ?? 'stpd';
        };

        /*
        |--------------------------------------------------------------------------
        | Data bulanan (untuk grafik + tab Per Bulan)
        |--------------------------------------------------------------------------
        */
        $dataRealisasi = [];
        foreach ($realisasi as $row) {
            $jenisId = (int) $row['jenis_id'];
            $bulan   = (int) $row['bulan'];

            if (!isset($dataRealisasi[$jenisId])) {
                $dataRealisasi[$jenisId] = [
                    'jenis_id'        => $jenisId,
                    'nama_pajak'      => $row['nama_pajak'],
                    'bulan'           => [],
                    'total_target'    => 0,
                    'total_realisasi' => 0,
                    'persentase'      => 0,
                ];
            }

            $target = (float) ($row['target'] ?? 0);
            $realisasiValue = (float) ($row['realisasi'] ?? 0);

            $dataRealisasi[$jenisId]['bulan'][$bulan] = [
                'target'     => $target,
                'realisasi'  => $realisasiValue,
                'persentase' => $target > 0 ? ($realisasiValue / $target) * 100 : 0,
            ];

            $dataRealisasi[$jenisId]['total_target'] += $target;
            $dataRealisasi[$jenisId]['total_realisasi'] += $realisasiValue;
        }

        foreach ($dataRealisasi as &$item) {
            for ($i = 1; $i <= 12; $i++) {
                if (!isset($item['bulan'][$i])) {
                    $item['bulan'][$i] = [
                        'target'     => 0,
                        'realisasi'  => 0,
                        'persentase' => 0,
                    ];
                }
            }
            ksort($item['bulan']);
            $item['persentase'] = $item['total_target'] > 0
                ? ($item['total_realisasi'] / $item['total_target']) * 100
                : 0;
        }
        unset($item);

        // Agregat bulanan untuk tabel Per Bulan (sesuai filter aktif)
        $dataBulanan = [];
        for ($b = 1; $b <= 12; $b++) {
            $t = 0.0;
            $r = 0.0;
            foreach ($dataRealisasi as $item) {
                $t += (float) ($item['bulan'][$b]['target'] ?? 0);
                $r += (float) ($item['bulan'][$b]['realisasi'] ?? 0);
            }
            $dataBulanan[$b] = [
                'target'     => $t,
                'realisasi'  => $r,
                'persentase' => $t > 0 ? ($r / $t) * 100 : 0,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Pivot kecamatan (zero-fill 21 kecamatan)
        |--------------------------------------------------------------------------
        */
        $dataKecamatan = [];
        foreach ($kecamatan as $kec) {
            $dataKecamatan[$kec['kode_kecamatan']] = [
                'kode_kecamatan'  => $kec['kode_kecamatan'],
                'nama_kecamatan'  => $kec['nama_kecamatan'],
                'pajak'           => $emptyPajak,
                'total_realisasi' => 0.0,
            ];
        }

        foreach ($realisasiKecamatanRaw as $row) {
            $kode = $row['kode_kecamatan'] ?? null;
            if ($kode === null || $kode === '') {
                continue;
            }
            if (!isset($dataKecamatan[$kode])) {
                $dataKecamatan[$kode] = [
                    'kode_kecamatan'  => $kode,
                    'nama_kecamatan'  => $row['nama_kecamatan'] ?? '(Tanpa Kecamatan)',
                    'pajak'           => $emptyPajak,
                    'total_realisasi' => 0.0,
                ];
            }

            $nilai = (float) ($row['realisasi'] ?? 0);
            $key = $resolveKey((int) $row['jenis_id']);
            if (!array_key_exists($key, $dataKecamatan[$kode]['pajak'])) {
                $dataKecamatan[$kode]['pajak'][$key] = 0.0;
            }
            $dataKecamatan[$kode]['pajak'][$key] += $nilai;
            $dataKecamatan[$kode]['total_realisasi'] += $nilai;
        }

        /*
        |--------------------------------------------------------------------------
        | Pivot desa
        |--------------------------------------------------------------------------
        */
        $dataDesa = [];
        foreach ($realisasiDesaRaw as $row) {
            $kode = $row['kode_desa'] ?? '-';
            if (!isset($dataDesa[$kode])) {
                $dataDesa[$kode] = [
                    'kode_desa'       => $kode,
                    'nama_desa'       => $row['nama_desa'] ?? '(Tanpa Desa)',
                    'kode_kecamatan'  => $row['kode_kecamatan'] ?? '-',
                    'nama_kecamatan'  => $row['nama_kecamatan'] ?? '-',
                    'pajak'           => $emptyPajak,
                    'total_realisasi' => 0.0,
                ];
            }

            $nilai = (float) ($row['realisasi'] ?? 0);
            $key = $resolveKey((int) $row['jenis_id']);
            if (!array_key_exists($key, $dataDesa[$kode]['pajak'])) {
                $dataDesa[$kode]['pajak'][$key] = 0.0;
            }
            $dataDesa[$kode]['pajak'][$key] += $nilai;
            $dataDesa[$kode]['total_realisasi'] += $nilai;
        }

        /*
        |--------------------------------------------------------------------------
        | Mode wilayah + baris tabel hierarki (agent.md)
        |--------------------------------------------------------------------------
        */
        if (!empty($kodeDesa)) {
            $modeWilayah = 'one_desa';
        } elseif (!empty($kodeKecamatan)) {
            $modeWilayah = 'one_kec';
        } else {
            $modeWilayah = 'all_kec';
        }

        $tabelWilayah = [];

        if ($modeWilayah === 'all_kec') {
            foreach ($dataKecamatan as $kec) {
                $tabelWilayah[] = [
                    'tipe'            => 'kecamatan',
                    'nama'            => $kec['nama_kecamatan'],
                    'pajak'           => $kec['pajak'],
                    'total_realisasi' => $kec['total_realisasi'],
                ];
            }
        } elseif ($modeWilayah === 'one_kec') {
            $kecRow = $dataKecamatan[$kodeKecamatan] ?? [
                'nama_kecamatan'  => '-',
                'pajak'           => $emptyPajak,
                'total_realisasi' => 0.0,
            ];
            $tabelWilayah[] = [
                'tipe'            => 'kecamatan',
                'nama'            => $kecRow['nama_kecamatan'],
                'pajak'           => $kecRow['pajak'],
                'total_realisasi' => $kecRow['total_realisasi'],
            ];

            // Pastikan semua desa di kecamatan muncul (meski 0)
            $desaMaster = $this->detilModel->getDesa($kodeKecamatan);
            foreach ($desaMaster as $d) {
                $kodeD = $d['kode_desa'];
                $rowD = $dataDesa[$kodeD] ?? [
                    'nama_desa'       => $d['nama_desa'],
                    'pajak'           => $emptyPajak,
                    'total_realisasi' => 0.0,
                ];
                $tabelWilayah[] = [
                    'tipe'            => 'desa',
                    'nama'            => $rowD['nama_desa'] ?? $d['nama_desa'],
                    'pajak'           => $rowD['pajak'] ?? $emptyPajak,
                    'total_realisasi' => $rowD['total_realisasi'] ?? 0.0,
                ];
            }
        } else {
            // one_desa
            $rowD = null;
            foreach ($dataDesa as $ds) {
                if (($ds['kode_desa'] ?? '') === $kodeDesa) {
                    $rowD = $ds;
                    break;
                }
            }
            if ($rowD === null) {
                $namaDesa = '-';
                foreach ($desa as $d) {
                    if ($d['kode_desa'] === $kodeDesa) {
                        $namaDesa = $d['nama_desa'];
                        break;
                    }
                }
                $rowD = [
                    'nama_desa'       => $namaDesa,
                    'pajak'           => $emptyPajak,
                    'total_realisasi' => 0.0,
                ];
            }
            $tabelWilayah[] = [
                'tipe'            => 'desa',
                'nama'            => $rowD['nama_desa'],
                'pajak'           => $rowD['pajak'],
                'total_realisasi' => $rowD['total_realisasi'],
            ];
        }

        // Baris total (kecuali saat 1 jenis pajak — tetap boleh total nilai, tapi kolom Total disembunyikan)
        $totalRowPajak = $emptyPajak;
        $totalRowSum = 0.0;
        foreach ($tabelWilayah as $row) {
            // Hindari double-count: saat one_kec, baris kecamatan sudah = sum desa
            if ($modeWilayah === 'one_kec' && ($row['tipe'] ?? '') === 'desa') {
                continue;
            }
            if ($modeWilayah === 'one_kec' && ($row['tipe'] ?? '') === 'kecamatan') {
                foreach ($kolomPajak as $kol) {
                    $totalRowPajak[$kol['key']] += (float) ($row['pajak'][$kol['key']] ?? 0);
                }
                $totalRowSum += (float) $row['total_realisasi'];
                continue;
            }
            if ($modeWilayah !== 'one_kec') {
                foreach ($kolomPajak as $kol) {
                    $totalRowPajak[$kol['key']] += (float) ($row['pajak'][$kol['key']] ?? 0);
                }
                $totalRowSum += (float) $row['total_realisasi'];
            }
        }

        if (!$jenisPakaiKelurahan) {
            $tabelWilayah = [];
            $dataKecamatan = [];
            $dataDesa = [];
            $totalRowPajak = $emptyPajak;
            $totalRowSum = 0.0;
        }

        $data = [
            'data_user'       => $this->data_user,
            'role_login'      => $this->data_user->role,
            'menu'            => 'Monitoring Perpajakan',
            'fiture'          => 'Realisasi VS Target',
            'title'           => 'Detil Realisasi Pajak',
            'tahun'           => $tahun,
            'kodeKecamatan'   => $kodeKecamatan,
            'kodeDesa'        => $kodeDesa,
            'jenisId'         => $jenisIdGet,
            'jenisPakaiKelurahan' => $jenisPakaiKelurahan,
            'kecamatan'       => $kecamatan,
            'desa'            => $desa,
            'jenisPajak'      => $jenisPajak,
            'dataRealisasi'   => $dataRealisasi,
            'dataBulanan'     => $dataBulanan,
            'dataKecamatan'   => $dataKecamatan,
            'dataDesa'        => $dataDesa,
            'tabelWilayah'    => $tabelWilayah,
            'totalRowPajak'   => $totalRowPajak,
            'totalRowSum'     => $totalRowSum,
            'kolomPajak'      => $kolomPajak,
            'showTotalKolom'  => $showTotalKolom,
            'modeWilayah'     => $modeWilayah,
            'total'           => $total,
        ];

        return view('App\Modules\Admin\Detil\Views\index', $data);
    }

    public function desa()
    {
        $kodeKecamatan = $this->request->getGet('kode_kecamatan');
        $desa = $this->detilModel->getDesa($kodeKecamatan);

        return $this->response->setJSON([
            'status' => true,
            'data'   => $desa,
        ]);
    }
}
