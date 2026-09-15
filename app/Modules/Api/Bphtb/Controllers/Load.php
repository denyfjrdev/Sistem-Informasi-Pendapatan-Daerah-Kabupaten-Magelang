<?php
namespace App\Modules\Api\Bphtb\Controllers;

use App\Modules\Api\ApiBaseController;


class Load extends ApiBaseController
{

  public function __construct(){
    $this->db           = \Config\Database::connect();
  }

  function index(){
    $respon = [
      'index' => "index BPHTB"
    ];

    return $this->response->setJSON($respon);
  }

  function load(){
    set_time_limit(180);

    $param          = is_array($this->param) ? $this->param : [];
    $tahun          = $param['tahun'] ?? $this->request->getGet('tahun') ?? '2026';
    $bulan          = $param['bulan'] ?? $this->request->getGet('bulan') ?? '08';
    $kd_kecamatan   = $param['kd_kecamatan'] ?? $this->request->getGet('kd_kecamatan') ?? '010';

    $tahun          = (int) $tahun;
    $bulan          = (int) $bulan;
    $kd_kecamatan   = str_pad((string) $kd_kecamatan, 3, '0', STR_PAD_LEFT);
    $bulan_api      = str_pad((string) $bulan, 2, '0', STR_PAD_LEFT);

    $jenis_id_pbb     = $this->get_jenis_id('pbb', 'PBBP2', 'pbb');
    $jenis_id_bphtb   = $this->get_jenis_id('bphtb', 'BPHTB', 'pbb');

    $load_api         = json_decode($this->api([
      'kd_kecamatan'  => $kd_kecamatan,
      'tahun'         => (string) $tahun,
      'bulan'         => $bulan_api,
    ]));

    $nama_kecamatan   = $load_api->result->nama ?? '';
    $map_desa         = $this->map_desa_pbb($kd_kecamatan, $nama_kecamatan);
    $kelurahan        = $load_api->result->kelurahan ?? [];
    $terisi           = 0;
    $tidak_ketemu     = [];
    $dilewati         = [];
    $skip_nama        = [
      'MUNGKIDPERUMKERETAAPI',
      'HULLERKABMAGELANG',
      'MUNGKIDPDAM',
    ];

    $target_pbb       = $this->get_target([
      'tahun'     => $tahun,
      'bulan'     => $bulan,
      'jenis_id'  => $jenis_id_pbb,
    ]);
    $target_bphtb     = $this->get_target([
      'tahun'     => $tahun,
      'bulan'     => $bulan,
      'jenis_id'  => $jenis_id_bphtb,
    ]);

    foreach ($kelurahan as $val) {
      $nama_kel     = $val->nama ?? '';
      if (in_array($this->norm_nama($nama_kel), $skip_nama, true)) {
        $dilewati[] = [
          'kd_kelurahan' => str_pad((string) ($val->kd_kelurahan ?? ''), 3, '0', STR_PAD_LEFT),
          'nama'         => $nama_kel,
        ];
        continue;
      }

      $kd_kelurahan = str_pad((string) ($val->kd_kelurahan ?? ''), 3, '0', STR_PAD_LEFT);
      $kode_gabung  = $kd_kecamatan . $kd_kelurahan;
      $kode_desa    = $map_desa[$kode_gabung] ?? null;

      if (is_null($kode_desa)) {
        $kode_desa = $this->cari_desa_kode($kd_kecamatan, $kd_kelurahan, $nama_kecamatan);
      }

      if (is_null($kode_desa)) {
        $kode_desa = $this->cari_desa_nama($kd_kecamatan, $val->nama ?? '', $nama_kecamatan);
      }

      if (is_null($kode_desa)) {
        $tidak_ketemu[] = [
          'kd_kelurahan' => $kd_kelurahan,
          'nama'         => $val->nama ?? '',
        ];
        continue;
      }

      $pbb_bulan    = $this->ambil_pbb_bulan($val, $bulan_api);
      $this->input_realisasi([
        'tahun'             => $tahun,
        'bulan'             => $bulan,
        'target_id'         => $target_pbb,
        'kode_desa'         => $kode_desa,
        'realisasi'         => $pbb_bulan['realisasi'],
        'realisasi_piutang' => $pbb_bulan['realisasi_piutang'],
      ]);

      $this->input_realisasi([
        'tahun'             => $tahun,
        'bulan'             => $bulan,
        'target_id'         => $target_bphtb,
        'kode_desa'         => $kode_desa,
        'realisasi'         => (int) ($val->bphtb->realisasi ?? 0),
        'realisasi_piutang' => 0,
      ]);

      $terisi++;
    }

    $this->db->table('config')->update(['last_update_pbb' => date('Y-m-d H:i:s')]);

    return $this->response->setJSON([
      'status'        => true,
      'message'       => 'selesai',
      'kd_kecamatan'  => $kd_kecamatan,
      'tahun'         => $tahun,
      'bulan'         => $bulan,
      'jml_api'       => count($kelurahan),
      'jml_terisi'    => $terisi,
      'dilewati'      => $dilewati,
      'tidak_ketemu'  => $tidak_ketemu,
    ]);
  }

  private function get_jenis_id($kode, $nama_cari, $jenis){
    $row = $this->db->table('jenis_pajak')->where('kode', $kode)->get()->getRow();
    if (!is_null($row)) {
      return $row->id;
    }

    $row = $this->db->table('jenis_pajak')
      ->like('nama_pajak', $nama_cari)
      ->get()->getRow();
    if (!is_null($row)) {
      if (empty($row->kode)) {
        $this->db->table('jenis_pajak')->where('id', $row->id)->update(['kode' => $kode]);
      }
      return $row->id;
    }

    $this->db->table('jenis_pajak')->insert([
      'kode'       => $kode,
      'nama_pajak' => $nama_cari,
      'jenis'      => $jenis,
    ]);
    return $this->db->insertID();
  }

  /**
   * Kecamatan: nama dari API (bukan rumus BPS × 10).
   * Desa: kd_kecamatan + kd_kelurahan, 3 digit terakhir kode BPS.
   * Contoh Salaman: 010 + 001 = 010001 → 3308012001
   */
  private function map_desa_pbb($kd_kecamatan_pbb, $nama_kecamatan_api=''){
    $kode_kecamatan_bps = $this->kode_kecamatan_bps($kd_kecamatan_pbb, $nama_kecamatan_api);
    $map = [];

    if (is_null($kode_kecamatan_bps)) {
      return $map;
    }

    $this->db->table('kecamatan')
      ->where('kode_kecamatan', $kode_kecamatan_bps)
      ->update(['kode_kecamatan_pbb' => $kd_kecamatan_pbb]);

    $desa = $this->db->table('desa')
      ->where('kode_kecamatan', $kode_kecamatan_bps)
      ->get()->getResult();

    foreach ($desa as $row) {
      $kd_kelurahan = str_pad(substr(trim($row->kode_desa), -3), 3, '0', STR_PAD_LEFT);
      $kode_gabung  = $kd_kecamatan_pbb . $kd_kelurahan;
      if (!isset($map[$kode_gabung])) {
        $map[$kode_gabung] = $row->kode_desa;
      }

      if (empty($row->kode_desa_pbb)) {
        $this->db->table('desa')
          ->where('kode_desa', $row->kode_desa)
          ->update(['kode_desa_pbb' => $kode_gabung]);
      }
    }

    return $map;
  }

  private function kode_kecamatan_bps($kd_kecamatan_pbb, $nama_kecamatan_api=''){
    $row = $this->db->table('kecamatan')
      ->where('kode_kecamatan_pbb', $kd_kecamatan_pbb)
      ->get()->getRow();
    if (!is_null($row)) {
      return $row->kode_kecamatan;
    }

    $nama = $this->norm_nama($nama_kecamatan_api);
    $alias = [
      'MUNTHILAN'   => 'MUNTILAN',
      'CANDIMULYO'  => 'CANDIMULYO',
    ];
    $nama = $alias[$nama] ?? $nama;

    $semua = $this->db->table('kecamatan')->get()->getResult();
    foreach ($semua as $kec) {
      if ($this->norm_nama($kec->nama_kecamatan) === $nama) {
        return $kec->kode_kecamatan;
      }
    }

    return null;
  }

  private function norm_nama($s){
    $s = strtoupper(trim((string) $s));
    $s = preg_replace('/^KELURAHAN\s+/', '', $s);
    $s = str_replace(['/', '-', '.', ',', '(', ')'], ' ', $s);
    $s = preg_replace('/\s+/', '', $s);
    return $s;
  }

  private function cari_desa_kode($kd_kecamatan_pbb, $kd_kelurahan, $nama_kecamatan_api=''){
    $kode_kecamatan_bps = $this->kode_kecamatan_bps($kd_kecamatan_pbb, $nama_kecamatan_api);
    if (is_null($kode_kecamatan_bps)) {
      return null;
    }

    foreach (['2', '1'] as $tipe) {
      $kode = $kode_kecamatan_bps . $tipe . $kd_kelurahan;
      $row  = $this->db->table('desa')->where('kode_desa', $kode)->get()->getRow();
      if (!is_null($row)) {
        return $row->kode_desa;
      }
    }

    return null;
  }

  private function cari_desa_nama($kd_kecamatan_pbb, $nama, $nama_kecamatan_api=''){
    $kode_kecamatan_bps = $this->kode_kecamatan_bps($kd_kecamatan_pbb, $nama_kecamatan_api);
    if (is_null($kode_kecamatan_bps) || $nama === '') {
      return null;
    }

    $nama_api = $this->norm_nama($nama);
    $desa = $this->db->table('desa')
      ->where('kode_kecamatan', $kode_kecamatan_bps)
      ->get()->getResult();

    foreach ($desa as $row) {
      if ($this->norm_nama($row->nama_desa) === $nama_api) {
        return $row->kode_desa;
      }
    }

    return null;
  }

  private function ambil_pbb_bulan($val, $bulan_api){
    $realisasi = 0;
    $piutang   = 0;
    $list      = $val->pbb->per_bulan ?? [];

    foreach ($list as $item) {
      $b = str_pad((string) ($item->bulan ?? ''), 2, '0', STR_PAD_LEFT);
      if ($b === $bulan_api) {
        $realisasi = (int) ($item->realisasi ?? 0);
        $piutang   = (int) ($item->realisasi_piutang ?? 0);
        break;
      }
    }

    return [
      'realisasi'         => $realisasi,
      'realisasi_piutang' => $piutang,
    ];
  }

  private function input_realisasi($param){
    $target_id  = $param['target_id'];
    $bulan      = $param['bulan'];
    $tahun      = $param['tahun'];
    $realisasi  = $param['realisasi'];
    $kode_desa  = $param['kode_desa'];
    $piutang    = $param['realisasi_piutang'] ?? 0;

    if ($target_id == false) {
      return;
    }

    $target_id = $target_id['id'];
    $field = [
      'tahun'             => $tahun,
      'bulan'             => $bulan,
      'target_id'         => $target_id,
      'realisasi'         => $realisasi,
      'realisasi_piutang' => $piutang,
      'kode_desa'         => $kode_desa,
      'create_at'         => date('Y-m-d H:i:s'),
    ];

    $cek = $this->db->table('realisasi')
      ->where('target_id', $target_id)
      ->where('bulan', $bulan)
      ->where('tahun', $tahun)
      ->where('kode_desa', $kode_desa)
      ->get()->getRow();

    if (is_null($cek)) {
      $this->db->table('realisasi')->insert($field);
    } else {
      $this->db->table('realisasi')->where('id', $cek->id)->update([
        'realisasi'         => $realisasi,
        'realisasi_piutang' => $piutang,
        'update_at'         => date('Y-m-d H:i:s'),
      ]);
    }
  }

  private function get_target($param=[]){
    $data = $this->db->table('target')
      ->where('tahun', $param['tahun'])
      ->where('bulan', $param['bulan'])
      ->where('jenis_id', $param['jenis_id'])
      ->get()->getRow();

    if (is_null($data)) {
      $this->db->table('target')->insert([
        'tahun'     => $param['tahun'],
        'bulan'     => $param['bulan'],
        'target'    => 0,
        'jenis_id'  => $param['jenis_id'],
        'create_at' => date('Y-m-d H:i:s'),
      ]);
      return ['id' => $this->db->insertID()];
    }

    return ['id' => $data->id];
  }

  private function api($data=[]){
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => "https://sibphtbprima.magelangkab.go.id/api/dashboard/realisasi-per-kelurahan",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 90,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($data),
      CURLOPT_HTTPHEADER => [
        "Authorization: Basic TTRnM2w0bmcjS2FiOk00ZzNsNG5nI0thYg==",
        "Content-Type: application/json"
      ],
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return json_encode(['code' => '500', 'message' => $err, 'result' => null]);
    }

    return $response;
  }

}
