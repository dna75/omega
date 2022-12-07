<?	
include "./../include/config.inc.php";

$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$uniqueId = $_REQUEST['uniqueId'];
$sdate = $_REQUEST['sdate'];
$newDate = date("d-m-Y", strtotime($sdate));



$query = $db->query("SELECT * FROM reservation_times WHERE time = '".$uniqueId."'");
if(mysqli_num_rows($query) == 1)	{
	$remove = $db->query("DELETE FROM reservation_times WHERE time = '".$uniqueId."'");
} else {
 	if (isset($uniqueId) && $uniqueId !='') :
	$sql = $db->query("INSERT INTO reservation_times (time, datum)
	VALUES ('".$uniqueId."', '".$newDate."')");
	endif;
}

?>
