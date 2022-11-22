<?php

$dayPrice = 50;
$weekPrice = 20;
$mothPrice = 10;
$weekendPrice = 40;

$days = $_GET['dayNumber'];

$days = explode(',', $days);
foreach ($days as $day) {
    $daynumber = substr($day, 0, 1);
    $dayNumbers[] = $daynumber;
}

// difference start and end date in minutes
$datetime1 = new DateTime($_GET['start'] . ' ' . $_GET['startTime']);
$datetime2 = new DateTime($_GET['end'] . ' ' . $_GET['endTime']);

// time difference in minutes
$interval = $datetime1->diff($datetime2);
$minutes = $interval->format('%i');
$hours = $interval->format('%h');
$rentaldays = $interval->format('%d');
$rentalHours = $hours + ($rentaldays * 24);
$minutes = $minutes + ($rentalHours * 60);
$totalRentalDays = ceil($minutes / 60 / 24);

// trim array show only 2 items
$dayNumbers = array_slice($dayNumbers, 0, $totalRentalDays);

$daycount = count($days);

// print_r($dayNumbers);

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
if ($daycount >= 1 && $daycount <= 6) {

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
if ($daycount >= 7 && $daycount <= 29) {
    $total = $totalRentalDays * $weekPrice;
    echo 'Weekprijs: ' . $total;
}
if ($daycount >= 30) {
    $total = $totalRentalDays * $mothPrice;
    echo 'Maandprijs: ' . $total;
}

?>