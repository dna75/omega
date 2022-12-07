<!-- Extra code for the index page external code Javascript / PHP - Java script or other info! -->
<!-- Extra code for the index page - Java script or other info -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<!--
    <?
    $datum = array();
    $nietbeschikbaar = $db->query('SELECT DISTINCT datum FROM reserverenbeschikbaaar') or die(mysqli_error($db));
    while ($row2 = mysqli_fetch_array($nietbeschikbaar)) {

        $datum[] = $row2['datum'];
    }

    $datum = implode("','", $datum);
    ?>

    <script type="text/javascript" src="scripts/jquery-ui.multidatespicker.js"></script>
-->

<? // Get unavailable Dates from the reservations page
$datum = array();
$nietbeschikbaar = $db->query('SELECT DISTINCT `datum` FROM reservation_dates') or die(mysqli_error($db));
while ($row2 = mysqli_fetch_array($nietbeschikbaar)) {

    $datum[] = $row2['datum'];
}

$datum = implode("','", $datum);
?>

<? // Get unavailable Times from the reservations page
$datumTimes = array();
$nietbeschikbaarTimes = $db->query('SELECT DISTINCT `datum` FROM reservation_times') or die(mysqli_error($db));
while ($row2 = mysqli_fetch_array($nietbeschikbaarTimes)) {

    $datumTimes[] = $row2['datum'];
}

$datumTimes = implode("','", $datumTimes);
?>

<!-- CSS for colored dates -->
<style>
    .yellow a.ui-state-default {
        background-color: #ffdd76;
        background-image: none;
    }

    .red a.ui-state-default {
        background-color: #ff3e3e;
        background-image: none;
    }

    .green a.ui-state-default {
        background-color: green;
        background-image: none;
    }
</style>

<script>
    // Color scheme - Get the arrays for the colored dates
    var yellowDates = ['<? echo $datumTimes; ?>']; // Get array blocked times
    var greenDates = []; // Empty color for laters use
    var redDates = ['<? echo $datum; ?>']; // Get array blocked dates
    function colorize(date) {
        mdy = date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();

        mdy = ("0" + date.getDate()).slice(-2) + '-' + ("0" + (date.getMonth() + 1)).slice(-2) + '-' + date.getFullYear();

        console.log(mdy);
        if ($.inArray(mdy, redDates) > -1) {
            return [true, "red"];
        } else if ($.inArray(mdy, greenDates) > -1) {
            return [true, "green"];
        } else if ($.inArray(mdy, yellowDates) > -1) {
            return [true, "yellow"];
        } else {
            return [true, ""];
        }
    }

    // DE AJAX FUNCTIE DIE DE DATA NAAR DE SERVER VERSTUURD
    function sendDateAjax(dateText) {

        $.ajax({
            url: '/cockpit/reserverenbeschikbaarheid.php', // De link naar het php bestand
            type: "POST", // Via de $_POST techniek wordt de data verstuurd
            data: {
                datum: dateText
            }, // De variabele dateText word onder de $_POST variabele datum meegestuurd naar de server
            success: function(data, textStatus, jqXHR) { // Wanneer de server succesvol bereikt wordt wordt de success functie opgeroepen
                // Een alert met een succes melding. De data variabele geeft alle info weer die het php script weergeeft, dus foutmeldingen van bijv: sql en alle echo's.
                //                             alert('De datum is aangepast, de server geeft de text: ' + data);
            },
            error: function(jqXHR, textStatus, errorThrown) { // Wanneer de server niet bereikt wordt geeft hij de error weer.
                //                             alert('Er ging iets fout, namelijk: ' + errorThrown);
            }
        });
    }

    // Datepicker on overview page -> Show main 3 months for the reservations
    $("#datepicker2").datepicker({
        'dateFormat': 'dd-mm-yy',
        numberOfMonths: 3,
        showButtonPanel: false,
        currentText: 'Vandaag',
        monthNames: ['januari', 'februari', 'maart', 'april', 'mei', 'juni',
            'juli', 'augustus', 'september', 'oktober', 'november', 'december'
        ],
        monthNamesShort: ['jan', 'feb', 'maa', 'apr', 'mei', 'jun',
            'jul', 'aug', 'sep', 'okt', 'nov', 'dec'
        ],
        dayNames: ['zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag'],
        dayNamesShort: ['zon', 'maa', 'din', 'woe', 'don', 'vri', 'zat'],
        dayNamesMin: ['zo', 'ma', 'di', 'wo', 'do', 'vr', 'za'],
        weekHeader: 'Wk',
        onSelect: function() {
            window.open("index.php?page=reserveren" + '&date=' + this.value, '_self');
        },
        beforeShowDay: colorize // Colors of the dates - Blocked or partially blocked
    });

    // Get Variable like date from URL - Use in Datepicker
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };

    // Datepicker for the selected month / date
    $("#datepickermobile").datepicker({
        'dateFormat': 'dd-mm-yy',
        defaultDate: getUrlParameter('date'), // Get the current date
        selectOtherMonths: true,
        numberOfMonths: 1,
        showButtonPanel: false,
        currentText: 'Vandaag',
        monthNames: ['januari', 'februari', 'maart', 'april', 'mei', 'juni',
            'juli', 'augustus', 'september', 'oktober', 'november', 'december'
        ],
        monthNamesShort: ['jan', 'feb', 'maa', 'apr', 'mei', 'jun',
            'jul', 'aug', 'sep', 'okt', 'nov', 'dec'
        ],
        dayNames: ['zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag'],
        dayNamesShort: ['zon', 'maa', 'din', 'woe', 'don', 'vri', 'zat'],
        dayNamesMin: ['zo', 'ma', 'di', 'wo', 'do', 'vr', 'za'],
        weekHeader: 'Wk',
        onSelect: function() {
            window.open("index.php?page=reserveren" + '&date=' + this.value, '_self');
        },
        beforeShowDay: colorize // Colors of the dates - Blocked or partially blocked
    });


    /*
        // 3 months reservations
        $( "#datepicker2" ).datepicker({
            'dateFormat' : 'dd-mm-yy',
            numberOfMonths: 3,
            showButtonPanel: false,
            currentText: 'Vandaag',
            monthNames: ['januari', 'februari', 'maart', 'april', 'mei', 'juni',
            'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
            monthNamesShort: ['jan', 'feb', 'maa', 'apr', 'mei', 'jun',
            'jul', 'aug', 'sep', 'okt', 'nov', 'dec'],
            dayNames: ['zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag'],
            dayNamesShort: ['zon', 'maa', 'din', 'woe', 'don', 'vri', 'zat'],
            dayNamesMin: ['zo', 'ma', 'di', 'wo', 'do', 'vr', 'za'],
            weekHeader: 'Wk',
            onSelect: function () {
                window.open("index.php?page=reserveren" + '&date=' + this.value,'_self');
            }
        });

        // 1 XS reservations
        $( "#datepickerXS" ).datepicker({
            'dateFormat' : 'dd-mm-yy',
            numberOfMonths: 1,
            showButtonPanel: false,
            currentText: 'Vandaag',
            monthNames: ['januari', 'februari', 'maart', 'april', 'mei', 'juni',
            'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
            monthNamesShort: ['jan', 'feb', 'maa', 'apr', 'mei', 'jun',
            'jul', 'aug', 'sep', 'okt', 'nov', 'dec'],
            dayNames: ['zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag'],
            dayNamesShort: ['zon', 'maa', 'din', 'woe', 'don', 'vri', 'zat'],
            dayNamesMin: ['zo', 'ma', 'di', 'wo', 'do', 'vr', 'za'],
            weekHeader: 'Wk',
            onSelect: function () {
                window.open("index.php?page=reserveren" + '&date=' + this.value,'_self');
            }
        });
    */

    // Availability
    $('#datepicker5').multiDatesPicker({
        'dateFormat': 'd-m-yy',
        addDates: ['<? echo $datum; ?>'],
        numberOfMonths: 3,
        onSelect: function(dateText, inst) {
            sendDateAjax(dateText);
        }
    });
</script>