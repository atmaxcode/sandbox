<?php
$code = trim($_GET['code'] ?? '');
$dbFile = "db.json";

if (!$code || !file_exists($dbFile)) {
  http_response_code(404);
  echo "Kode tidak ditemukan.";
  exit;
}

$db = json_decode(file_get_contents($dbFile), true);

if (isset($db[$code])) {
  header("Location: " . $db[$code]);
  exit;
} else {
  http_response_code(404);
  echo "URL tidak ditemukan.";
}
