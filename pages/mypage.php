<div class="row mt-5">
    <?
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

        $queryUser = $db->query("SELECT * FROM `cust_users` WHERE `id` = '" . $_SESSION['id'] . "' ");
        while ($row = mysqli_fetch_object($queryUser)) { ?>

            <? ($_GET['sub'] == 'trip' ? $active = 'active' : ''); ?>

            <div class="col-md-auto mb-4 mb-md-0">
                <div class="btn-group-vertical w-100" role="group" aria-label="Button group">
                    <a href="/<?= urlencode(db_escape($_GET['id'])); ?>/trip#show" class="btn btn-sm btn-outline-primary mb-2 w-100 p-md-3  <?= (db_escape($_GET['sub']) == 'trip' || empty($_GET['sub']) ? 'active' : ''); ?>">Huur historie</a>
                    <a href="/<?= urlencode(db_escape($_GET['id'])); ?>/personal#show" class="btn btn-sm btn-outline-primary mb-2 p-md-3 <?= (db_escape($_GET['sub']) == 'personal' ? 'active' : ''); ?>">Mijn gegevens</a>
                    <a href="/<?= urlencode(db_escape($_GET['id'])); ?>/user#show" class="btn btn-sm btn-outline-primary mb-2 p-md-3 <?= (db_escape($_GET['sub']) == 'user' ? 'active' : ''); ?>">Inloggegevens</a>
                    <a href="/Account/Exit" class="btn btn-sm btn-danger mb-2 ml-3 p-md-3 text-light">Uitloggen</a>
                </div>
            </div>

        <? } ?>




        <!-- Trip Details One Trip -->
        <? if (isset($_GET['sub']) && db_escape($_GET['sub']) == 'details'  && isset($_GET['oid'])) {

            $query = $db->query("SELECT * FROM `booking_temp` WHERE id = '" . intval($_GET['oid']) . "' AND `customer_id` = '" . intval($_SESSION['id']) . "' ") or die(mysqli_error($db));
            $row = mysqli_fetch_object($query);
        ?>
            <div class="col">
                <p class="text-danger">Details opdracht : <?= date('ym', strtotime($row->createdDateTime)); ?>00<?= $row->res_id; ?></p>
                <? if ($row->cancelled) { ?>
                    <p class="text-primary">
                        Deze opdracht is geannuleerd op <? echo date('d-m-Y H:i', strtotime($row->cancelledDate)); ?><br>
                        De opdracht is <? echo difference($row->dateFrom, $row->cancelledDate); ?> dagen voor vertrek geannuleerd en het bedrag van €<?= price($row->refunded); ?> (<?= $row->refunded / ($row->price / 100); ?>% van de reissom) is teruggestort op uw rekening.
                    </p>

                <? } ?>
                <table class="table table-striped small">
                    <? if (!empty($row->ireference)) { ?>
                        <tr>
                            <td colspan="2">Referentie / Kostenplaats</td>
                            <td colspan="2"><?= $row->ireference; ?></td>
                        </tr>
                    <? } ?>

                    <tr>
                        <td>Vertrekdatum</td>
                        <td><?= $row->dateFrom; ?></td>
                        <td>Vertrektijd</td>
                        <td><?= $row->timeFrom; ?></td>
                    </tr>

                    <tr>
                        <td colspan="2"></td>
                        <td>Aankomsttijd</td>
                        <td><?= $row->timeDestArrival; ?></td>
                    </tr>


                    <? if (!empty($row->dateReturn)) { ?>
                        <tr>
                            <td>Datum terugreis</td>
                            <td><?= $row->dateReturn; ?></td>
                            <td>Vertrektijd terugreis</td>
                            <td><?= $row->timeReturn; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Aankomsttijd</td>
                            <td><?= $row->timeHomeArrival; ?></td>
                        </tr>

                    <? } ?>
                    <tr>
                        <td colspan="2">Adres gegegevens vertrek</td>
                        <td colspan="3"><?= $row->addressFrom; ?> <?= $row->zipcodeFrom; ?> <?= $row->cityFrom; ?> </td>
                    </tr>
                    <? if (!empty($row->dateReturn)) { ?>
                        <tr>
                            <td colspan="2">Adres gegegevens bestemming / terugreis</td>
                            <td colspan="3"><?= $row->addressTo; ?> <?= $row->zipcodeTo; ?> <?= $row->cityTo; ?> </td>
                        </tr>
                    <? } ?>
                </table>

                <a href="javascript:history.go(-1)" class="btn btn-danger btn-sm text-white w-100"><i class="fa fa-chevron-left"></i> Terug naar overzicht reizen</a>
            </div>
        <? } ?>


        <!-- TRIP OVERVIEW -->
        <? if (isset($_GET['sub']) && db_escape($_GET['sub']) == 'trip' || empty($_GET['sub'])) { ?>
            <div class=" col">
                <p class="text-danger">Mijn reizen:</p>
                <? $query = $db->query("SELECT * FROM `cust_data` WHERE `userId` = '" . intval($_SESSION['id']) . "' ") or die(mysqli_error($db));
                if (mysqli_num_rows($query) == 0) { ?>

                    <a href="/<?= db_escape($_GET['id']); ?>/personal#show" class="btn btn-primary w-100 text-white mb-3"><i class="fa fa-chevron-right"></i> Maak uw account compleet en voer uw gegevens in - klik hier</a>
                <? } ?>
                <div class="table-responsive">
                    <table class="table small">

                        <?
                        $queryUserdata = $db->query("SELECT * FROM `booking_temp` WHERE `customer_id` = '" . $_SESSION['id'] . "' AND `status` = 'paid' ORDER BY `id` DESC ") or die(mysqli_error($db));

                        $count = mysqli_num_rows($queryUserdata);
                        if ($count >= 1) { ?>

                            <tr>
                                <th>Opdracht</th>
                                <th>Vertrekdatum</th>
                                <th>Plaats vertrek</th>
                                <th>Plaats aankomst</th>
                                <th>Aantal personen</th>
                                <th colspan="2">Reis info</th>
                            </tr>

                            <? while ($rowUserData = mysqli_fetch_object($queryUserdata)) {
                                $cancelTrip = cancelTrip($rowUserData->id, $_SESSION['id'], $db) ?>
                                <tr>
                                    <td><?= date('ym', strtotime($rowUserData->createdDateTime)); ?>00<?= $rowUserData->res_id; ?></td>
                                    <td><?= $rowUserData->dateFrom; ?></td>
                                    <td><?= $rowUserData->cityFrom; ?></td>
                                    <td><?= $rowUserData->cityTo; ?></td>
                                    <td><?= $rowUserData->persons; ?></td>
                                    <td><a href="/<?= urlencode(db_escape($_GET['id'])); ?>/details/<?= $rowUserData->id; ?>" class="btn btn-primary btn-sm">Details</td>

                                    <? if ($rowUserData->cancelled) { ?>
                                        <td>
                                            <a class="btn btn-danger btn-sm text-white disabled">geannuleerd</a>
                                        </td>
                                    <? } else { ?>
                                        <td>
                                            <a href=" /<?= urlencode(db_escape($_GET['id'])); ?>/del/<?= $rowUserData->id; ?>" onclick="return confirm('Weet u zeker dat u uw reservering wilt annuleren? Uw reis is over <?= $cancelTrip['differenceDays']; ?> dagen en u ontvangt €<?= $cancelTrip['refundAmount']; ?> retour.');" class="btn btn-danger btn-sm text-white">Annuleren</a>
                                        </td>
                                    <? } ?>

                                <? }
                        } else { ?>
                                <p>U heeft geen auto's gehuurd.</p>
                            <? } ?>
                    </table>
                </div>
            </div>
        <?
        }
        ?>
        <!-- Invoice overview -->
        <? if (isset($_GET['sub']) && db_escape($_GET['sub']) == 'invoice') { ?>
            <div class="col">
                <p class="text-danger">Mijn facturen:</p>

                <div class="table-responsive">
                    <table class="table small">

                        <?
                        $queryUserData = $db->query("
                        SELECT * FROM `booking_temp` 
                        WHERE `customer_id` = '" . $_SESSION['id'] . "'
                        AND `status` = 'paid' 
                        ORDER BY `id` DESC 
                        ") or die(mysqli_error($db));

                        $count = mysqli_num_rows($queryUserData);
                        if ($count >= 1) { ?>

                            <tr>
                                <th>Factuurnummer</th>
                                <th>Factuurdatum</th>
                                <th>Betaalstatus</th>
                                <th>Download</th>
                            </tr>

                            <? while ($rowUserData = mysqli_fetch_object($queryUserData)) {

                                if ($rowUserData->status == 'paid') {
                                    $paymentStatus = 'Betaald';
                                } else {
                                    $paymentStatus = 'Open';
                                }
                                if ($rowUserData->cancelled) {  ?>
                                    <tr>
                                        <td>DEBC<?= date('ym', strtotime($rowUserData->cancelledDate)); ?>00<?= $rowUserData->res_id; ?></td>
                                        <td><?= date('d-m-Y', strtotime($rowUserData->cancelledDate)); ?></td>
                                        <td><button type="button" class="w-100 btn btn-sm text-uppercase btn-outline-success">Teruggestort</button></td>
                                        <td>
                                            <form method="post" action="/modules/credit.php">
                                                <button type="submit" name="invoice_id" value="<?= $rowUserData->res_id; ?>" class=" btn btn-primary btn-sm w-100">DOWNLOAD</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>DEB<?= date('ym', strtotime($rowUserData->createdDateTime)); ?>00<?= $rowUserData->res_id; ?></td>
                                        <td><?= date('d-m-Y', strtotime($rowUserData->createdDateTime)); ?></td>
                                        <td><button type="button" class="w-100 btn btn-sm text-uppercase btn-outline-success"><?= $paymentStatus; ?></button></td>
                                        <td>
                                            <form method="post" action="/modules/invoice.php">
                                                <button type="submit" name="invoice_id" value="<?= $rowUserData->res_id; ?>" class=" btn btn-primary btn-sm w-100">DOWNLOAD</button>
                                            </form>
                                        </td>
                                    </tr>
                                <? } else { ?>
                                    <tr>
                                        <td>DEB<?= date('ym', strtotime($rowUserData->createdDateTime)); ?>00<?= $rowUserData->res_id; ?></td>
                                        <td><?= date('d-m-Y', strtotime($rowUserData->createdDateTime)); ?></td>
                                        <td><button type="button" class="w-100 btn btn-sm text-uppercase btn-outline-success"><?= $paymentStatus; ?></button></td>
                                        <td>
                                            <form method="post" action="/modules/invoice.php">
                                                <button type="submit" name="invoice_id" value="<?= $rowUserData->res_id; ?>" class=" btn btn-primary btn-sm w-100">DOWNLOAD</button>
                                            </form>
                                        </td>
                                <? }
                            }
                        } else { ?>
                                <p>U heeft nog geen facturen.</p>
                            <? } ?>
                    </table>
                </div>
            </div>
        <? } ?>

        <!-- Personal data overview -->
        <? if (isset($_GET['sub']) && db_escape($_GET['sub']) == 'personal') { ?>

            <? if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['myDetails'])) {

                $query = $db->query("SELECT * FROM `cust_data` WHERE `userId` = '" . intval($_SESSION['id']) . "' ") or die(mysqli_error($db));
                if (mysqli_num_rows($query) != '1') {

                    $db->query("INSERT INTO `cust_data` (`userId`, `companyname`, `firstname`,`lastname`,`address`,`zipcode`,`city`,`phone`) VALUES (
                    '" . intval($_SESSION['id']) . "',
                    '" . db_escape($_POST['companyname']) . "',
                    '" . db_escape($_POST['firstname']) . "',
                    '" . db_escape($_POST['lastname']) . "', 
                    '" . db_escape($_POST['address']) . "', 
                    '" . db_escape($_POST['zipcode']) . "', 
                    '" . db_escape($_POST['city']) . "', 
                    '" . db_escape($_POST['phone']) . "' 
                    )") or die(mysqli_error($db));

                    $db->query("UPDATE `cust_users` SET `newsletter` = '" . intval($_POST['newsletter']) . "' ") or die(mysqli_error($db));
                } else {
                    $db->query("UPDATE `cust_data` SET
                    `companyname` = '" . db_escape($_POST['companyname']) . "',
                    `firstname`   = '" . db_escape($_POST['firstname']) . "',
                    `lastname`    = '" . db_escape($_POST['lastname']) . "',
                    `address`     = '" . db_escape($_POST['address']) . "',
                    `zipcode`     = '" . db_escape($_POST['zipcode']) . "',
                    `city`        = '" . db_escape($_POST['city']) . "',
                    `phone`       = '" . db_escape($_POST['phone']) . "'
                    WHERE `userId` = '" . intval($_SESSION['id']) . "'
                    ") or die(mysqli_error($db));

                    $db->query("UPDATE `cust_users` SET `newsletter` = '" . intval($_POST['newsletter']) . "' ") or die(mysqli_error($db));
                }

                header("Location: " . $_SERVER['REQUEST_URI'] . '#show/success');
            }
            ?>
            <div class="col">
                <p class="text-danger">Mijn gegevens:</p>

                <? echo $_GET['stat']; ?>
                <? if (db_escape($_GET['status']) == 'success') {
                    echo '<div class="alert alert-success" role="alert">Uw gegevens zijn succesvol aangepast.</div>';
                } ?>

                <div class="row">
                    <!-- Customers details -->
                    <div class="col">
                        <!-- Get all personal data -->
                        <? $queryUserData = $db->query("SELECT * FROM `cust_data` WHERE `userid` = '" . intval($_SESSION['id']) . "'") or die(mysqli_error($db));
                        $rowUserData = mysqli_fetch_object($queryUserData); ?>

                        <!-- Get newsletter yes or no -->
                        <? $queryUserNewsletter = $db->query("SELECT `newsletter` FROM `cust_users` WHERE `id` = '" . intval($_SESSION['id']) . "'") or die(mysqli_error($db));
                        $rowUserNewsletter = mysqli_fetch_object($queryUserNewsletter); ?>

                        <form method="post" onkeydown="return event.key != 'Enter';">
                            <div class='mb-3'>
                                <label class="form-label" for='companyname'>Bedrijfsnaam / instelling (optioneel)</label>
                                <input type='text' class='form-control' id='' name='companyname' value='<?php echo (isset($rowUserData)) ? $rowUserData->companyname : ''; ?>'>
                            </div>
                            <div class="row mb-3">
                                <div class='col-md-6'>
                                    <label class="form-label" for='firstname'>Voornaam</label>
                                    <input type='text' class='form-control' id='' name='firstname' value='<?php echo (isset($rowUserData)) ? $rowUserData->firstname : ''; ?>' required>
                                </div>
                                <div class='col-md-6'>
                                    <label class="form-label" for='lastname'>Achternaam</label>
                                    <input type='text' class='form-control' id='' name='lastname' value='<?php echo (isset($rowUserData)) ? $rowUserData->lastname : ''; ?>' required>
                                </div>
                            </div>
                            <div class='mb-3'>
                                <label class="form-label" for='address'>Adres</label>
                                <input type='text' class='form-control' id='' name='address' value='<?php echo (isset($rowUserData)) ? $rowUserData->address : ''; ?>' required>
                            </div>
                            <div class="row mb-3">
                                <div class='col-md-6'>
                                    <label class="form-label" for='zipcode'>Postcode</label>
                                    <input type='text' class='form-control' id='' name='zipcode' value='<?php echo (isset($rowUserData)) ? $rowUserData->zipcode : ''; ?>' required>
                                </div>
                                <div class='col-md-6'>
                                    <label class="form-label" for='city'>Plaats</label>
                                    <input type='text' class='form-control' id='' name='city' value='<?php echo (isset($rowUserData)) ? $rowUserData->city : ''; ?>' required>
                                </div>
                            </div>
                            <div class='mb-3'>
                                <label class="form-label" for='phone'>Telefoon</label>
                                <input type='text' class='form-control' id='' name='phone' value='<?php echo (isset($rowUserData)) ? $rowUserData->phone : ''; ?>' required>
                            </div>

                            <div class="mb-4">
                                <? if ($rowUserNewsletter->newsletter == '1') {
                                    $checked = 'checked';
                                } else {
                                    $checked = '';
                                } ?>
                                <input class="form-check-input" type="checkbox" value="1" name="newsletter" id="" <?= $checked; ?>>
                                <label class="form-check-label text-danger" for="newsletter">
                                    Ja, ik wil de nieuwsbrief ontvangen
                                </label>
                            </div>

                            <button type="submit" name="myDetails" class="btn btn-success text-white mt-3">Opslaan</button>

                        </form>

                    </div>

                </div>

            </div>
        <? } ?>

        <!-- User data overview -->
        <? if (isset($_GET['sub']) && db_escape($_GET['sub']) == 'user') { ?>

            <?
            // Processing form data when form is submitted - UPDATE USERNAME
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user'])) {

                // Validate username
                if (empty(trim($_POST["username"]))) {
                    $username_err = "Voer je e-mailadres in.";
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
                            $username = trim($_POST["username"]);
                        } else {
                            echo "Oeps! Er is iets mis gegaan, probeer het aub opnieuw.";
                        }

                        // Close statement
                        mysqli_stmt_close($stmt);
                    }
                }

                // Check input errors before inserting in database
                if (empty($username_err)) {

                    // Prepare an insert statement
                    $sql = "UPDATE cust_users SET `username` = ? WHERE `id` = ?";

                    if ($stmt = mysqli_prepare($db, $sql)) {
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "si", $param_username, $id);

                        // Set parameters
                        $param_username = $username;
                        $id = intval($_SESSION['id']);
                        // Attempt to execute the prepared statement
                        if (mysqli_stmt_execute($stmt)) {
                            // Redirect to login page
                            session_unset();
                            session_destroy();
                            header("location: /Account/update");
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

            <?
            // Processing form data when form is submitted - UPDATE PASSWORD
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pass'])) {

                // Validate username

                $sql = "SELECT `id` FROM `cust_users` WHERE `username` = ?";

                if ($stmt = mysqli_prepare($db, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_username);

                    // Set parameters
                    $param_username = db_escape($_SESSION["username"]);

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        /* store result */
                        mysqli_stmt_store_result($stmt);
                        $username = db_escape($_SESSION["username"]);
                    } else {
                        echo "Oeps! Er is iets mis gegaan, probeer het aub opnieuw.";
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
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
                if (empty($password_err) && empty($confirm_password_err)) {

                    // Prepare an insert statement
                    $sql = "UPDATE cust_users SET `password` = ? WHERE `id` = ?";

                    if ($stmt = mysqli_prepare($db, $sql)) {
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "si", $param_password, $id);

                        // Set parameters
                        $param_password = password_hash($password,  PASSWORD_DEFAULT); // Creates a password hash
                        $id = intval($_SESSION['id']);
                        // Attempt to execute the prepared statement
                        if (mysqli_stmt_execute($stmt)) {
                            // Redirect to login page
                            session_unset();
                            session_destroy();
                            header("location: /Account/update");
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
            <div class="col">
                <p class="text-danger">Mijn inloggegevens:</p>

                <div class="row">
                    <!-- Customers details -->
                    <div class="col">

                        <!-- Change username -->
                        <form method="post" onkeydown="return event.key != 'Enter';">
                            <div class='mb-4'>
                                <p>Gebruikersnaam (e-mail) wijzigen:</p>
                                <input type='text' class='form-control' id='' name='username' value='<?php echo (isset($_SESSION['username'])) ? $_SESSION['username'] : ''; ?>' required>
                            </div>
                            <button type="submit" name="user" value="user" class="btn btn-success text-white mb-4">Opslaan</button>
                        </form>

                        <!-- Change password -->
                        <form method="post" onkeydown="return event.key != 'Enter';">
                            <div class="row mb-3">
                                <p>Wachtwoord wijzigen:</p>
                                <div class="col-md-6">
                                    <label class="form-label">Nieuw wachtwoord</label>
                                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Bevestig nieuw wachtwoord</label>
                                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                                </div>
                            </div>

                            <button type="submit" name="pass" value="pass" class="btn btn-success text-white mt-3">Opslaan</button>

                        </form>

                    </div>

                </div>

            </div>
        <? } ?>

    <? } ?>
</div>