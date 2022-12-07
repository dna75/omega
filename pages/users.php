<?php
// Initialize the session

// Include config file
// require_once $_SERVER['DOCUMENT_ROOT'] . '/cockpit/include/spinnerz.inc.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . '/cockpit/include/spinnerz-local.inc.php';

// Check if the user is already logged in, if yes then redirect him to welcome page
// if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

//     if (isset($_GET['id']) && db_escape($_GET['id'] = 'Account')) {
//         header("location: /mijn+gegevens");
//     } else {
//         header("location: /Home");
//     }
// }

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userlogin'])) {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Voer je e-mailadres in.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Voer je wachtwoord in.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM cust_users WHERE username = ?";

        if ($stmt = mysqli_prepare($db, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Refresh page calculation
                            if (db_escape($_GET['id']) != 'Account' && isset($_SESSION['order_id'])) {
                                header("Location: /Home/calc");
                            } else {
                                // Redirect user to welcome page
                                header("location: /Mijn+gegevens");
                            }
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "De gebruikersnaam of het wachtwoord is niet juist ingevoerd.";
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_err = "Ongeldige gebruikersnaam of e-mailadres.";
                }
            } else {
                echo "De gebruikersnaam of het wachtwoord is niet juist ingevoerd.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($db);
}
?>

<!-- Log in -->
<? if (!isset($_GET['fase']) || db_escape($_GET['fase'] == 'success') || db_escape($_GET['fase'] == 'changed') || db_escape($_GET['fase']) == 'update') { ?>

    <div class="row">
        <!-- Created new account, success notice -->
        <? if (isset($_GET['fase']) && db_escape($_GET['fase'] == 'success')) { ?>
            <div class="row">
                <div class="col">
                    <p class="bg-success p-4 text-white text-center">Uw account is succesvol aangemaakt. U kunt nu inloggen.</p>
                </div>
            </div>
        <? } ?>
        <!-- reset password, success notice -->
        <? if (isset($_GET['fase']) && db_escape($_GET['fase'] == 'changed')) { ?>
            <div class="row">
                <div class="col">
                    <p class="bg-success p-4 text-white text-center">Het wachtwoord is succesvol aangepast, u kunt nu inloggen met uw nieuwe wachtwoord.</p>
                </div>
            </div>
        <? } ?>

        <!-- change password / usersname, success notice -->
        <? if (isset($_GET['fase']) && db_escape($_GET['fase'] == 'update')) { ?>
            <div class="row">
                <div class="col">
                    <p class="bg-success p-4 text-white text-center">De inloggegevens zijn succesvol aangepast, u kunt nu inloggen met uw nieuwe inloggevens.</p>
                </div>
            </div>
        <? } ?>


        <div class="col">
            <? if ((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
                if ($_GET['id'] != 'account') {
                    header("location: /Mijn+gegevens");
                } else {
                    // header("location: /Home/calc");

                    header("location: /index.php");
                }
            } else { ?>

                <h6 class="mb-0 text-uppercase text-danger fw-bold">Ik heb al een account <?= $_GET['id']; ?></h6>
                <p class="fs-6 mb-3 fw-light">Voer uw gegevens in om in te loggen.</p>

                <?php
                if (!empty($login_err)) {
                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                }
                ?>
                <form method="post">
                    <div class="row mb-1">
                        <div class="mb-3 col">
                            <input type="text" name="username" placeholder="Gebruikersnaam (email)" class=" form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>
                        <div class="mb-3 col">
                            <input type="password" name="password" placeholder="Wachtwoord" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="submit" class="btn btn-primary" name="userlogin" value="Inloggen">
                        <a class="btn  btn-secondary" href="/Account/Forgot">Wachtwoord vergeten?</a>
                    </div>
                    <div class="mb-3">
                    </div>
                    <!-- <p class="">Nog geen account? <a href="/Account/Register">Maak een account aan</a>.</p> -->
                </form>
            <? } ?>
        </div>
    </div>

<? } ?>

<!-- Register -->
<? if (isset($_GET['fase']) && db_escape($_GET['fase'] == 'Register')) {

    $db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Validate username
        if (empty(trim($_POST["username"]))) {
            $username_err = "Voer je e-mailadres in.";
        } elseif (!filter_var(trim($_POST["username"]), FILTER_VALIDATE_EMAIL)) {
            $username_err = "Voer een geldig e-mailadres in";
        } else {
            // Prepare a select statement
            $sql = "SELECT `id` FROM `cust_users` WHERE `username` = ?";

            if ($stmt = mysqli_prepare($db, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);

                // Set parameters
                $param_username = trim($_POST["username"]);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    /* store result */
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $username_err = "Dit email adres is al geregistreerd..";
                    } else {
                        $username = trim($_POST["username"]);
                    }
                } else {
                    echo "Oeps! Er is iets mis gegaan, probeer het aub opnieuw.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }

        // Validate password
        $password   = trim($_POST["password"]);
        $uppercase  = preg_match('@[A-Z]@', $password);
        $lowercase  = preg_match('@[a-z]@', $password);
        $number     = preg_match('@[0-9]@', $password);
        if (empty(trim($_POST["password"]))) {
            $password_err = "Voer een wachtwoord in.";
        } elseif (!$uppercase || !$lowercase || !$number || strlen($password) < 7) {
            $password_err = "Het wachtwoord moet bestaan uit minimaal 7 tekens, een hoofdletter en een cijfer bevatten.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Validate confirm password
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Bevestig het wachtwoord.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "De wachtwoorden zijn niet gelijk.";
            }
        }

        // Check input errors before inserting in database
        if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

            // Prepare an insert statement
            $sql = "INSERT INTO cust_users (`username`, `password`, `newsletter`) VALUES (?, ?, ?)";

            if ($stmt = mysqli_prepare($db, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssi", $param_username, $param_password, $newsletter);

                // Set parameters
                $param_username = $username;
                $param_password = password_hash($password,  PASSWORD_DEFAULT); // Creates a password hash
                $newsletter = intval($_POST['newsletter']);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to login page
                    $_SESSION['offer'] = true;
                    header("location: /Account/success");
                } else {
                    echo "Oeps! Er is iets mis gegaan, probeer het aub opnieuw.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }

        // Close connection
        mysqli_close($db);
    }
?>

    <h2>Aanmelden</h2>
    <p>Voer je gegevens in om een account aan te maken.</p>
    <form action="/Account/Register" method="post">
        <div class="mb-3">
            <label class="form-label">e-mailadres</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="mb-3">
            <label class="form-label">Wachtwoord</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="mb-3">
            <label class="form-label">Bevestig wachtwoord</label>
            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
        </div>

        <div class="mb-4">
            <input class="form-check-input" type="checkbox" value="1" name="newsletter" id="">
            <label class=" form-check-label text-danger" for="newsletter">
                Ja, ik wil de nieuwsbrief ontvangen
            </label>
        </div>

        <div class="mb-3">
            <input type="submit" class="btn btn-primary" value="Verstuur">
            <!-- <input type="reset" class="btn btn-secondary ml-2" value="Reset"> -->
        </div>
        <p class="mb-5 pb-5">Heb je al een account? <a href="/Account">Inloggen</a>.</p>
    </form>

<? } ?>


<!-- Log out -->
<? if (isset($_GET['fase']) && db_escape($_GET['fase']) == 'Exit') {

    // Destroy the session.
    // remove all session variables
    session_unset();

    // destroy the session 
    session_destroy();

    // Redirect to login page
    header("location: /Home");
    exit;
}
?>

<!-- Forgot Password -->
<? if (isset($_GET['fase']) && db_escape($_GET['fase']) == 'Forgot' && !isset($_GET['userid'])) {

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

                require_once './swift/swift_required.php';

                $MESSAGE_BODY = "Beste," . PHP_EOL;
                $MESSAGE_BODY .= "" . PHP_EOL;
                $MESSAGE_BODY .= "U heeft een verzoek gedaan om uw wachtwoord te resetten." . PHP_EOL;
                $MESSAGE_BODY .= "U kunt hiervoor op de volgende link klikken: " . PHP_EOL;
                $MESSAGE_BODY .= "" . PHP_EOL;

                // $MESSAGE_BODY .= "<a href='$db/register/forgot_password.php?uid=" . $row['id'] . "&code=" . $code . "'>$domein/cockpit/forgot_password.php?uid=" . $row['id'] . "&code=" . $code . "</a>" . PHP_EOL;
                $MESSAGE_BODY .= "<a href='https://bus.spinnerz.nl/Account/Reset/" . $row['id'] . "/" . $code . "'>$domein/cockpit/forgot_password.php?uid=" . $row['id'] . "&code=" . $code . "</a>" . PHP_EOL;

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

                $transport = Swift_SmtpTransport::newInstance('srv4.spinnerz.nl', 25)
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

    if (isset($_POST['userid']) && isset($_POST['code']) && isset($_POST['password']) && isset($_POST['password2'])) {

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

    if (isset($error) && $error != '') echo "<div class='row'><div class='col-xs-12'><p class='text-center'>" . $error . "</p></div></div>";


    if (isset($errorNoExist) && $errorNoExist != '') echo "<div class='row'><div class='col'><p class='text-center bg-warning p-4 text-white'>" . $errorNoExist . "</p></div></div>";

    if (isset($send) && $send == false && $code_send == false) { ?>

        <form class="form-signin" action="/Account/Forgot" method="post">
            <p class="titel red">Direct een bus</p>

            <hr>

            <p>Wachtwoord vergeten? Na het invoeren van uw e-mailadres ontvangt u een link om het wachtwoord te resetten.</p>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa fa-user fa-fw"></i></span>
                <input type="text" class="form-control" name="username" placeholder="Gebruikersnaam (e-mailadres)" autofocus>
            </div>

            <button class="btn btn-md btn-danger btn-block submit text-white" type="submit">Versturen</button>

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

                    <button class="btn btn-sm btn-danger btn-block mt-4" type="submit">Versturen</button>

                </form>

            <? } elseif ($send == true) { ?>

                <div class="col">
                    <p class="bg-success text-white text-center p-3">Er is een email verzonden naar het aangegeven emailadres. Met deze email kunt u uw wachtwoord resetten.</p>
                </div>
            <? } ?>
            </div>
        <?
    }
        ?>

        <!-- Change Password -->
        <? if (isset($_GET['fase']) && db_escape($_GET['fase']) == 'Reset') {

            $new_password = $confirm_password = "";
            $new_password_err = $confirm_password_err = "";

            $actual_link = '/Account/Reset/' . $_GET['userid'] . '/' . $_GET['code'];

            echo 'Fase is:' . $_GET['fase'];

            // Processing form data when form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // Validate new password
                $new_password   = trim($_POST["new_password"]);
                $lowercase  = preg_match('@[a-z]@', $new_password);
                $number     = preg_match('@[0-9]@', $new_password);
                $uppercase  = preg_match('@[A-Z]@', $new_password);
                if (empty(trim($_POST["new_password"]))) {
                    $new_password_err = "Voer het nieuwe wachtwoord in.";
                } elseif (!$uppercase || !$lowercase || !$number || strlen($new_password) < 7) {
                    $new_password_err = "Het wachtwoord moet bestaan uit minimaal 7 tekens, een hoofdletter en een cijfer bevatten.";
                } else {
                    $new_password = trim($_POST["new_password"]);
                }

                // Validate confirm password
                if (empty(trim($_POST["confirm_password"]))) {
                    $confirm_password_err = "Bevestig je wachtwoord.";
                } else {
                    $confirm_password = trim($_POST["confirm_password"]);
                    if (empty($new_password_err) && ($new_password != $confirm_password)) {
                        $confirm_password_err = "De wachtwoorden zijn niet gelijk";
                    }
                }

                // Check input errors before updating the database
                if (empty($new_password_err) && empty($confirm_password_err)) {
                    // Prepare an update statement
                    $sql = "UPDATE cust_users SET password = ? WHERE id = ?";

                    if ($stmt = mysqli_prepare($db, $sql)) {
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

                        // Set parameters
                        $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $param_id = intval($_GET['userid']);

                        // Attempt to execute the prepared statement
                        if (mysqli_stmt_execute($stmt)) {
                            // Password updated successfully. Destroy the session, and redirect to login page
                            session_destroy();
                            header("location: /Account/changed");
                            exit();
                        } else {
                            echo "Oeps! Er is iets misgegaan, probeer het aub opnieuw.";
                        }

                        // Close statement
                        mysqli_stmt_close($stmt);
                    }
                }

                // Close connection
                mysqli_close($db);
            }
        ?>

            <h2>Wachtwoord resetten</h2>
            <p>Voer in het onderstaande formulier uw nieuwe wachtwoord in.</p>

            <form action="<?= $actual_link; ?>" method="post">
                <div class="mb-3">
                    <label>Nieuw wachtwoord</label>
                    <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                    <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                </div>
                <div class="mb-3">
                    <label>Bevestig (herhaal) wachtwoord</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="mb-3">
                    <input type="submit" class="btn btn-primary" value="Verstuur">
                    <a class="btn btn-link ml-2" href="/Account">Annuleren</a>
                </div>
            </form>
        <?
        }
        ?>