<?php
if ($_SERVER['REMOTE_ADDR'] == '81.207.233.155') {
    $reportErrors = 'off';
} else {
    $reportErrors = 'off';
}

ini_set('display_errors', $reportErrors);
error_reporting(E_ALL);

ob_start();
include "./cockpit/include/config.inc.php";
//include "./functions/func.inc.php";
include "./functions/corefunc.inc.php";

$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

// Check connection
if ($db->connect_errno) {
    die('Sorry we have some database problems');
}

include($develop . '/include/language.class.php');

$lang = (isset($_GET['lang'])) ? $_GET['lang'] : '';
$language = new Language($lang);
$language->multilingual_site = false;

require_once($develop . '/include/CustomPages/CustomPages.class.php');

setlocale(
    LC_ALL,
    'Dutch_Netherlands',
    'Dutch',
    'nl_NL',
    'nl',
    'nl_NL.ISO8859-1',
    'nl_NL.UTF-8',
    'nld_nld',
    'nld',
    'nld_NLD',
    'NL_nl'
);
?>

<?php

$cartype = db_escape($_GET['cartype']);
$query = $db->query("SELECT * FROM `cars` WHERE `name` = '$cartype'") or die($db->error);
$row = mysqli_fetch_array($query);

$dayPrice     = intval($row['priceaday']);
$weekPrice    = intval($row['priceaweek']);
$monthPrice   = intval($row['priceamonth']);
$weekendPrice = intval($row['priceweekendday']);

$days = $_GET['dayNumber'];

$days = explode(',', $days);
foreach ($days as $day) {
    $daynumber = substr($day, 0, 1);
    $dayNumbers[] = $daynumber;
}

// difference start and end date in minutes
$datetime1 = $_GET['start'] . ' ' . $_GET['startTime'];
$datetime2 = $_GET['end'] . ' ' . $_GET['endTime'];
// time difference in minutes

if (!isset($_GET['end']) || $_GET['end'] == '') {
    $datetime2 = $_GET['start'] . ' ' . $_GET['startTime'];
}

$start_datetime = new DateTime($datetime1);
$diff = $start_datetime->diff(new DateTime($datetime2));
$total_minutes = ($diff->days * 24 * 60);
$total_minutes += ($diff->h * 60);
$total_minutes += $diff->i;
$totalRentalDays = ceil($total_minutes / 1440);

// trim array show only 2 items
$dayNumbers = array_slice($dayNumbers, 0, $totalRentalDays);

// Cout the number of days
$daycount = count($days);


// select saterday and sunday from array
$weekend = array_intersect($days, array(0, 6));
// subtract weekend from days
$daysNoWeekend = array_diff($days, $weekend);

// echo '<br>';
// echo $_GET['start'];
// echo '<br>';
// echo $_GET['end'];
// echo '<br>';
// echo $totalRentalDays;
// echo '<br>';

// echo 'Aantal dagen: ' . $totalRentalDays;
// echo '<br>';
?>
<?php

if ($totalRentalDays >= 1) {
    echo ' € ';
}

if ($totalRentalDays == 0) {
}

if ($totalRentalDays >= 1 && $totalRentalDays <= 6) {

    // if array contains days 0 and 6
    if (in_array(0, $dayNumbers) && in_array(6, $dayNumbers)) {

        $weekend = array_intersect($dayNumbers, array(0, 6));
        $totalPriceWeekend = count($weekend) * $weekendPrice;
        // print_r($weekend);

        $daysNoWeekend = array_diff($dayNumbers, $weekend);
        $totalPriceDays = count($daysNoWeekend) * $dayPrice;

        $total = $totalPriceWeekend + $totalPriceDays;
        echo number_format($total, 2, ',', '');
    } else {
        $total = $totalRentalDays * $dayPrice;
        echo number_format($total, 2, ',', '');
    }
}
if ($totalRentalDays >= 7 && $totalRentalDays <= 29) {
    $total = $totalRentalDays * $weekPrice;
    echo number_format($total, 2, ',', '');
}
if ($totalRentalDays >= 30) {
    $total = $totalRentalDays * $monthPrice;
    echo number_format($total, 2, ',', '') . ' (' . $totalRentalDays . ')';;
}

if ($totalRentalDays == 1) {
    $days = 'dag';
} else {
    $days = 'dagen';
}

if ($totalRentalDays >= 1) {
    echo  ' (' . $totalRentalDays . ' ' . $days . ')';
    echo '<div class="d-grid mt-2"><a href="#" class="btn btn-lg btn-success rounded-0">RESERVEER NU</a></div>';
}
?>