<?php
header('Content-Type: text/html; charset=UTF-8');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//print_r($_FILES['file']['tmp_name']);
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $_FILES['file']['tmp_name']);
$locale = 'hu';
$validLocale = \PhpOffice\PhpSpreadsheet\Settings::setLocale($locale);
$res= array();
$i=0;
while ($i<$spreadsheet->getSheetCount()){
    $spreadsheet->setActiveSheetIndex($i);
    $worksheet = $spreadsheet->getActiveSheet();
    $ws = $worksheet->toArray();
    $name = $spreadsheet->getSheetNames()[$i];
    $res[$i] = new \stdClass();
    $res[$i]->$name = $ws;
    $i++;
}
echo json_encode($res);
?>


