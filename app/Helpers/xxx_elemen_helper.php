<?php 
  if ( ! function_exists('elemen'))
  {
    function elemen($jenis='',$kategori='baru'){
      $el["SIPDU"]['baru']  = ["xxx","nama_pemohon","alamat_pemohon","lahir","nama_tempat_praktik","alamat_praktek","untuk_praktek","nomor_str","masaberlaku_str"];
      return $el[$jenis][$kategori];
    }
  }
?>