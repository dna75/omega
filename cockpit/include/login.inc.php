<?php
/* include_once('config.inc.php'); */
include_once('users/user.class.php');

if (isset($devsite) && $devsite !="") {
    $cockpit_url = (isset($cockpit_url) && $cockpit_url != '') ? $cockpit_url : $devsite.'/cockpit';
}
else {
    $cockpit_url = (isset($cockpit_url) && $cockpit_url != '') ? $cockpit_url : '../cockpit';
}

$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (isset($_POST['login_username']) && $_POST['login_password']) {
    $user = new User(strtolower($_POST['login_username']), $_POST['login_password'], $cockpit_url);
} else {
    $user = new User('','',$cockpit_url);
}

?>
