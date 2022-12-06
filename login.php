<? // create user login form 
// form
echo "<form action='login.php' method='post'>
Username: <input type='text' name='username' /><br />
Password: <input type='password' name='password' /><br />
<input type='submit' name='submit' value='Login' />
</form>";

// check if form has been submitted
if (isset($_POST['submit'])) {
    // check if username and password are correct
    if (($_POST['username'] == 'username') && ($_POST['password'] == 'password')) {
        // if they are correct, set session variables
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];
        // redirect to secure page
        header('Location: secure.php');
    } else {
        // if they are not correct, display error message
        echo 'Incorrect username or password';
    }
}
