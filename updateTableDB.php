<?php
require 'vendor/autoload.php';
require "db.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$tableName= $_POST['tableName'];
$arr = json_decode($_POST['table']);


$sql = 'TRUNCATE TABLE '.$tableName;
if ($conn->query($sql) != TRUE) {
    echo "Adatbázis hiba";
    exit;
}
if(empty((array)$arr)){
    echo "<h6><b>Sikeres mentés!</b></h6>";
    exit;
}

$sql = 'INSERT INTO '.$tableName.' VALUES ';
foreach($arr as $key => $value){
    $sql.='(NULL, ';
    foreach($value as $k => $v){
        if($v==""){
            $sql.= 'NULL, ';
        }
        else{
            $sql.='"'.addslashes($v).'", ';
            }
    }
    $sql = substr($sql,0,-2);
    $sql.= '),';
}
$sql = substr($sql,0,-1);
if ($conn->query($sql) !=TRUE){
        echo "Adatbázis hiba";
        exit;
}
    
echo "<h6><b>Sikeres mentés!</b></h6>";

?>