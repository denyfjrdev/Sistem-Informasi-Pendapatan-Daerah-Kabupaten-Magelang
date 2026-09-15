<?php

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://sibphtbprima.magelangkab.go.id/api/dashboard/realisasi-per-kelurahan",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'kd_kecamatan' => '010',
    'tahun' => '2026',
    'bulan' => '08'
  ]),
  CURLOPT_HTTPHEADER => [
    "Authorization: Basic TTRnM2w0bmcjS2FiOk00ZzNsNG5nI0thYg==",
    "Content-Type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}