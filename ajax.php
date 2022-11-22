<?php

$dagPrijs = 25;
$weekPrijs = 100;
$maandPrijs = 300;

$days = $_GET['dayNumber'];
$days = explode(',', $days);
$daycount = count($days);

foreach ($days as $day) {
    if ($day == 0) {
        echo "Sunday";
    } elseif ($day == 1) {
        echo "Monday";
    } elseif ($day == 2) {
        echo "Tuesday";
    } elseif ($day == 3) {
        echo "Wednesday";
    } elseif ($day == 4) {
        echo "Thursday";
    } elseif ($day == 5) {
        echo "Friday";
    } elseif ($day == 6) {
        echo "Saturday";
    }
}


echo $daycount;
echo '<br>';
echo $_GET['start'];
echo '<br>';
echo $_GET['end'];
echo '<br>';

// difference start and end date in minutes
$datetime1 = new DateTime($_GET['start'] . ' 10:00:00');
$datetime2 = new DateTime($_GET['end'] . ' 14:00:00');

// time difference in minutes
$interval = $datetime1->diff($datetime2);
$minutes = $interval->format('%i');
$hours = $interval->format('%h');
$days = $interval->format('%d');
$hours = $hours + ($days * 24);
$minutes = $minutes + ($hours * 60);
$days = $days + ($hours / 24);

echo 'Aantal uren: ' . $hours;
echo '<br>';
echo 'Aantal dagen: ' . ceil($days);
echo '<br>';
?>
<h3 style="margin-top:30px;">Prijzen</h3>
<?php
if ($days == 1 && $days < 7) {
    echo 'Dagprijs: ' . $dagPrijs * $days;
}
if ($days >= 7 && $days < 30) {
    echo 'Weekprijs: ' . $weekPrijs * $days;
}
if ($days >= 30) {
    echo 'Maandprijs: ' . $maandPrijs * $days;
}
