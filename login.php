<?
// create login script
// check if user is logged in
if (isset($_SESSION['user_id'])) {
    // if user is logged in, redirect to home page
    header('Location: index.php');
    exit;
}
?>

<!-- create login form -->
<form action="login.php" method="post">
    <input type="text" name="username" placeholder="Username" />
    <input type="password" name="password" placeholder="Password" />
    <!-- forgot password -->
    <input type="submit" name="submit" value="Login" />


</form>

<?
// user submits login form
// check if user submitted form
if (isset($_POST['submit'])) {
    // if user submitted form, check if username and password are correct
    // get username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // check if username and password are correct
    // connect to database
    $dbc = mysqli_connect('localhost', 'root', '', 'login') or die('Error connecting to MySQL server.');
} else {
    // if user did not submit form, display error message
    echo 'Please enter a username and password.';
}

// check if username and password are correct
// create query
$query = "SELECT user_id, username FROM users WHERE username = '$username' AND password = SHA('$password')";
// run query
$result = mysqli_query($dbc, $query) or die('Error querying database.');
// check if query returned any rows
if (mysqli_num_rows($result) == 1) {
    // if query returned a row, username and password are correct
    // get user_id from query
    $row = mysqli_fetch_array($result);
    $user_id = $row['user_id'];
    // set user_id in session
    $_SESSION['user_id'] = $user_id;
    // redirect to home page
    header('Location: index.php');
    exit;
} else {
    // if query did not return a row, username and password are incorrect
    // display error message
    echo 'Incorrect username and/or password.';
}

// close database connection
mysqli_close($dbc);

?>