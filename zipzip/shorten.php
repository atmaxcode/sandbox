<?php
$longUrl = $_POST['url'] ?? '';
if (!$longUrl || !filter_var($longUrl, FILTER_VALIDATE_URL)) {
  echo json_encode(['error' => 'URL tidak valid']);
  exit;
}

$token = 'c37378ae0f9882a301071ee3f4d9950a1cb80a0e'; // Ganti dengan token Bitly kamu
$payload = json_encode(['long_url' => $longUrl]);

$ch = curl_init("https://api-ssl.bitly.com/v4/shorten");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer $token",
  "Content-Type: application/json"
]);

$result = curl_exec($ch);
$data = json_decode($result, true);

if (isset($data['link'])) {
  $shortUrl = $data['link'];

  // Simpan ke log.csv
  $log = fopen("log.csv", "a");
  fputcsv($log, [$longUrl, $shortUrl, date("Y-m-d H:i:s"), 0]);
  fclose($log);

  echo json_encode(['shortUrl' => $shortUrl]);
} else {
  echo json_encode(['error' => 'Gagal mempersingkat URL']);
}
