<?php
if ($_SERVER['REMOTE_ADDR'] == '82.172.63.44') {
    $reportErrors = 'on';
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Omega Autoverhuur - Leeuwarden</title>

    <!-- Bootstrap -->
    <link href="/css/styles.min.css" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="/css/animate.css" rel="stylesheet" />

    <!-- Google Font Lato -->
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,900,400italic,700italic,900italic" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" href="/resources/demos/style.css" />


    <?
    $items = new CustomPages('algemeen');
    $items->fetchOne(1); // je kan ook een id ophalen met: $items->fetchOne(87);

    // OPTIONAL STUFF
    // $result->filter('gender'        , '!='    , '0');
    // $result->filter('date_to'    , '>='    , date('d-m-Y', strtotime('-12 hours')));
    // $result->sort('date_to', true);
    // $result->random();
    // $result->reverse();
    // $result->limit(100);
    // END OPTIONAL STUFF

    $itemAlgemeen = $items->getResults();
    ?>

</head>

<div class="container">

    <? if (!isset($_SESSION["loggedin"])) {
        include('./pages/users.php');
        echo '<hr>';
    }

    $id = $_GET['id'];
    $query = $db->query("SELECT `main_id`
                        FROM 		`custom_pages`
                        WHERE 		`page`				= 'pages'
                        AND			`field_slug`		= 'menu_title'
                        AND			`text`				= '" . $id . "'
                        LIMIT		1
                        ") or die(mysqli_error($db));
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_array($query)) {
            $result = new CustomPages('pages');
            $result->fetchOne($row['main_id'], $lang);

            $page = $result->getResults();

            if (!empty($page['externepagina'])) {
                include('' . $page['externepagina'] . '');
            }
        }
    }
    ?>

</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/js/jquery-1.11.0.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->

<script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="/js/jquery.placeholder.js"></script>
<!--[if !(gte IE 8)]><!-->
<script src="/js/wow.min.js"></script>
<script>
    // Initialize WOW
    //-------------------------------------------------------------
    new WOW({
        mobile: false
    }).init();
</script>
<!--<![endif]-->

<script src="/js/custom.js"></script>
<script src="/js/jquery.matchHeight.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://kit.fontawesome.com/121652c01a.js" crossorigin="anonymous"></script>


<!-- disabled days datepicker -->
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

    $("#datepicker2, #datepicker, #startTime, #endTime, #cartype").change(function() {
        var cartype = $("#cartype").val();
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
            url: "ajax.php?cartype=" + cartype + "&start=" + start + "&startTime=" + startTime + "&end=" + end + "&endTime=" + endTime + "&days=" + days + "&dayNumber=" + dayNumber + "&price=" + price + "&qty=" + qty + "&totalSum=" + totalSum,
            success: function(result) {
                $("#result").html(result);
            },
        });
    });
</script>

</body>

</html>