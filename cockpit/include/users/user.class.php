<?php
/*
 *	A handy user class which combines a login script with the addition
 *	of user roles and right.
 *
 *	The email and password data is heavily encrypted. The email with a AES /salt
 *	and the password with a Blowfish / salt combination.
 *
 *	@company: 	Amplo
 *	@author:	 Nanne Dijkstra
 *	@version: 	0.1
 *
 */
CRYPT_BLOWFISH or die('No Blowfish found.');


define('LOGIN_URL', '/login.php');
define('LOGOUT_URL', '/login.php');
define('ILLEGAL_URL', '/index.php?page=forbidden');

class User
{

	public $firstname 		= 'John';
	public $lastname		= 'Doe';
	public $logged_in 		= false;
	public $email 			= '';
	public $language		= '';

	public $id = 0;
	public $roles 			= array();
	private $cockpit_url	= 'cockpit';

	//blowfish settings
	private $blowfish_pre 	= '$2a$05$';
	private $blowfish_end 	= '$';


	function __construct($email = '', $password = '', $cockpit_url = 'cockpit')
	{

		$this->cockpit_url = ($cockpit_url != '') ? $cockpit_url : 'cockpit';

		if ($email != '' && $password != '') {
			$this->login($email, $password);
		}
		if ($this->id == 0 && $this->logged_in == false) {
			$this->session_login();
		}
		if ($this->id > 0 && $this->logged_in == true) {
			$this->load_defaults();
			$this->get_roles();
		}
	}

	private function login($email, $password)
	{

		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$email 		= mysqli_real_escape_string($db, $email);
		$password 	= mysqli_real_escape_string($db, $password);

		$result = $db->query("SELECT 		id, salt, password, language
								FROM		`users`
								WHERE		email = AES_ENCRYPT('$email', salt)
								LIMIT		1
								") or die(mysqli_error($db));
		while ($row = mysqli_fetch_array($result)) {

			$hashed_pass = crypt($password, $this->blowfish_pre . $row['salt'] . $this->blowfish_end);

			if ($hashed_pass == $row['password']) {

				$this->id 				= $row['id'];
				$this->logged_in 		= true;
				$_SESSION['user_id'] 	= $row['id'];
				$_SESSION['logged_in'] 	= true;
			}
		}
	}

	public 	function logout()
	{

		session_destroy();
		header('Location: /' . $this->cockpit_url . LOGOUT_URL);
	}

	private function session_login()
	{

		if ($_SESSION['logged_in'] == true && $_SESSION['user_id'] > 0) {

			$this->id = $_SESSION['user_id'];
			$this->logged_in = $_SESSION['logged_in'];
		}
	}

	private function load_defaults()
	{

		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$result = $db->query('SELECT 		AES_DECRYPT(email, salt),
											firstname,
											lastname
								FROM		`users`
								WHERE		`id` = ' . $this->id);

		while ($row = mysqli_fetch_array($result)) {

			$this->email 		= (isset($row['email'])) ? $row['email'] : '';
			$this->firstname 	= (isset($row['firstname'])) ? $row['firstname'] : '';
			$this->lastname 	= (isset($row['lastname'])) ? $row['lastname'] : '';
			$this->logged_in 	= true;
			$this->language		= (isset($row['language'])) ? $row['language'] : '';
		}
	}

	private function get_roles()
	{

		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$result = $db->query(
			'SELECT 		DISTINCT role_id
								FROM		`user_role`
								WHERE		`user_id` = ' . $this->id
		);
		while ($row = mysqli_fetch_array($result)) {

			$this->roles[] = $row['role_id'];
		}
	}

	public 	function check_right($right, $redirect = false)
	{

		foreach ($this->roles as $role) {

			$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

			$result = $db->query("SELECT 		rori.id
									FROM		role_right 			AS rori
									JOIN		rights				AS ri
									ON			rori.right_id		= ri.id
									WHERE		ri.slug				= '$right'
									AND			rori.role_id		= '$role'
									");

			if (mysqli_num_rows($result) > 0) return true;
		}
		if ($redirect) {
			if ($this->logged_in) {
				header('Location: /' . $this->cockpit_url . ILLEGAL_URL);
			} else {
				header('Location: /' . $this->cockpit_url . LOGIN_URL);
			}
		}

		return false;
	}

	private	function create_salt()
	{

		$Allowed_Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
		$Chars_Len = 63;
		$Salt_Length = 21;

		$salt = "";
		for ($i = 0; $i < $Salt_Length; $i++) {
			$salt .= $Allowed_Chars[mt_rand(0, $Chars_Len)];
		}
		return $salt;
	}

	public 	function add_user($email, $password, $firstname, $lastname, $roles)
	{

		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$email 			= mysqli_real_escape_string($db, $email);
		$password 		= mysqli_real_escape_string($db, $password);
		$firstname 		= mysqli_real_escape_string($db, $firstname);
		$lastname 		= mysqli_real_escape_string($db, $lastname);

		$salt = $this->create_salt();

		$bcrypt_salt = $this->blowfish_pre . $salt . $this->blowfish_end;

		$hashed_password = crypt($password, $bcrypt_salt);


		$db->query("INSERT INTO `users`
								(reg_date, email, salt, password, firstname, lastname)
					VALUES		(NOW()
								,AES_ENCRYPT('$email','$salt')
								,'$salt'
								,'$hashed_password'
								,'$firstname'
								,'$lastname')
								") or die(mysqli_error($db));
		$last_id = mysqli_insert_id($db);

		if (count($roles) > 0) {

			foreach ($roles as $key => $value) {

				$db->query("INSERT INTO 	user_role
											(user_id, role_id)
								VALUES		(" . $last_id . "
											," . mysqli_real_escape_string($db, $value) . ")
								");
			}
		}
	}

	public  function update_user($user_id, $email, $password, $firstname, $lastname, $roles)
	{
		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$email 			= mysqli_real_escape_string($db, $email);
		$firstname 		= mysqli_real_escape_string($db, $firstname);
		$lastname 		= mysqli_real_escape_string($db, $lastname);

		$password_query = '';
		if ($password != '') {

			$result = $db->query("SELECT salt FROM users WHERE id = " . mysqli_real_escape_string($db, $user_id));
			$row = mysqli_fetch_array($result);
			$salt = $row['salt'];

			$bcrypt_salt = $this->blowfish_pre . $salt . $this->blowfish_end;
			$hashed_password = crypt($password, $bcrypt_salt);

			$password_query = ", password = '$hashed_password' ";
		}

		$db->query("UPDATE			users
						SET			email 		= AES_ENCRYPT('$email',salt),
									firstname 	= '$firstname',
									lastname	= '$lastname'
									$password_query
						WHERE		id = " . $user_id . "
						");


		$db->query("DELETE FROM `user_role` WHERE user_id=" . $user_id);
		if (count($roles) > 0) {

			foreach ($roles as $key => $value) {

				$db->query("INSERT INTO 	user_role
											(user_id, role_id)
								VALUES		(" . $user_id . "
											," . mysqli_real_escape_string($db, $value) . ")
								");
			}
		}
	}

	public	function delete_user($user_id)
	{

		if (!is_numeric($user_id) || $this->id == $user_id) return false;

		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$db->query("DELETE FROM `users` WHERE id=" . $user_id);
		$db->query("DELETE FROM `user_role` WHERE user_id=" . $user_id);

		return true;
	}


	public 	function send_password_email($email, $from, $reply, $website, $password, $url = 'http:\\standaard')
	{

		require_once './swift/swift_required.php';

		$to = $email;
		$subject = 'Wachtwoord ' . $_SERVER['SERVER_NAME'];

		$bound_text = "----*%$!$%*";
		$bound = "--" . $bound_text . "\r\n";
		$bound_last = "--" . $bound_text . "--\r\n";

		$transport = Swift_SmtpTransport::newInstance('37.97.180.164', 25)
			->setUsername('info@spinnerz.nl')
			->setPassword('f7I1_vs9');

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance('Wachtwoord ' . $_SERVER['SERVER_NAME']);
		$message->setFrom(strip_tags($from));

		$MESSAGE_BODY =	'
				<html><body>
				<div Style="align:center;">
				</div>
				</br>
				<div style=" height="40" align="left">

				<font size="3" color="#000000" style="text-decoration:none;font-family:Monospace">
				<div class="info" Style="align:left;">
				<p>Beste,</p>
				<p>Hierbij sturen wij het wachtwoord om in te loggen op ' . $_SERVER['SERVER_NAME'] . '.</p>

				<br>

				<p>URL: ' . $website . '</p>
				<p>Gebruikersnaam: ' . $email . '</p>
				<p>Wachtwoord:   ' . $password . '   </p>

				</div>

				</br>
				<p>-----------------------------------------------------------------------------------------------------------------</p>
				</br>
				<p>( This is an automated message, please do not reply to this message, if you have any queries please contact ' . strip_tags($from) . ' )</p>
				</font>
				</div>
				</body></html>
			';

		$message->setTo($to);
		$message->setBody($MESSAGE_BODY, 'text/html');

		$result = $mailer->send($message);

		$send = true;
	}
}
