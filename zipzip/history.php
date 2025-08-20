<?php
$rows = [];
if (($file = fopen("log.csv", "r")) !== false) {
  while (($data = fgetcsv($file)) !== false) {
    if (count($data) >= 2) {
      $rows[] = [$data[0], $data[1]];
    }
  }
  fclose($file);
}
echo json_encode(array_reverse($rows));
