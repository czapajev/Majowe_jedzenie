<?php
require_once('mysql_connect.php');
if(isset($_POST['ref'])) {
	$ref= $_POST['ref'];
	if($ref == '#add_meal') {
		$add_head = '<td>Otwarte</td>';//<td>Smak</td><td>Komentarz</td>';
		$add_line = '</tr><tr><td>&nbsp;</td><td colspan="2">Komentarz: <input type="text" class="comment" /></td><td colspan="4">Smak: <input type="text" class="smak" size="6" /></td>';
	} else {
		$add_head = '';
		$add_line = '';
	}
} else {
	$join = "LEFT";
}
$query = "SELECT material.mat_id, material.name, SUM( movement.qty ) AS suma,material.cat, material.brand 
FROM material
LEFT JOIN movement ON material.mat_id = movement.mat_id
GROUP BY mat_id";
$result = mysql_query($query);
echo '<table>
		<tr>
			<td class="cat_show">Kat.</td>
			<td>Marka</td>
			<td>Nazwa</td>
			
			<td>Ilość</td>
			<td>Stan</td>' . $add_head . '
		</tr>';
while($row = mysql_fetch_array($result, MYSQL_BOTH)) {
	if($row['suma'] == NULL) {
		$row['suma'] = 0;
	}
	$q = "SELECT * FROM category WHERE cid='{$row['cat']}'";
	$res = mysql_query($q);
	$r = mysql_fetch_array($res);
	$q2 = "SELECT * FROM partial WHERE mat_id='{$row['mat_id']}' AND date > NOW() - '00000002000000'";
	$res2 = mysql_query($q2);
	$num = mysql_num_rows($res2);
	echo mysql_error();
	if(strlen($add_line) >0) {
		$ins = '<td>' . $num . '</td>';
	} else {
		$ins = '';
	}
	echo '<tr id="' . $row['mat_id'] . '" class="mat_line">
		<td class="cat_show" style="background-color: ' . $r['color'] . '">&nbsp;</td>
		<td>' . $row['brand'] . '</td>
		<td>' . $row['name'] .'</td>
		
		<td><button class="dec">-</button><input type="text" size="2" class="mat_list_line" value="0" /><button class="inc">+</button></td>
		<td>' . $row['suma'] . '</td>' . $ins . $add_line .  '
		</tr>';
}

echo '</table>';
?>