<?php
if ($_SERVER['REMOTE_ADDR'] == '82.172.63.44') {
	$reportErrors = 'on';
} else {
	$reportErrors = 'on';
}

ini_set('display_errors', $reportErrors);
error_reporting(E_ALL);

ob_start();
include "./cockpit/include/config.inc.php";
//include "./functions/func.inc.php";

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

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Reken demo</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="/resources/demos/style.css" />
</head>

<body style="padding:20px;">
	<h1 class="text-center">OMEGA AUTOVERHUUR</h1>

	<p>Er wordt gerekend met onderstande prijzen:<br>
		€ 50,- per dag<br>
		€ 20,- per week<br>
		€ 10,- per maand</p>
	€ 40,- per weekenddag (zaterdag en zondag)<br>
	Alle prijzen zijn de prijzen per dag.</p>

	<? // times
	$start = '09:00';
	$end = '18:00';
	?>

	<?
	function dropdown($start, $end, $step = 15, $format = 'H:i')
	{
		$output = '';
		$times = array();
		$startTime = strtotime($start);
		$endTime = strtotime($end);
		$currentTime = $startTime;
		while ($currentTime <= $endTime) {
			$times[] = date($format, $currentTime);
			$currentTime += $step * 60;
		}
		foreach ($times as $time) {
			$output .= '<option value="' . $time . '">' . $time . '</option>';
		}
		return $output;
	}
	?>


	<!-- #region main -->
	<form action="" id="form">

		<p>Start: <input type="text" name="startShow" id="datepicker" /></p>
		<input type="hidden" name="start" id="alt-date" />
		<label>Start tijd:</label>
		<select name="startTime" id="startTime">
			<?php echo dropdown($start, $end); ?>
		</select>

		<br><br>
		<p>Einde: <input type="text" name="endShow" id="datepicker2" /></p>
		<input type="hidden" name="end" id="alt-date2" />

		<label>Eind tijd:</label>
		<select name="endTime" id="endTime">
			<?php echo dropdown($start, $end); ?>
		</select>

		<input type="hidden" name="days" id="days" />
		<input type="hidden" name="dayNumber" id="dayNumber" />
	</form>
	<!-- #endregion main -->

	<input type="hidden" id="totalSum" value="100" onchange="updateTotal()" />

	<div id="hidden">Total</div>

	<div id="result" style="border: 3px solid red; padding:19px;">RESULT</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


	// disabled days datepicker
	<script>
		// Check disabled times
		$("#datepicker").bind("change keyup", function() {
			let d = new Date(document.getElementById("alt-date").value),
				month = '' + (d.getMonth() + 1),
				day = '' + d.getDate(),
				year = d.getFullYear();
			date = [year, month, day].join('-');

			if (date == "2022-11-30") { // Check if times are disabled for this date
				// array disabled times
				let times = ["09:00", "09:15"];
				$("#startTime option").each(function() {
					if ($.inArray($(this).val(), times) != -1) {
						$(this).remove();
					}
				});
			}
			if (date != "2022-11-30") { // If times are not disabled for this date add times back to dropdown
				$("#startTime").html('<?php echo dropdown($start, $end); ?>');
			}
		});

		// datepicker disable specific dates
		var disabledDays = ["2022-11-28", "2022-11-29"];

		// pass disabled dates array to datepicker
		function disabled(date) {
			var string = jQuery.datepicker.formatDate("yy-mm-dd", date);
			return [disabledDays.indexOf(string) == -1];
		}

		// Datepicker Start / End date
		$(function() {
			$("#datepicker").datepicker({
				closeText: "Sluiten",
				prevText: "Vorig",
				nextText: "Volgende",
				currentText: "Vandaag",
				monthNames: ["januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december"],
				monthNamesShort: ["jan", "feb", "mrt", "apr", "mei", "jun", "jul", "aug", "sep", "okt", "nov", "dec"],
				dayNames: ["zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag"],
				dayNamesShort: ["zon", "maa", "din", "woe", "don", "vri", "zat"],
				dayNamesMin: ["zo", "ma", "di", "wo", "do", "vr", "za"],
				weekHeader: "Wk",
				dateFormat: "dd-mm-yy",
				altFormat: "yy-mm-dd",
				altField: "#alt-date",
				firstDay: 1,
				changeMonth: true,
				changeYear: true,
				minDate: +2,
				beforeShowDay: disabled,
			});
		});

		// datepicker 2 
		$("#datepicker").bind("change keyup", function() {
			$("#datepicker2").datepicker("option", "minDate", $(this).val()); // reset minDate after changing Start date
			var minDate = ''; // create var
			var minDate = $("#datepicker").val(); // get value from datepicker (start date)

			var curEndDate = $("#datepicker2").val(); // get value from datepicker (end date)

			if (curEndDate <= minDate && curEndDate != '') { // if end date is smaller than start date clear end date
				document.getElementById('datepicker2').value = ''
			}

			// datepicker end date
			$("#datepicker2").datepicker({
				closeText: "Sluiten",
				prevText: "Vorig",
				nextText: "Volgende",
				currentText: "Vandaag",
				monthNames: ["januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december"],
				monthNamesShort: ["jan", "feb", "mrt", "apr", "mei", "jun", "jul", "aug", "sep", "okt", "nov", "dec"],
				dayNames: ["zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag"],
				dayNamesShort: ["zon", "maa", "din", "woe", "don", "vri", "zat"],
				dayNamesMin: ["zo", "ma", "di", "wo", "do", "vr", "za"],
				weekHeader: "Wk",
				dateFormat: "dd-mm-yy",
				altFormat: "yy-mm-dd",
				altField: "#alt-date2",
				firstDay: 1,
				changeMonth: true,
				changeYear: true,
				minDate: minDate,
			});
		});

		// get all dates in between start and end date and put them in an array
		$("#datepicker2, #datepicker").bind("change keyup", function() {
			let date1 = new Date(document.getElementById("alt-date").value);
			let date2 = new Date(document.getElementById("alt-date2").value);

			const diffTime = Math.abs(date2 - date1);
			const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
			document.getElementById("days").value = diffDays + "dagen";
			// display each date between start and end date
			const dates = []; // all dates between start and end date in an array
			for (let i = 0; i <= diffDays; i++) {
				let newDate = new Date(date1);
				newDate.setDate(newDate.getDate() + i);

				// Day Name
				var days = ["zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag"];
				var d = new Date(newDate);
				var dayName = days[d.getDay()];

				// Day number
				let dayNumber = newDate.getDay();
				let day = newDate.getDate();
				let month = newDate.getMonth() + 1;
				let year = newDate.getFullYear();
				let formattedDate = dayNumber + "-" + dayName + "-" + day + "-" + month + "-" + year;
				// format date Day Month Year

				dates.push(formattedDate);
			}

			document.getElementById("dayNumber").value = dates;
		});

		$("#datepicker2, #startTime, #endTime").change(function() {

			var start = $("#datepicker").val();
			var startTime = $("#startTime").val();
			var end = $("#datepicker2").val();
			var endTime = $("#endTime").val();
			var days = $("#days").val();
			var dayNumber = $("#dayNumber").val();
			var price = $("#price").val();
			var qty = $("#qty").val();
			var totalSum = $("#totalSum").val();
			$.ajax({
				type: "POST",
				url: "ajax.php?start=" + start + "&startTime=" + startTime + "&end=" + end + "&endTime=" + endTime + "&days=" + days + "&dayNumber=" + dayNumber + "&price=" + price + "&qty=" + qty + "&totalSum=" + totalSum,
				success: function(result) {
					$("#result").html(result);
				},
			});
		});
	</script>
</body>

</html>