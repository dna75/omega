<?
require_once($develop.'/include/CustomPages/CustomPages.class.php');

$result = new CustomPages('admindetails');
$result->fetch();

$count = count($result->getResults());

if ($count == 1 ) {
	foreach ($result->getResults() as $row) {
		if ($row['analytics'] && $row['analytics'] !='0') :
			$analytics = $row['analytics'];
		endif;
	}

}

$function="sin(x)";
$var="x";
$urlga='https://develop.spinnerz.nl/oocharts/oocharts.php';
echo '<base href="' . $domein . '">'; // Fixes broken relative links
$Curl_Session = curl_init($urlga);
curl_setopt ($Curl_Session, CURLOPT_POST, 1);
curl_setopt ($Curl_Session, CURLOPT_POSTFIELDS, "analytics=$analytics");
curl_setopt ($Curl_Session, CURLOPT_FOLLOWLOCATION, 0);
$content=curl_exec($Curl_Session);
curl_close ($Curl_Session);


?>
