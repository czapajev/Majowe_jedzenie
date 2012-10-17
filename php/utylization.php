<?php

require_once 'mysql_connect.php';

$tid = $_POST['tid'];
$action = $_POST['action'];

$query = "SELECT mat_id, qty FROM partial WHERE pid='$tid'";
$result = mysql_query($query);
echo mysql_error();

$temp = mysql_fetch_array($result, MYSQL_BOTH);
$mid = $temp[0];
$qty = $temp[1];
//var_dump($temp);

$query = "INSERT INTO utylizacja (mid, qty, action, date) VALUES ('$mid', '$qty', '$action', NOW())";
$result = mysql_query($query);
echo mysql_error();
if($result) {
	$query2 = "DELETE FROM partial WHERE pid='$tid'";
	$result2 = mysql_query($query2);
	echo mysql_error();
	//echo $tid;
	if($result2) {
		echo 1;
	} else {
		echo 0;
	}
} else {
	echo 0;
	
}


?>