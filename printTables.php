<?php
require "db.php";
$arr = $_POST;
$json;
$res = array();
foreach ($arr as $val ){
	$sql = "SELECT * FROM ".$val;
	$result = mysqli_query($conn, $sql);

	$rows = array();
	while($r = mysqli_fetch_assoc($result)) {
		unset($r['PK_ID']);
		foreach($r as $key => $value){
			if(is_null($value)){
				$r[$key]='';
			}
		}
		$rows[] = $r;
	}
	$res[$val] = $rows;

	$sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'".$val."'";
	$result = mysqli_query($conn, $sql);
	$res['columns'.$val] = new stdClass();


	$colls = array();
	while($r = mysqli_fetch_assoc($result)) {
		if($r['COLUMN_NAME']!="PK_ID"){
			$colls[]=$r['COLUMN_NAME'];
		}
	}
	$res['columns'.$val]=$colls;
}
echo json_encode($res);
?>
