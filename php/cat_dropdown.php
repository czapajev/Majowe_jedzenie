<?php

require_once 'mysql_connect.php';

$query = "SELECT * FROM category";
$result = mysql_query($query);
echo 'Kategoria:<br><select id="cat">';
while($row = mysql_fetch_array($result)) {
	echo '<option value="' . $row['cid'] . '">' . $row['cat'] . '</option>';
}
echo '</select>';

?>
