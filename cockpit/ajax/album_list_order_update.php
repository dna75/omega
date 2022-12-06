<?php
include('../include/config.inc.php');

$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }


$action 	= mysqli_real_escape_string($db, $_POST['action']);
$albums 	= $_POST['album'];

if ($action == "updateAlbums"){

	echo "Gelukt...";

	$listingCounter = 1;
	foreach ($albums as $id) {

		$db->query("UPDATE			`amplo_galleries` 
						SET 		`order` = $listingCounter
						WHERE		id = $id
									") or die(mysqli_error($db));
		$listingCounter++;
	
		echo "Gelukt";
	}
}

?>
