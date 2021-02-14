<?php
require "db.php";

$sql = "SELECT TABLE_NAME 
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='excel'";
$result = mysqli_query($conn, $sql);
$i=0;
$arr;
while ($row = mysqli_fetch_array($result)) {
	$arr[$i]= '
	<tr id="tr_'.$row["TABLE_NAME"].'">
        <td class="text-center align-middle">
			'.$row["TABLE_NAME"].'
		</td>
        <td class="text-center align-middle">
			<input class="isOneChecked" type="checkbox" value="'.$row["TABLE_NAME"].'" name="'.$row["TABLE_NAME"].'">
		</td>
    </tr>
	';
	$i++;
}
	if($i>0){
		echo json_encode($arr);
	}
?>