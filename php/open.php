<?php

require_once 'mysql_connect.php';

$query = "SELECT * 
FROM partial INNER JOIN material ON partial.mat_id = material.mat_id
WHERE partial.date > NOW( ) -  '00000001180000'
";
$result = mysql_query($query);
echo mysql_error();
if(mysql_num_rows($result) >0) {
	echo '<h4>Są otwarte poniżej 36 godzin:</h4>
	<table>
		<tr>
			<td class="brand">Marka</td><td class="open_name">Nazwa</td><td>Ilość</td><td>Akcja</td>
		</tr>';
}

while($row = mysql_fetch_array($result)) {
	echo '<tr><td>' .$row['brand'] . '</td><td>' . $row['name'] . '</td><td>' . $row['qty'] . '</td><td>
	<button id="trash_' . $row['pid'] . '" class="trash">Utylizacja</button>
	<button id="eat_' . $row['pid'] . '" class="eat">Zjedzone</button>
</td></tr>';
}
if(mysql_num_rows($result) >0) {
	echo '</table>';
}

$query = "SELECT * 
FROM partial INNER JOIN material ON partial.mat_id = material.mat_id
WHERE partial.date < NOW( ) -  '00000001180000'
AND partial.date > NOW( ) -  '00000002000000'";
$result = mysql_query($query);
echo mysql_error();
if(mysql_num_rows($result) >0) {
	echo '<h4>Mija im termin przydatności:</h4>
	<table>
		<tr>
			<td class="brand">Marka</td><td class="open_name">Nazwa</td><td>Ilość</td><td>Akcja</td>
		</tr>';
}

while($row = mysql_fetch_array($result)) {
	echo '<tr><td>' .$row['brand'] . '</td><td>' . $row['name'] . '</td><td>' . $row['qty'] . '</td><td>
	<button id="trash_' . $row['pid'] . '" class="trash">Utylizacja</button>
	<button id="eat_' . $row['pid'] . '" class="eat">Zjedzone</button></td></tr>';
}
if(mysql_num_rows($result) >0) {
	echo '</table>';
}
$query = "SELECT * 
FROM partial INNER JOIN material ON partial.mat_id = material.mat_id
WHERE partial.date < NOW( ) -  '00000002000000'";
$result = mysql_query($query);
echo mysql_error();
if(mysql_num_rows($result) >0) {
	echo '<h4>Minął im termin przydatności:</h4>
	<table>
		<tr>
			<td class="brand">Marka</td><td class="open_name">Nazwa</td><td>Ilość</td><td>Akcja</td>
		</tr>';
}
while($row = mysql_fetch_array($result)) {
	echo '<tr><td>' .$row['brand'] . '</td><td>' . $row['name'] . '</td><td>' . $row['qty'] . '</td><td>
	<button id="trash_' . $row['pid'] . '" class="trash">Utylizacja</button>
	</td></tr>';
}
if(mysql_num_rows($result) >0) {
	echo '</table>';
}
?>