<?php
require_once('mysql_connect.php');
$mov = $_POST['mov'];
$mat = $_POST['mat'];
$qty = $_POST['qty'];
if(isset($_POST['smak'])) {
	$smak = $_POST['smak'];
} else {
	$smak = false;
}
if(isset($_POST['comment'])) {
	$comment = $_POST['comment'];
} else {
	$comment = false;
}
$error = '';
if($mov == '601') {
	foreach($qty as $key => $value) {
		if(strpos($qty[$key],".")) {
			$temp = explode('.',$qty[$key]);
			$decimal = '0.' . $temp[1];
			$query = "INSERT INTO partial (mat_id, qty, date) VALUES ('{$mat[$key]}', '$decimal', NOW())";
			$result = mysql_query($query);
			$value = $temp[0]+1;
		}
		$qty[$key] = $value*-1;
		$query = "SELECT SUM(qty) FROM movement WHERE mat_id = '{$mat[$key]}'";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		if($row[0]+$qty[$key]<0) {
			$query = "SELECT * FROM material WHERE mat_id= '{$mat[$key]}'";
			$result = mysql_query($query);
			$row2 = mysql_fetch_array($result);
			$error .= '<div>Za mało produktu: ' . $row2['name'] . '</div>';
		} else {
			$query = "SELECT * FROM material WHERE mat_id = '{$mat[$key]}'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result);
			if($row['smak'] == "nowość") {
				if($smak[$key]) {
					$sm = $smak[$key];
				} else {
					$sm = '';
				}
				$query = "UPDATE material SET smak='$sm' WHERE mat_id = '{$mat[$key]}'";
				mysql_query($query);
				$query = "UPDATE skladniki INNER JOIN skladnik_join ON skladniki.sid=skladnik_join.sid SET status='0' WHERE mat_id='{$mat[$key]}'";
				mysql_query($query);
			} else {
				if($smak[$key]) {
					$sm = $smak[$key];
					$query = "UPDATE material SET smak='$sm' WHERE mat_id = '{$mat[$key]}'";
					mysql_query($query);
					echo mysql_error();
					//echo 2;
				}
			}
			if($row['wprowadzone'] == "nie") {
				$query = "UPDATE material SET wprowadzone='tak' WHERE mat_id = '{$mat[$key]}'";
				mysql_query($query);
			}
			if($comment[$key]) {
				$query = "SELECT comment FROM material WHERE mat_id = '{$mat[$key]}'";
				$result = mysql_query($query);
				$row = mysql_fetch_array($result);
				$comm = '';
				if($row['comment']) {
					$comm = $row['comment'] . ", ";
				}
				$comment[$key] = $comm . $comment[$key];
				$query = "UPDATE material SET comment='{$comment[$key]}' WHERE mat_id = '{$mat[$key]}'";
				mysql_query($query);
				//echo 3;
			}
			
		}
	}
}
if(!$error) {
	foreach($mat as $key => $value) {
		$query = "INSERT INTO movement (mat_id, typ, qty, date) VALUES ('$value','$mov','{$qty[$key]}', NOW())";
		$result = mysql_query($query);
		$id = mysql_insert_id();
		//echo $id;
	}
	if(isset($id)) {
		echo 1;
	} else {
		echo 0;
	}
} else {
	echo $error;
}

?>