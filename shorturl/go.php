<?php
$code = $_GET['c'] ?? '';
$dbFile = "db.json";

if (!file_exists($dbFile)) {
  die("Data tidak tersedia.");
}

$db = json_decode(file_get_contents($dbFile), true);

if (isset($db[$code])) {
  header("Location: " . $db[$code]);
  exit;
} else {
  echo "URL tidak ditemukan.";
}
