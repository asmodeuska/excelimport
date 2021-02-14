<?php
header('Content-Type: text/html; charset=UTF-8');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$arr = $_POST['data'];
var_dump($arr);
?>


