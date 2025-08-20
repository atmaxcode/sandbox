<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$url = trim($data["url"] ?? '');
$custom = trim($data["custom"] ?? '');

if (!filter_var($url, FILTER_VALIDATE_URL)) {
  echo json_encode(["error" => "URL tidak valid"]);
  exit;
}

$dbFile = "db.json";
$db = file_exists($dbFile) ? json_decode(file_get_contents($dbFile), true) : [];

// Gunakan custom jika tersedia dan belum dipakai
if ($custom !== '') {
  $code = preg_replace("/[^a-zA-Z0-9\-]/", "", strtolower($custom));
  if (isset($db[$code])) {
    echo json_encode(["error" => "Custom name '$code' sudah digunakan"]);
    exit;
  }
} else {
  // Otomatis generate kode
  do {
    $code = substr(md5($url . time() . rand()), 0, 6);
  } while (isset($db[$code]));
}

$db[$code] = $url;
file_put_contents($dbFile, json_encode($db, JSON_PRETTY_PRINT));

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$domain = $protocol . "://" . $_SERVER['HTTP_HOST'];
$path = rtrim(dirname($_SERVER['PHP_SELF']), "/\\");

$shortUrl = "$domain$path/$code";

echo json_encode(["short" => $shortUrl]);
