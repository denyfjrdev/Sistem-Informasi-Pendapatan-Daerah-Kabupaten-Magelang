<?php

namespace App\Modules\Admin\Detil\Models;

use CodeIgniter\Model;

class DetilModel extends Model
{
    protected $table = 'realisasi';

    public function getKecamatan()
    {
        return $this->db
            ->table('kecamatan')
            ->orderBy('nama_kecamatan', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getDesa($kodeKecamatan = null)
    {
        $builder = $this->db
            ->table('desa d')
            ->select('d.kode_desa, d.kode_desa_pbb, d.kode_kecamatan, d.nama_desa')
            ->orderBy('d.nama_desa', 'ASC');

        if (!empty($kodeKecamatan)) {
            $builder->where('d.kode_kecamatan', $kodeKecamatan);
        }

        return $builder->get()->getResultArray();
    }

    public function getJenisPajak()
    {
        return $this->db
            ->table('jenis_pajak')
            ->whereIn('jenis', ['pbb', 'nonpbb', 'kendaraan'])
            ->orderBy('nama_pajak', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Rekap realisasi per jenis pajak dan bulan
     */
    // public function getRealisasi(
    //     int $tahun,
    //     ?string $kodeKecamatan = null,
    //     ?string $kodeDesa = null
    // ) {
    //     $builder = $this->db
    //         ->table('realisasi r');

    //     $builder->select("
    //         jp.id AS jenis_id,
    //         jp.nama_pajak,
    //         r.bulan,
    //         SUM(r.realisasi) AS realisasi
    //     ");

    //     $builder->join(
    //         'target t',
    //         't.id = r.target_id',
    //         'inner'
    //     );

    //     $builder->join(
    //         'jenis_pajak jp',
    //         'jp.id = t.jenis_id',
    //         'inner'
    //     );

    //     $builder->join(
    //         'desa d',
    //         'd.kode_desa = r.kode_desa',
    //         'left'
    //     );

    //     $builder->where('r.tahun', $tahun);

    //     if (!empty($kodeKecamatan)) {
    //         $builder->where(
    //             'd.kode_kecamatan',
    //             $kodeKecamatan
    //         );
    //     }

    //     if (!empty($kodeDesa)) {
    //         $builder->where(
    //             'r.kode_desa',
    //             $kodeDesa
    //         );
    //     }

    //     $builder->groupBy([
    //         'jp.id',
    //         'jp.nama_pajak',
    //         'r.bulan'
    //     ]);

    //     $builder->orderBy('jp.nama_pajak', 'ASC');
    //     $builder->orderBy('r.bulan', 'ASC');

    //     return $builder->get()->getResultArray();
    // }
    public function getRealisasi(
        int $tahun,
        ?string $kodeKecamatan = null,
        ?string $kodeDesa = null,
        ?int $jenisId = null
    ) {
        $builder = $this->db
            ->table('realisasi r');

        $builder->select("
            jp.id AS jenis_id,
            jp.nama_pajak,
            r.bulan,
            SUM(r.realisasi) AS realisasi,

            COALESCE(
                (
                    SELECT SUM(t2.target)
                    FROM target t2
                    WHERE t2.jenis_id = t.jenis_id
                      AND t2.bulan = r.bulan
                      AND t2.tahun = r.tahun
                ),
                0
            ) AS target
        ");

        $builder->join(
            'target t',
            't.id = r.target_id',
            'inner'
        );

        $builder->join(
            'jenis_pajak jp',
            'jp.id = t.jenis_id',
            'inner'
        );

        $builder->join(
            'desa d',
            'd.kode_desa = r.kode_desa',
            'left'
        );

        $builder->where('r.tahun', $tahun);

        if (!empty($kodeKecamatan)) {
            $builder->where(
                'd.kode_kecamatan',
                $kodeKecamatan
            );
        }

        if (!empty($kodeDesa)) {
            $builder->where(
                'r.kode_desa',
                $kodeDesa
            );
        }

        if (!empty($jenisId)) {
            $builder->where(
                't.jenis_id',
                $jenisId
            );
        }

        $builder->groupBy([
            'jp.id',
            'jp.nama_pajak',
            't.jenis_id',
            'r.bulan',
            'r.tahun'
        ]);

        $builder->orderBy('jp.nama_pajak', 'ASC');
        $builder->orderBy('r.bulan', 'ASC');

        return $builder->get()->getResultArray();
    }

    #--- total setahun
    public function getTotal(
        int $tahun,
        ?string $kodeKecamatan = null,
        ?string $kodeDesa = null,
        ?int $jenisId = null
    ) {
        /*
        * TARGET
        */
        $targetBuilder = $this->db
            ->table('target t');

        $targetBuilder->select(
            'COALESCE(SUM(t.target), 0) AS total_target'
        );

        $targetBuilder->where(
            't.tahun',
            $tahun
        );

        if (!empty($jenisId)) {
            $targetBuilder->where(
                't.jenis_id',
                $jenisId
            );
        }

        $target = $targetBuilder
            ->get()
            ->getRowArray();


        /*
        * REALISASI
        */
        $realisasiBuilder = $this->db
            ->table('realisasi r');

        $realisasiBuilder->select(
            'COALESCE(SUM(r.realisasi), 0) AS total_realisasi'
        );

        $realisasiBuilder->join(
            'target t',
            't.id = r.target_id',
            'inner'
        );

        $realisasiBuilder->join(
            'desa d',
            'd.kode_desa = r.kode_desa',
            'left'
        );

        $realisasiBuilder->where(
            'r.tahun',
            $tahun
        );

        if (!empty($kodeKecamatan)) {
            $realisasiBuilder->where(
                'd.kode_kecamatan',
                $kodeKecamatan
            );
        }

        if (!empty($kodeDesa)) {
            $realisasiBuilder->where(
                'r.kode_desa',
                $kodeDesa
            );
        }

        if (!empty($jenisId)) {
            $realisasiBuilder->where(
                't.jenis_id',
                $jenisId
            );
        }

        $realisasi = $realisasiBuilder
            ->get()
            ->getRowArray();


        $totalTarget =
            (float) ($target['total_target'] ?? 0);

        $totalRealisasi =
            (float) ($realisasi['total_realisasi'] ?? 0);


        $persentase = $totalTarget > 0
            ? ($totalRealisasi / $totalTarget) * 100
            : 0;


        return [
            'total_target'    => $totalTarget,
            'total_realisasi' => $totalRealisasi,
            'persentase'      => $persentase
        ];
    }    

}