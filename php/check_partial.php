<?php
require_once 'mysql_connect.php';

$query = "SELECT * 
FROM partial INNER JOIN material ON partial.mat_id = material.mat_id
WHERE partial.date > NOW( ) -  '00000002000000'";
$result = mysql_query($query);
echo mysql_error();
if(mysql_num_rows($result) >0) {
	echo 'Mija im termin przydatności:<br>';
}
while($row = mysql_fetch_array($result)) {
	echo $row['brand'] . ' ' . $row['name'] . ' ' . $row['qty'] . '<br>';
}
$query = "SELECT * 
FROM partial INNER JOIN material ON partial.mat_id = material.mat_id
WHERE partial.date < NOW( ) -  '00000002000000'";
$result = mysql_query($query);
echo mysql_error();
if(mysql_num_rows($result) >0) {
	echo 'Minął im termin przydatności:<br>';
}
while($row = mysql_fetch_array($result)) {
	echo $row['brand'] . ' ' . $row['name'] . ' ' . $row['qty'] . '<br>';
}

?>