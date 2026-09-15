<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKodeKecamatanToTargetRealisasi extends Migration
{
    public function up()
    {
        // ---- target: tambah kolom kode_kecamatan (NULL = target kabupaten/global, data lama) ----
        $this->forge->addColumn('target', [
            'kode_kecamatan' => [
                'type'       => 'CHAR',
                'constraint' => 6,
                'null'       => true,
                'after'      => 'jenis_id',
            ],
        ]);

        // ---- realisasi: tambah kolom kode_kecamatan ----
        $this->forge->addColumn('realisasi', [
            'kode_kecamatan' => [
                'type'       => 'CHAR',
                'constraint' => 6,
                'null'       => true,
                'after'      => 'target_id',
            ],
        ]);

        // Ganti unique key realisasi: sebelumnya (target_id,bulan,tahun) hanya
        // mengizinkan 1 baris realisasi per target. Sekarang perlu 1 baris
        // per kecamatan untuk target yang sama.
        $this->db->query('ALTER TABLE `realisasi` DROP INDEX `target_id_bulan_tahun`');
        $this->db->query(
            'ALTER TABLE `realisasi`
             ADD UNIQUE KEY `target_id_bulan_tahun_kec` (`target_id`,`bulan`,`tahun`,`kode_kecamatan`)'
        );

        // Index bantu supaya query per kecamatan cepat
        $this->db->query('ALTER TABLE `target` ADD INDEX `idx_target_kode_kecamatan` (`kode_kecamatan`)');
        $this->db->query('ALTER TABLE `realisasi` ADD INDEX `idx_realisasi_kode_kecamatan` (`kode_kecamatan`)');

        // Foreign key opsional ke kecamatan (aman karena kolom nullable)
        $this->db->query(
            'ALTER TABLE `target`
             ADD CONSTRAINT `fk_target_kecamatan`
             FOREIGN KEY (`kode_kecamatan`) REFERENCES `kecamatan`(`kode_kecamatan`)
             ON DELETE SET NULL ON UPDATE CASCADE'
        );
        $this->db->query(
            'ALTER TABLE `realisasi`
             ADD CONSTRAINT `fk_realisasi_kecamatan`
             FOREIGN KEY (`kode_kecamatan`) REFERENCES `kecamatan`(`kode_kecamatan`)
             ON DELETE SET NULL ON UPDATE CASCADE'
        );
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `target` DROP FOREIGN KEY `fk_target_kecamatan`');
        $this->db->query('ALTER TABLE `realisasi` DROP FOREIGN KEY `fk_realisasi_kecamatan`');

        $this->db->query('ALTER TABLE `target` DROP INDEX `idx_target_kode_kecamatan`');
        $this->db->query('ALTER TABLE `realisasi` DROP INDEX `idx_realisasi_kode_kecamatan`');

        $this->db->query('ALTER TABLE `realisasi` DROP INDEX `target_id_bulan_tahun_kec`');
        $this->db->query(
            'ALTER TABLE `realisasi`
             ADD UNIQUE KEY `target_id_bulan_tahun` (`target_id`,`bulan`,`tahun`)'
        );

        $this->forge->dropColumn('target', 'kode_kecamatan');
        $this->forge->dropColumn('realisasi', 'kode_kecamatan');
    }
}