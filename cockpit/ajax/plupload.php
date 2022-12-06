<?
include('../include/config.inc.php');

$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

include($develop.'/include/upload.class.php');

$album_id = mysqli_real_escape_string($db, $_POST['album_id']);

$album = $db->query("select 			*
FROM 			amplo_galleries
WHERE 			id = $album_id
LIMIT			1
");
$album = mysqli_fetch_array($album);

$destpath = "../../upload/gallery/" . date("Ymdhis" , strtotime($album['directory'])) . "/";

if(!empty($_FILES["file"]["name"])) {
    
    $file = array(	'name' 		=> $_REQUEST['name'],
    'tmp_name' 	=> $_FILES['file']['tmp_name'],
    'size' 		=> $_FILES['file']['size'],
    'error' 	=> $_FILES['file']['error'],
    'type' 		=> $_FILES['file']['type']
);
$upload = new Upload($destpath, $file);
$upload->resize(1600,1600);

if (!isset($customWidth)) {
    $upload->thumb(481,481);      
} else {
    $upload->thumb($customWidth,$customHeight);
}

$upload->save_image();

$filename = $upload->filename;

$db->query("INSERT INTO	amplo_images
    (album_id, filename)
    VALUES		($album_id, '$filename')
    ");
}

?>
