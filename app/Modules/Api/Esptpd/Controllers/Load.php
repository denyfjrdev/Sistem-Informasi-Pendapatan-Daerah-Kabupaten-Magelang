<?php
namespace App\Modules\Api\Esptpd\Controllers;

use App\Modules\Api\ApiBaseController;


class Load extends ApiBaseController
{

  private $bulan_field = [
    1  => 'REALISASI JANUARI',
    2  => 'REALISASI FEBRUARI',
    3  => 'REALISASI MARET',
    4  => 'REALISASI APRIL',
    5  => 'REALISASI MEI',
    6  => 'REALISASI JUNI',
    7  => 'REALISASI JULI',
    8  => 'REALISASI AGUSTUS',
    9  => 'REALISASI SEPTEMBER',
    10 => 'REALISASI OKTOBER',
    11 => 'REALISASI NOVEMBER',
    12 => 'REALISASI DESEMBER',
  ];

  private $alias_desa = [
    'SOMOKERTO'     => 'SOMOKETRO',
    'LASANPURO'     => 'LESANPURO',
    'MANGUNSUKO'    => 'MANGUNSOKO',
    'PODOSUKO'      => 'PODOSOKO',
    'KAPUAN'        => 'KAPUHAN',
    'SUMBERARUM'    => 'SUMURARUM',
    'KALEGEN'       => 'KLEGEN',
    'SELOMERAH'     => 'SELOMIRAH',
    'SUMBERREJO'    => 'SUMBEREJO',
    'NGARGOSUKO'    => 'NGARGOSOKO',
    'NGAWONGSO'     => 'NGAWONGGO',
    'BANJARREJO'    => 'BANJAREJO',
    'PANGARENGAN'   => 'PENGARENGAN',
    'BANJARSEDAYU'  => 'BANDARSEDAYU',
  ];

  public function __construct(){
    $this->db = \Config\Database::connect();
  }

  function index(){
    return $this->response->setJSON([
      'index' => 'index ESPTPD',
    ]);
  }

  function load_esptpd(){
    return $this->load();
  }

  function load(){
    set_time_limit(180);

    $param  = is_array($this->param) ? $this->param : [];
    $tahun  = (int) ($param['tahun'] ?? $this->request->getGet('tahun') ?? 2026);
    $bulan  = (int) ($param['bulan'] ?? $this->request->getGet('bulan') ?? 1);

    if ($bulan < 1 || $bulan > 12) {
      return $this->response->setJSON([
        'status'  => false,
        'message' => 'bulan tidak valid',
      ]);
    }

    $field_bulan = $this->bulan_field[$bulan];
    $load_api    = $this->ambil_api($tahun, 'realisasi/realisasi_perkelurahan_bulanan');
    $rows        = $load_api['data'] ?? [];

    if (!is_array($rows) || count($rows) === 0) {
      return $this->response->setJSON([
        'status'  => false,
        'message' => 'data API kosong / gagal',
        'raw'     => $load_api,
      ]);
    }

    $jenis_id = $this->get_jenis_esptpd();
    $target   = $this->get_target([
      'tahun'    => $tahun,
      'bulan'    => $bulan,
      'jenis_id' => $jenis_id,
    ]);

    $map_desa = $this->map_desa_esptpd();
    $skip_nama = ['LUARKABUPATEN'];

    $terisi        = 0;
    $total_mapped  = 0;
    $dilewati      = [];
    $tidak_ketemu  = [];
    $lebih         = [];
    $desa_terpakai = [];

    foreach ($rows as $val) {
      $nama_kec  = $val['KECAMATAN'] ?? ($val['kecamatan'] ?? '');
      $nama_kel  = $val['KELURAHAN'] ?? ($val['kelurahan'] ?? '');
      $kd_kec    = str_pad((string) ($val['KODE KECAMATAN'] ?? ''), 2, '0', STR_PAD_LEFT);
      $kd_kel    = str_pad((string) ($val['KODE KELURAHAN'] ?? ''), 2, '0', STR_PAD_LEFT);
      $norm_kel  = $this->norm_nama($nama_kel);
      $realisasi = (int) ($val[$field_bulan] ?? 0);

      $info = [
        'kd_kecamatan' => $kd_kec,
        'kd_kelurahan' => $kd_kel,
        'kecamatan'    => $nama_kec,
        'kelurahan'    => $nama_kel,
        'realisasi'    => $realisasi,
      ];

      if (in_array($norm_kel, $skip_nama, true) || in_array($this->norm_nama($nama_kec), $skip_nama, true)) {
        $dilewati[] = $info;
        $lebih[]    = $info;
        continue;
      }

      $kode_desa = $this->cari_kode_desa($map_desa, $nama_kec, $nama_kel);
      if (is_null($kode_desa)) {
        $tidak_ketemu[] = $info;
        $lebih[]        = $info;
        continue;
      }

      $this->input_realisasi([
        'tahun'     => $tahun,
        'bulan'     => $bulan,
        'target_id' => $target,
        'kode_desa' => $kode_desa,
        'realisasi' => $realisasi,
      ]);

      $desa_terpakai[$kode_desa] = true;
      $total_mapped += $realisasi;
      $terisi++;
    }

    $desa_db = $this->db->table('desa')->select('kode_desa, nama_desa, kode_kecamatan')->get()->getResult();
    $db_tanpa_api = [];
    foreach ($desa_db as $d) {
      if (isset($desa_terpakai[$d->kode_desa])) {
        continue;
      }
      $db_tanpa_api[] = [
        'kode_desa'      => $d->kode_desa,
        'nama_desa'      => $d->nama_desa,
        'kode_kecamatan' => $d->kode_kecamatan,
      ];
      $this->input_realisasi([
        'tahun'     => $tahun,
        'bulan'     => $bulan,
        'target_id' => $target,
        'kode_desa' => $d->kode_desa,
        'realisasi' => 0,
      ]);
    }

    $this->db->table('config')->update(['last_update_esptpd' => date('Y-m-d H:i:s')]);

    return $this->response->setJSON([
      'status'       => true,
      'message'      => 'selesai',
      'tahun'        => $tahun,
      'bulan'        => $bulan,
      'jml_api'      => count($rows),
      'jml_terisi'   => $terisi,
      'jml_db'       => count($desa_db),
      'total_mapped' => $total_mapped,
      'jml_lebih'    => count($lebih),
      'lebih'        => $lebih,
      'dilewati'     => $dilewati,
      'tidak_ketemu' => $tidak_ketemu,
      'db_tanpa_api' => $db_tanpa_api,
    ]);
  }

  function load_lra(){
    set_time_limit(180);

    $param = is_array($this->param) ? $this->param : [];
    $tahun = (int) ($param['tahun'] ?? $this->request->getGet('tahun') ?? 2026);
    $bulan_filter = $param['bulan'] ?? $this->request->getGet('bulan');
    $bulan_filter = ($bulan_filter === null || $bulan_filter === '') ? null : (int) $bulan_filter;

    $load_api = $this->ambil_api($tahun, 'realisasi/lra_bulanan_pajak_jenis');
    $rows     = $load_api['data'] ?? [];

    if (!is_array($rows) || count($rows) === 0) {
      return $this->response->setJSON([
        'status'  => false,
        'message' => 'data API LRA kosong / gagal',
        'raw'     => $load_api,
      ]);
    }

    $skip_nama = ['pbb', 'bphtb', 'opsen'];
    $terisi    = [];
    $dilewati  = [];

    foreach ($rows as $val) {
      $kode       = (string) ($val['KODE REKENING'] ?? '');
      $nama_pajak = (string) ($val['JENIS PAJAK'] ?? '');
      $norm       = strtolower($nama_pajak);

      $skip = false;
      foreach ($skip_nama as $s) {
        if (strpos($norm, $s) !== false) {
          $skip = true;
          break;
        }
      }
      if ($skip) {
        $dilewati[] = ['kode' => $kode, 'nama_pajak' => $nama_pajak];
        continue;
      }

      $jenis = $this->get_jenis_by_kode($kode, $nama_pajak);
      $bulan_isi = [];

      for ($i = 1; $i <= 12; $i++) {
        if ($bulan_filter !== null && $i !== $bulan_filter) {
          continue;
        }
        $field_realisasi = $this->bulan_field[$i];
        $field_anggaran  = str_replace('REALISASI', 'ANGGARAN', $field_realisasi);
        $realisasi       = (int) ($val[$field_realisasi] ?? 0);
        $target_nilai    = (int) ($val[$field_anggaran] ?? 0);

        $target = $this->get_target([
          'tahun'    => $tahun,
          'bulan'    => $i,
          'jenis_id' => $jenis['id'],
        ]);

        $this->input_realisasi_kabupaten([
          'tahun'     => $tahun,
          'bulan'     => $i,
          'target_id' => $target,
          'realisasi' => $realisasi,
        ]);

        if ($target_nilai > 0) {
          $this->db->table('target')->where('id', $target['id'])->update([
            'target'    => $target_nilai,
            'update_at' => date('Y-m-d H:i:s'),
          ]);
        }

        $bulan_isi[] = $i;
      }

      $terisi[] = [
        'kode'       => $kode,
        'nama_pajak' => $nama_pajak,
        'jenis_id'   => $jenis['id'],
        'bulan'      => $bulan_isi,
      ];
    }

    $this->db->table('config')->update(['last_update_esptpd' => date('Y-m-d H:i:s')]);

    return $this->response->setJSON([
      'status'    => true,
      'message'   => 'selesai',
      'tahun'     => $tahun,
      'bulan'     => $bulan_filter,
      'jml_api'   => count($rows),
      'jml_pajak' => count($terisi),
      'pajak'     => $terisi,
      'dilewati'  => $dilewati,
    ]);
  }

  private function get_jenis_by_kode($kode, $nama_pajak){
    $row = $this->db->table('jenis_pajak')->where('kode', $kode)->get()->getRow();
    if (!is_null($row)) {
      return ['id' => (int) $row->id];
    }

    $row = $this->db->table('jenis_pajak')
      ->like('nama_pajak', $nama_pajak)
      ->where('jenis', 'nonpbb')
      ->get()->getRow();
    if (!is_null($row)) {
      if (empty($row->kode) && $kode !== '') {
        $this->db->table('jenis_pajak')->where('id', $row->id)->update(['kode' => $kode]);
      }
      return ['id' => (int) $row->id];
    }

    $this->db->table('jenis_pajak')->insert([
      'kode'       => $kode,
      'nama_pajak' => $nama_pajak,
      'jenis'      => 'nonpbb',
      'create_at'  => date('Y-m-d H:i:s'),
    ]);
    return ['id' => (int) $this->db->insertID()];
  }

  private function input_realisasi_kabupaten($param){
    $target_id = $param['target_id'];
    if ($target_id == false) {
      return;
    }
    $target_id = $target_id['id'];
    $tahun     = $param['tahun'];
    $bulan     = $param['bulan'];
    $realisasi = $param['realisasi'];

    $cek = $this->db->table('realisasi')
      ->where('target_id', $target_id)
      ->where('bulan', $bulan)
      ->where('tahun', $tahun)
      ->groupStart()
        ->where('kode_desa', null)
        ->orWhere('kode_desa', '')
      ->groupEnd()
      ->get()->getRow();

    if (is_null($cek)) {
      $this->db->table('realisasi')->insert([
        'tahun'     => $tahun,
        'bulan'     => $bulan,
        'target_id' => $target_id,
        'realisasi' => $realisasi,
        'create_at' => date('Y-m-d H:i:s'),
      ]);
    } else {
      $this->db->table('realisasi')->where('id', $cek->id)->update([
        'realisasi' => $realisasi,
        'update_at' => date('Y-m-d H:i:s'),
      ]);
    }
  }

  private function get_jenis_esptpd(){
    $row = $this->db->table('jenis_pajak')->where('kode', 'esptpd')->get()->getRow();
    if (!is_null($row)) {
      return (int) $row->id;
    }

    $this->db->table('jenis_pajak')->insert([
      'kode'       => 'esptpd',
      'nama_pajak' => 'e-SPTPD',
      'jenis'      => 'nonpbb',
      'create_at'  => date('Y-m-d H:i:s'),
    ]);
    return (int) $this->db->insertID();
  }

  private function ambil_api($tahun, $path = 'realisasi/realisasi_perkelurahan_bulanan'){
    $total_loop = 1;
    $load_api   = [];

    while ($total_loop < 3) {
      $token = $this->db->table('config')->get()->getRow()->token_esptpd;
      $raw   = $this->api([
        'tahun' => $tahun,
        'token' => $token,
        'path'  => $path,
      ]);
      $load_api = json_decode($raw, true);

      if (isset($load_api['status']) && $load_api['status'] === false) {
        $request_token = json_decode($this->refresh_token());
        if (!isset($request_token->status) || $request_token->status != true) {
          return $load_api;
        }
        $this->db->table('config')->update([
          'token_esptpd' => $request_token->access_token,
        ]);
      } elseif (isset($load_api['data'])) {
        break;
      }

      $total_loop++;
    }

    return is_array($load_api) ? $load_api : [];
  }

  private function map_desa_esptpd(){
    $map = [];
    $desa = $this->db->table('desa d')
      ->select('d.kode_desa, d.nama_desa, k.nama_kecamatan')
      ->join('kecamatan k', 'k.kode_kecamatan = d.kode_kecamatan')
      ->get()->getResult();

    foreach ($desa as $row) {
      $kec = $this->norm_nama($row->nama_kecamatan);
      $kel = $this->norm_nama($row->nama_desa);
      if ($kec === '' || $kel === '') {
        continue;
      }
      if (!isset($map[$kec][$kel])) {
        $map[$kec][$kel] = $row->kode_desa;
      }
    }

    return $map;
  }

  private function cari_kode_desa($map, $nama_kec, $nama_kel){
    $kec = $this->norm_nama($nama_kec);
    $cands = $this->kandidat_nama($nama_kel);

    foreach ($cands as $kel) {
      if (isset($map[$kec][$kel])) {
        return $map[$kec][$kel];
      }
    }

    return null;
  }

  private function kandidat_nama($nama){
    $n = $this->norm_nama($nama);
    $out = [$n];
    if (isset($this->alias_desa[$n])) {
      $out[] = $this->norm_nama($this->alias_desa[$n]);
    }
    return array_unique($out);
  }

  private function norm_nama($s){
    $s = strtoupper(trim((string) $s));
    $s = preg_replace('/^KELURAHAN\s+/', '', $s);
    $s = preg_replace('/^DESA\s+/', '', $s);
    $s = preg_replace('/\([^)]*\)/', '', $s);
    $s = str_replace(['/', '-', '.', ',', "'"], ' ', $s);
    $s = preg_replace('/\s+/', '', $s);
    return $s;
  }

  private function hapus_realisasi_kabupaten($target_map, $tahun, $bulan){
    foreach ($target_map as $target) {
      if ($target == false) {
        continue;
      }
      $target_id = $target['id'];
      $this->db->table('realisasi')
        ->where('target_id', $target_id)
        ->where('tahun', $tahun)
        ->where('bulan', $bulan)
        ->groupStart()
          ->where('kode_desa', null)
          ->orWhere('kode_desa', '')
        ->groupEnd()
        ->delete();
    }
  }

  private function input_realisasi($param){
    $target_id = $param['target_id'];
    $bulan     = $param['bulan'];
    $tahun     = $param['tahun'];
    $realisasi = $param['realisasi'];
    $kode_desa = $param['kode_desa'];

    if ($target_id == false) {
      return;
    }

    $target_id = $target_id['id'];
    $field = [
      'tahun'     => $tahun,
      'bulan'     => $bulan,
      'target_id' => $target_id,
      'realisasi' => $realisasi,
      'kode_desa' => $kode_desa,
      'create_at' => date('Y-m-d H:i:s'),
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
        'realisasi' => $realisasi,
        'update_at' => date('Y-m-d H:i:s'),
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

  private function api($param=[]){
    $tahun = $param['tahun'];
    $token = $param['token'];
    $path  = $param['path'] ?? 'realisasi/realisasi_perkelurahan_bulanan';

    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => 'https://esptpd.magelangkab.go.id/wspdl-kab-magelang/api/' . $path . '?tahun=' . $tahun,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 120,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
      ],
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
      return json_encode(['status' => false, 'message' => $err]);
    }

    return $response;
  }

  private function refresh_token(){
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => 'https://esptpd.magelangkab.go.id/wspdl-kab-magelang/api/auth/login',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode([
        'username' => 'api-dashboard',
        'password' => 'g,$fs6nLW5tRVUmI',
      ]),
      CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
      ],
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
      return json_encode(['status' => false, 'message' => $err]);
    }

    return $response;
  }

}
