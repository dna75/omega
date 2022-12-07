<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);	
	
include "./../include/config.inc.php";

$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$uniqueId2 = $_REQUEST['uniqueId2'];
$newDate = date("d-m-Y", strtotime($uniqueId2));
$status = $_REQUEST['status'];

$query = $db->query("SELECT * FROM reservation_dates WHERE datum = '".$newDate."'");
if(mysqli_num_rows($query) == 1 && $status == '')	{					
	$remove = $db->query("DELETE FROM reservation_dates WHERE datum = '".$newDate."'");
	$checked = ''; 
} 

if(mysqli_num_rows($query) == 0 && $status == 'checked')	{
$sql = $db->query("INSERT INTO reservation_dates (datum)
VALUES ('".$newDate."')");
$checked = 'checked'; 
}

?>
