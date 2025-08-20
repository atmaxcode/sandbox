<?php
$targetShort = $_GET['short'] ?? '';
if (!$targetShort) exit;

$lines = file("log.csv", FILE_IGNORE_NEW_LINES);
$newLines = [];

foreach ($lines as $line) {
  $parts = str_getcsv($line);
  if (count($parts) >= 2 && $parts[1] !== $targetShort) {
    $newLines[] = $line;
  }
}

file_put_contents("log.csv", implode("\n", $newLines));
