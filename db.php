<?php

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = 'excel';
$conn = mysqli_connect($servername, $dbUsername, $dbPassword);
$db_selected = mysqli_select_db($conn, $dbName);

if (!$db_selected) {
  $sql = 'CREATE DATABASE '.$dbName;
  mysqli_query($conn,$sql);
  $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

}

if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
}
