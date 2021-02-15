<?php
require 'vendor/autoload.php';
require "db.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$sheetNumber = $_POST['sheetNumber'];
$tableName= $_POST['tableName'];
$arr = json_decode($_POST['arr']);
//var_dump($_POST);


$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['file']['tmp_name']);
$locale = 'hu';
$validLocale = \PhpOffice\PhpSpreadsheet\Settings::setLocale($locale);
$spreadsheet->setActiveSheetIndex($sheetNumber);
$worksheet = $spreadsheet->getActiveSheet();
$colRow = $worksheet->getHighestRowAndColumn();
$colls;
$sql = 'CREATE TABLE '.$tableName.' (PK_ID INT AUTO_INCREMENT PRIMARY KEY,';
for ($i = 0; $i< \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($colRow["column"]); $i++){
         $sql.= " `".$worksheet->getCellByColumnAndRow($i+1,1)->getValue()."` ";
         $colls[$i]= $worksheet->getCellByColumnAndRow($i+1,1)->getValue();
         $sql.= " ".$arr[$i];
         if($arr[$i]=='DATE'){
            $sql.=" NULL,";
         }
         else if($arr[$i] =='FLOAT'){
              $sql.="(20,2) NULL,";
         }
         else{
              $sql.="(200) NULL,";
         }
}
$sql = substr($sql,0,-1);
$sql.=" )";
//echo $sql;
if ($conn->query($sql) != TRUE) {
  echo "Tablakeszites hiba: " . $conn->error;
  exit;
}
$sql = 'ALTER TABLE '.$tableName.' AUTO_INCREMENT = 0';
if ($conn->query($sql) != TRUE) {
  echo "Tablakeszites hiba: " . $conn->error;
  exit;
}

$i=0;
foreach ($worksheet->getRowIterator() as $row) {
if($i!=0){
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE); 
    $j=0;
    foreach ($cellIterator as $cell) {  
        $value = $cell->getCalculatedValue();
        $arr[$j] = $value;
        $j++;
    }
    $sql= "INSERT INTO `".$tableName."`(`";
    foreach($colls as $c){
        $sql.=$c."`, `";
    }
    $sql=substr($sql,0,-3);
    $sql.=") VALUES ('";
    foreach($arr as $value){
        if($value != ''){
            $sql.=addslashes($value)."', '";
        }
        else{
            $sql = substr($sql,0,-1);
            $sql.="NULL, '";
        }
    }
    $sql=substr($sql,0,-3);
    $sql.=")";
    //echo $sql;
    if ($conn->query($sql) !=TRUE){
      echo "Hiba: " . $conn->error;
      exit;
    }
}
    $i++;
}
echo "Sikeres importalas..";
//print_r($arr);


?>


