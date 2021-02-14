<?php
require 'vendor/autoload.php';
require "db.php";
$tableName= $_POST['i'];
$sql = 'DROP TABLE '.$tableName;
if ($conn->query($sql) != TRUE) {
    echo "Adatbázis hiba";
    exit;
}
else{
    echo "tábla törölve";
}

?>