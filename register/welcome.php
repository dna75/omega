<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<div class="container">
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welkom bij direct een bus.</h1>
    <p>
        <a href="reset.php" class="btn btn-warning">Herstel je wachtwoord</a>
        <a href="logout.php" class="btn btn-danger ml-3">Uitloggen</a>
    </p>
</div>