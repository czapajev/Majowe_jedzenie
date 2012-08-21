<?php
require_once('mysql_connect.php');
$brand = $_POST['brand'];
$name = $_POST['name'];
$skladniki = $_POST['sk'];
if(isset($_POST['now'])) {
	$now = $_POST['now'];
	$smak = "nowość";
} else {
	$smak = '';
}
$cat = $_POST['cat'];
$query = "INSERT INTO material (brand, name, smak, wprowadzone, cat) VALUES ('$brand', '$name', '$smak', 'nie', '$cat')";
$result = mysql_query($query);
 echo mysql_error();
$mid = mysql_insert_id();

foreach($skladniki as $key => $value) {
	$sk = $value;
	if(isset($now[$key])) {
		$stat = "nowosc";
	} else {
		$stat = 0;
	}
	
	$query = "SELECT * FROM skladniki WHERE nazwa='$sk'";
	$result = mysql_query($query);
	echo mysql_error();
	if(mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result, MYSQL_BOTH);
		$sid = $row['sid'];
	} else {
		$query = "INSERT INTO skladniki (nazwa, status) VALUES ('$sk', '$stat')";
		$result = mysql_query($query);
		echo mysql_error();
		$sid = mysql_insert_id();
	}
	$query = "INSERT INTO skladnik_join (mat_id, sid) VALUES ('$mid', '$sid')";
	$result = mysql_query($query);
	echo mysql_error();
	$sj = mysql_insert_id();
}

if($mid && $sid && $sj) {
	echo 1;
} else {
	echo 0;
}


?>