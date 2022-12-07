<?php
ob_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../cockpit/include/config.inc.php');

$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$send = false;
$code_send = false;
$reset = false;

if (isset($_POST['username'])) {

    // check if email exists
    $username = mysqli_real_escape_string($db, $_POST['username']);

    $result = $db->query("SELECT 		id
        FROM		`cust_users`
        WHERE		username = '" . $username . "'
        LIMIT		1
        ") or die(mysqli_error($db));

    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_array($result)) {

            // create code
            $code = $random_password = md5(uniqid(rand()));

            require_once '../swift/swift_required.php';

            $MESSAGE_BODY = "Beste," . PHP_EOL;
            $MESSAGE_BODY .= "" . PHP_EOL;
            $MESSAGE_BODY .= "U heeft een verzoek gedaan om uw wachtwoord te resetten." . PHP_EOL;
            $MESSAGE_BODY .= "U kunt hiervoor op de volgende link klikken: " . PHP_EOL;
            $MESSAGE_BODY .= "" . PHP_EOL;

            // $MESSAGE_BODY .= "<a href='$link/register/forgot_password.php?uid=" . $row['id'] . "&code=" . $code . "'>$domein/cockpit/forgot_password.php?uid=" . $row['id'] . "&code=" . $code . "</a>" . PHP_EOL;
            $MESSAGE_BODY .= "<a href='http://bus:8888/register/forgot_password.php?uid=" . $row['id'] . "&code=" . $code . "'>$domein/cockpit/forgot_password.php?uid=" . $row['id'] . "&code=" . $code . "</a>" . PHP_EOL;

            $MESSAGE_BODY .= "" . PHP_EOL;
            $MESSAGE_BODY .= "" . PHP_EOL;
            $MESSAGE_BODY .= "Met vriendelijke groet,<br><br>Direct een Bus" . PHP_EOL;
            $MESSAGE_BODY .= "" . PHP_EOL;
            $MESSAGE_BODY = nl2br($MESSAGE_BODY);

            // update code
            $db->query("UPDATE 	cust_users
                    SET		recover = '$code'
                    WHERE	id = " . $row['id'] . "
                    ") or die(mysqli_error($db));

            // send email

            $transport = Swift_SmtpTransport::newInstance('localhost', 25)
                // $transport = Swift_SmtpTransport::newInstance('localhost', 1025);
                ->setUsername('info@spinnerz.nl')
                ->setPassword('f7I1_vs9');

            $mailer = Swift_Mailer::newInstance($transport);

            $message = Swift_Message::newInstance('Wachtwoord herstellen');
            $message->setFrom(array('info@spinnerz.nl' => 'Admin Spinnerz'));

            $message->setTo(array('info@spinnerz.nl'));
            $message->setReplyTo(array('info@spinnerz.nl' => 'Admin Spinnerz'));
            $message->setBody($MESSAGE_BODY, 'text/html');

            $result = $mailer->send($message);

            $send = true;
        }
    } elseif (mysqli_num_rows($result) == 0) {

        $errorNoExist = 'Het opgegeven emailadres is niet geldig.';
    } else {

        $error = "Het opgegeven wachtwoord is niet geldig.";
    }
}

if (isset($_GET['uid']) && isset($_GET['code'])) {

    $code_send = true;
}

if (isset($_POST['uid']) && isset($_POST['code']) && isset($_POST['password']) && isset($_POST['password2'])) {

    // check values
    $db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

    $result = $db->query("SELECT 		id, salt
                FROM		`users`
                WHERE		id = '" . mysqli_real_escape_string($db, $_POST['uid']) . "'
                AND			recover = '" . mysqli_real_escape_string($db, $_POST['code']) . "'
                LIMIT		1
                ") or die(mysqli_connect_errno());

    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_array($result)) {

            if ($_POST['password'] == $_POST['password2']) {

                $db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

                // update password
                $blowfish_pre = '$2a$05$';
                $blowfish_end = '$';

                $password = mysqli_real_escape_string($db, $_POST['password']);

                $bcrypt_salt = $blowfish_pre . $row['salt'] . $blowfish_end;

                $hashed_password = crypt($password, $bcrypt_salt);

                mysqli_query($db, "UPDATE 	users
                                SET		recover = '',
                                password = '" . $hashed_password . "'
                                WHERE	id = '" . $row['id'] . "'
                                ") or die(mysqli_connect_errno());
                header('Location: login.php?message=recovered');
            } else {

                $error = "De wachtwoorden zijn niet gelijk.";
            }
        }
    } else {

        $code_send = true;
        $error = "Er ging iets verkeerd, het wachtwoord kan niet hersteld worden.";
    }
}


?>

<html>

<head>

    <!-- <link rel="stylesheet" href="/cockpit/styles/login.css" type="text/css" /> -->

    <link href="/cockpit/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">


    <link href="/cockpit/vendor/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
                    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
                    <![endif]-->
</head>

<body>

    <div class="container">

        <!-- <div class="row">
            <div class="col-xs-10 col-xs-offset-1 col-md-4 col-md-offset-4 ">
                <img class="img-responsive" src="/cockpit/images/loginspinnerz.png" alt="loginspinnerz" width="" height="" />
            </div>
        </div> -->


        <?php if (isset($error) && $error != '') echo "<div class='row'><div class='col-xs-12'><p class='text-center'>" . $error . "</p></div></div>"; ?>


        <?php if (isset($errorNoExist) && $errorNoExist != '') echo "<div class='container'><div class='row'><div class='col-xs-10 col-xs-offset-1 col-md-4 col-md-offset-4' style='background-color:#000;'><p class='text-center' style='color:red;'>" . $errorNoExist . "</p></div></div></div>"; ?>

        <? if (isset($send) && $send == false && $code_send == false) { ?>

            <form class="form-signin" action="/register/forgot_password.php" method="post">
                <p class="titel red">Direct een bus</p>
                <hr>

                <p>Wachtwoord vergeten? Na het invoeren van je email adres ontvang je een link om je wachtwoord te resetten.</p>

                <div class=" input-group">
                    <div class="input-group-addon"><i class="fa fa-user fa-fw"></i></div>
                    <input type="text" class="form-control" name="username" placeholder="Gebruikersnaam (e-mailadres)" autofocus>
                </div>
                <button class="btn btn-lg btn-danger btn-block submit" style="background-color:#e51937 !important; border-radius:0px !important;" type="submit">Versturen</button>

            </form>

        <? } elseif ($code_send == true) { ?>


            <div class="container">

                <div class="row">
                    <form class="form-signin" action="/cockpit/forgot_password.php" method="post">

                        <p>Wachtwoord herstellen</p>

                        <input type="hidden" name="uid" value="<?= $_GET['uid']; ?>" />
                        <input type="hidden" name="code" value="<?= $_GET['code']; ?>" />

                        <input type="password" class="form-control" name="password" placeholder="Wachtwoord">
                        <input type="password" class="form-control" name="password2" placeholder="Wachtwoord herhalen">

                        <button class="btn btn-lg btn-danger btn-block" type="submit">Versturen</button>

                    </form>

                <? } elseif ($send == true) { ?>

                    <div class="row">
                        <div class="col-md-4 col-md-offset-4 well well-lg">
                            <p>Er is een email verzonden naar het aangegeven emailadres. Met deze email kunt u uw wachtwoord resetten.</p>
                        </div>
                    </div>
                <? } ?>
                </div>

                <script src="/cockpit/scripts/jquery.js"></script>
                <script src="/cockpit/scripts/jquery-ui.min.js"></script>
                <script src="/cockpit/vendor/bootstrap/bootstrap.min.js"></script>


</body>

</html>