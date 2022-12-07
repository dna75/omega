<?php
$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

// require_once $_SERVER['DOCUMENT_ROOT'] . '/cockpit/include/spinnerz.inc.php';
// //require_once $_SERVER['DOCUMENT_ROOT'] . '/cockpit/include/spinnerz-local.inc.php';

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Voer je email-adres in.";
    } elseif (!filter_var(trim($_POST["username"]), FILTER_VALIDATE_EMAIL)) {
        $username_err = "Voer een geldig email-adres in";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM cust_users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
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
        $sql = "INSERT INTO cust_users (`username`, `password`) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password,  PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header("location: login.php");
            } else {
                echo "Oeps! Er is iets mis gegaan, probeer het aub opnieuw.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    //    mysqli_close($link);
}
?>

<div class="container mt-4">
    <h2>Aanmelden</h2>
    <p>Voer je gegevens in om een account aan te maken.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Email-adres</label>
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
        <div class="mb-3">
            <input type="submit" class="btn btn-primary" value="Verstuur">
            <!-- <input type="reset" class="btn btn-secondary ml-2" value="Reset"> -->
        </div>
        <p class="mb-5 pb-5">Heb je al een account? <a href="/Account">Inloggen</a>.</p>
    </form>
</div>