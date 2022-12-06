<?php
$user->check_right('users', true);

if (isset($_POST['save'])) {

	if ($_GET['action'] == 'add' && $user->check_right('users_add')) {

		$user->add_user($_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], $_POST['roles']);
	} elseif ($user->check_right('users_edit')) {

		$user->update_user($_GET['id'], $_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], $_POST['roles']);
	}
	if (isset($_POST['password_send']) && isset($_POST['email']) && $_POST['email'] != '' && isset($_POST['password']) && $_POST['password'] != '') {
		$roles = $_POST['roles'];
		print_r($roles);
		if (in_array(6, $roles)) {

			$user->send_password_email($_POST['email'], 'info@spinnerz.nl', 'info@spinnerz.nl', '' . $_SERVER['HTTP_HOST'] . '/intranet', $_POST['password']);
		} else {

			$user->send_password_email($_POST['email'], 'info@spinnerz.nl', 'info@spinnerz.nl', '' . $_SERVER['HTTP_HOST'] . '/cockpit', $_POST['password']);
		}
	}

	header('Location: index.php?page=users');
	die();
}

// if (isset($_POST['save'])) {
//
// 	if ($_GET['action'] == 'add' && $user->check_right('users_add')) {
//
// 		$user->add_user($_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], $_POST['roles']);
// 	} elseif ($user->check_right('users_edit')) {
//
// 		$user->update_user($_GET['id'], $_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], $_POST['roles']);
// 	}
//
// 	header('Location: index.php?page=users');
// 	die();
// }

if (isset($_GET['del']) && intval($_GET['del']) > 0 && $user->check_right('users_delete')) {

	$user->delete_user($_GET['del']);
	header('Location: /cockpit/index.php?page=users');
}

if (!isset($_GET['action'])) {

	$result = $db->query("select 			id,
	AES_DECRYPT(email,salt) AS email,
	firstname,
	lastname
	FROM 			users
	ORDER BY 		id ASC
	");
?>
	<p class="bewerken well well-small">Gebruikers</p>

	<p>Onderstaand een overzicht van de gebruikers.</p>

	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<!-- 					<td>ID</td> -->
				<td>Voornaam</td>
				<td>Achternaam</td>
				<td>Email</td>
				<td>&nbsp;</td>
			</tr>
		</thead>
		<tbody>
			<?php while ($row = mysqli_fetch_array($result)) { ?>

				<tr>
					<!-- 				<td><?= $row['id']; ?></td> -->
					<td><?= $row['firstname']; ?></td>
					<td><?= $row['lastname']; ?></td>
					<td><?= $row['email']; ?></td>
					<td>
						<?php if ($user->check_right('users_edit')) { ?>
							<a class="btn btn-mini btn-default" href="index.php?page=users&action=edit&id=<?php echo $row['id']; ?>" title="Klik hier om deze gebruiker te bewerken"><i class="fa fa-pencil"></i></a>
						<?php } ?>
						<?php if ($user->check_right('users_delete')) { ?>
							<a class="btn btn-mini btn-danger" style="color:white;" onclick="return confirm('Weet u zeker dat u deze gebruiker wilt verwijderen?');" href="index.php?page=users&del=<?php echo $row['id']; ?>" title="Klik hier om deze gebruiker te verwijderen"><i class="fa fa-trash-o "></i></a>
						<?php } ?>
					</td>
				</tr>

			<? } ?>
		</tbody>
		<table class="table">
			<br>
			<?php if ($user->check_right('users_add')) { ?>
				<a class="btn btn-medium btn-success" onclick="window.location='index.php?page=users&action=add';">
					<i class="icon-plus"> Voeg gebruiker toe</i>
				</a>
			<?php } ?>

		<? } elseif ($_GET['action'] == 'add' or $_GET['action'] == 'edit') {

		if (isset($_GET['id']) && intval($_GET['id'])) {


			$result = $db->query("select 			id,
				AES_DECRYPT(email,salt) AS email,
				firstname,
				lastname
				FROM 			users
				WHERE			id = " . mysqli_real_escape_string($db, $_GET['id']) . "
				LIMIT			1
				");
			$row = mysqli_fetch_array($result);
		}

		$id = ($_GET['id'] > 0) ? $_GET['id'] : 0;
		$roles = $db->query("SELECT				DISTINCT r.id,
				r.description,
				ur.id	AS active
				FROM			roles 		AS r
				LEFT JOIN		user_role 	AS ur
				ON				r.id = ur.role_id
				AND			ur.user_id = $id
				ORDER BY		r.id ASC
				");

		?>
			<p class="bewerken well well-small">Gebruikers</p>
			<p><?= ($_GET['action'] == 'add') ? 'Voeg een gebruiker toe.' : 'Bewerk een gebruiker.'; ?></p>

			<form action="" method="post" autocomplete="off">
				<input type="hidden" name="save" value="1" />
				<table class="table">
					<tbody>
						<tr>
							<td>Email</td>
							<td><input type="text" name="email" value="<?php echo (isset($row)) ? $row['email'] : ''; ?>" /></td>
						</tr>

						<tr>
							<td>Wachtwoord</td>
							<td>
								<input type="text" name="password" id="password" />
								<input type="button" onclick="Javascript: $('#password').val(Math.random().toString(36).slice(-10));" value="Genereer wachtwoord" /><br /><br />

								<input type="checkbox" name="password_send" /> Verstuur email naar gebruiker met daarin de inlog-gegevens
							</td>
						</tr>

						<tr>
							<td>Voornaam</td>
							<td><input type="text" name="firstname" value="<?php echo (isset($row)) ? $row['firstname'] : ''; ?>" /></td>
						</tr>
						<tr>
							<td>Achternaam</td>
							<td><input type="text" name="lastname" value="<?php echo (isset($row)) ? $row['lastname'] : ''; ?>" /></td>
						</tr>
						<tr>
							<td>Rollen</td>
							<td>
								<?php while ($role = mysqli_fetch_array($roles)) { ?>
									<input type="checkbox" name="roles[]" value="<?= $role['id']; ?>" <?php if (isset($row) && $role['active'] > 0) echo 'CHECKED'; ?> />&nbsp;
									<?= $role['description']; ?><br />
								<? } ?>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<a href="/cockpit/index.php?page=users" class="btn btn-medim btn-default">
									<i class="icon-err"> Annuleren</i>
								</a>
								&nbsp;
								<button type="submit" class="btn btn-medim btn-success">
									<i class="icon-check"> Opslaan</i>
								</button>

							</td>
						</tr>
					</tbody>
				</table>
			</form>

		<? } ?>