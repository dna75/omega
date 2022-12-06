<?php

$dayPrice = 50;
$weekPrice = 20;
$monthPrice = 10;
$weekendPrice = 40;

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

echo '<br>';
echo $_GET['start'];
echo '<br>';
echo $_GET['end'];
echo '<br>';


echo 'Aantal dagen: ' . $totalRentalDays;
echo '<br>';
?>
<h3 style="margin-top:30px;">Prijzen</h3>
<?php
if ($totalRentalDays >= 1 && $totalRentalDays <= 6) {

    // if array contains days 0 and 6
    if (in_array(0, $dayNumbers) && in_array(6, $dayNumbers)) {

        $weekend = array_intersect($dayNumbers, array(0, 6));
        $totalPriceWeekend = count($weekend) * $weekendPrice;
        // print_r($weekend);

        $daysNoWeekend = array_diff($dayNumbers, $weekend);
        $totalPriceDays = count($daysNoWeekend) * $dayPrice;

        $total = $totalPriceWeekend + $totalPriceDays;
        echo $total;
    } else {
        $total = $totalRentalDays * $dayPrice;
        echo 'Totaal: ' . $total;
    }
}
if ($totalRentalDays >= 7 && $totalRentalDays <= 29) {
    $total = $totalRentalDays * $weekPrice;
    echo 'Weekprijs: ' . $total;
}
if ($totalRentalDays >= 30) {
    $total = $totalRentalDays * $monthPrice;
    echo 'Maandprijs: ' . $total;
}

?>