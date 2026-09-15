<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRealisasiPiutangToRealisasi extends Migration
{
    public function up()
    {
        $this->forge->addColumn('realisasi', [
            'realisasi_piutang' => [
                'type'       => 'BIGINT',
                'null'       => true,
                'default'    => 0,
                'after'      => 'kode_desa',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('realisasi', 'realisasi_piutang');
    }
}