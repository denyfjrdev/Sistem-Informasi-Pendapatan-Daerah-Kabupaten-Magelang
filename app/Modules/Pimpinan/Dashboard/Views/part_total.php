<?php
  #--- initial variable global
  $semua_realisasi_berjalan = $semua_realisasi_tahun = 0;
  $semua_target_berjalan = $semua_target_tahun = 0;
  $total_persentase_berjalan  = $total_persentase_tahun = 0;

  #---buat data per jenis_pajak
  $array_per_jenis_pajak =  [];
  foreach($realisasi['target'] as $val){
    $array_per_jenis_pajak[$val['jenis_id']]["nama_pajak"]  = $val['nama_pajak'];
  }

  #---isi target
  foreach($realisasi['target'] as $val){
    $total_target  = 0;
    for($i=1;$i<=12;$i++){
      $total_target += $val[$i];

      #----total target sampai saat ini ( bulan berjalan )
      if($i <= date("m")){
        $semua_target_berjalan += $val[$i];
      }
    }
    $array_per_jenis_pajak[$val['jenis_id']]["target"]  = $total_target;
  }

  #---isi realisasi
  foreach($realisasi['realisasi'] as $val){
    $total_realisasi  = 0;
    for($i=1;$i<=12;$i++){
      $total_realisasi += isset($val['detil'][$i]) ? $val['detil'][$i] : 0;

      if($i <= date("m")){
        $semua_realisasi_berjalan += isset($val['detil'][$i]) ? $val['detil'][$i] : 0;
      }
    }
    $array_per_jenis_pajak[$val['jenis_id']]["realisasi"]  = $total_realisasi;
    # piutang belum tersedia dari client — placeholder 0
    $array_per_jenis_pajak[$val['jenis_id']]["piutang"]  = 0;
    $array_per_jenis_pajak[$val['jenis_id']]["realisasi_piutang"] =
        $total_realisasi + ($array_per_jenis_pajak[$val['jenis_id']]["piutang"] ?? 0);
  }

  #---prosentase
  foreach($array_per_jenis_pajak as $index=>$val){
    $target    = $val['target'] ?? 0;
    $realisasi_val = $val['realisasi'] ?? 0;

    $persen = $target != 0
        ? ( $realisasi_val / $target) * 100
        : 0;
    $array_per_jenis_pajak[$index]["persen"]  = $persen;

    $semua_target_tahun += $target;
    $semua_realisasi_tahun += $realisasi_val;
  }

  # Card 1 & progres: realisasi sampai sekarang vs target penuh tahun ini
  $total_persentase_berjalan = $semua_target_tahun > 0
      ? ($semua_realisasi_berjalan / $semua_target_tahun) * 100
      : 0;

  $total_persentase_tahun = $semua_target_tahun > 0
      ? ($semua_realisasi_tahun / $semua_target_tahun) * 100
      : 0;

  # Realisasi + piutang (piutang belum ada → dikosongkan, lihat part_cards.php)
  $semua_piutang = 0;
  $semua_realisasi_piutang = $semua_realisasi_berjalan + $semua_piutang;
  $persen_realisasi_piutang = $semua_target_tahun > 0
      ? ($semua_realisasi_piutang / $semua_target_tahun) * 100
      : 0;

  $bar_width = min(100, max(0, $total_persentase_tahun));

  # 4 jenis pajak terakhir (placeholder: 4 pertama) untuk strip Poin B
  $chip_pajak = array_slice(array_values($array_per_jenis_pajak), 0, 4);
  $top_kecamatan = $top_kecamatan ?? [];
?>

<div class="container-fluid mt-3">

    <!--- FILE 1: 3/4 CARD (outline hijau) + strip info pembaruan --->
    <?= view('Modules\Pimpinan\Dashboard\Views\part_cards', compact(
        'semua_realisasi_tahun', 'total_persentase_tahun', 'semua_realisasi_berjalan',
        'total_persentase_berjalan', 'semua_realisasi_piutang', 'persen_realisasi_piutang',
        'semua_target_berjalan', 'semua_target_tahun', 'bar_width', 'chip_pajak', 'config',
        'top_kecamatan'
    )) ?>

    <!--- FILE 2 & 3: TABEL (kiri) + GRAFIK (kanan) berdampingan --->
    <div class="row mt-3 g-3">
        <?= view('Modules\Pimpinan\Dashboard\Views\part_tabel', compact(
            'array_per_jenis_pajak', 'semua_target_tahun', 'semua_realisasi_tahun', 'semua_piutang', 'total_persentase_tahun'
        )) ?>

        <?= view('Modules\Pimpinan\Dashboard\Views\part_grafik', compact(
            'array_per_jenis_pajak'
        )) ?>
    </div>

</div>
