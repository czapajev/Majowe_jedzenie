<?php

require_once 'mysql_connect.php';
if(isset($_POST['cat'])) {
	$query = "INSERT INTO category (cat, color) VALUES ('{$_POST['cat']}', '{$_POST['color']}')";
	$result = mysql_query($query);
	echo mysql_error();
}
if(isset($_POST['delete'])) {
	$query = "DELETE FROM category WHERE cid='{$_POST['cid']}'";
	$result = mysql_query($query);
	$query = "UPDATE material SET cat='0' WHERE cat='{$_POST['cid']}'";
	$result = mysql_query($query);
	$query = "SELECT * FROM category WHERE cid='{$_POST['cid']}'";
	$result = mysql_query($query);
	echo mysql_num_rows($result);
	$query = "SELECT * FROM material WHERE cat='{$_POST['cid']}'";
	$result = mysql_query($query);
	//echo mysql_error();
	echo mysql_num_rows($result);
} else {
	if(isset($_POST['cid'])) {
		$query = "UPDATE category SET ss='{$_POST['ss']}', color='{$_POST['color']}' WHERE cid='{$_POST['cid']}'";
		$result = mysql_query($query);
		echo mysql_error();
		echo 1; 
	} else {
		$query = "SELECT * FROM category";
		$result = mysql_query($query);
		echo '<table>
				<tr>
					<td>Nazwa</td>
					<td>Kolor</td>
					<td>Poziom zapasów</td>
					<td>Zapisz zmiany</td>
					<td>Usuń</td>
				</tr>';
		while($row = mysql_fetch_array($result)) {
			echo '<tr id="' . $row['cid'] . '">
					<td>' . $row['cat'] . '</td>
					<td><input type="text" size="6" value="' . $row['color'] . '" style="background-color:' . $row['color'] . '" class="color" /></td>
					<td><input type="text" size="4" value="' . $row['ss'] . '" class="level" /></td>
					<td><button class="change_ss">Zmień</button></td>
					<td><button class="del_cat">Usuń</button></td>
				</tr>';
		}
	}
}
?>