<?php
$days = $_GET['dayNumber'];

$days = explode(',', $days);

foreach ($days as $day) {
    if ($day == 0) {
        echo "Sunday";
        echo "200";
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
