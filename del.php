<?
$weekendPrice = 10;
$dayPrice = 20;
$a = '6-zaterdag-26-11-2022,7-zondag-27-11-2022';
$b = explode(',', $a);
// get first character of each array item
foreach ($b as $c) {
    $d = substr($c, 0, 1);
    $e[] = $d;
}
print_r($e);

$weekend = array_intersect($b, array(7, 6));
$count = count($weekend);

foreach ($weekend as $weekendDay) {
    $weekendPrice += $count * $weekendPrice;
}


// remove weekend from array
$daysNoWeekend = array_diff($b, $weekend);
$count = count($daysNoWeekend);
foreach ($daysNoWeekend as $day) {
    // $dayNumbers += $count * $dayPrice;
}
print_r($weekend);
echo '<br>';
print_r($daysNoWeekend);
// $total =  +$dayNumbers;
// echo $total;
