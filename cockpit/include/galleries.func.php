<?php

function save_new_gallery($name, $description, $date) {
$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

	$db->query("INSERT INTO		amplo_galleries
									(name, description, date, active)
					VALUES			('$name', '$description', '$date', 1)
									");
	return mysqli_insert_id($db);
}

function update_gallery($id, $name, $description, $date) {
$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

	$db->query("UPDATE				amplo_galleries
					SET				name = '$name',
									description = '$description',
									date = '$date'
					WHERE			id = $id
									");
}

?>
