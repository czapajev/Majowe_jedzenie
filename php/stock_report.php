<?php

require_once 'mysql_connect.php';
$det = array();
$query = "SELECT material.mat_id, brand, name, sum(qty) AS suma, smak, wprowadzone, comment, cat FROM material LEFT JOIN movement ON material.mat_id=movement.mat_id GROUP BY mat_id";
$result = mysql_query($query);
echo mysql_error();
echo '<table>
		<tr>
			<td>Marka</td>
			<td>Nazwa</td>
			<td class="cat_show">Kat.</td>
			<td>Zapas</td>
			<td>Zakupy</td>
			<td>Posiłki</td>
			<td>Smak</td>
			<td>Wprowadzone</td>
			<td>Komentarz</td>
		</tr>';
while($row = mysql_fetch_array($result)) {
	$det = array();
	$q = "SELECT sum(qty) AS detsuma, typ FROM movement WHERE mat_id='{$row['mat_id']}' GROUP BY mat_id, typ";
	$res = mysql_query($q);
	echo mysql_error();
	while($r = mysql_fetch_array($res)) {
		
		$i = $r['typ'];
		$det[$i] = $r['detsuma'];
	}
	if(!isset($det['101'])) {
		$det['101'] = 0;
	}
	if(!isset($det['601'])) {
		$det['601'] = 0;
	}
	if($row['suma'] == NULL) {
		$row['suma'] = 0;
	}
	$q = "SELECT * FROM category WHERE cid='{$row['cat']}'";
	$res = mysql_query($q);
	$r = mysql_fetch_array($res);
	echo '<tr>
			<td>' . $row['brand'] . '</td>
			<td>' . $row['name'] . '</td>
			<td class="cat_show" style="background-color: ' . $r['color'] . '">&nbsp;</td>
			<td>' . $row['suma'] . '</td>
			<td>' . $det['101'] . '</td>
			<td>' . $det['601'] . '</td>
			<td>' . $row['smak'] . '</td>
			<td>' . $row['wprowadzone'] . '</td>
			<td>' . $row['comment'] . '</td>
			</tr>';
}
echo '</table>';

?>
