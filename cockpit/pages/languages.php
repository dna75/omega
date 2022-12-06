<?php
define('devURL', $urldevelop);


$user->check_right('languages', true);

$languageArray = array(	'nl' => 'Nederlands',
						'en' => 'Engels',
						'du' => 'Duits',
						'sp' => 'Spaans',
						'ar' => 'Arabisch',
						'po' => 'Portugees',
						'ja' => 'Japans',
						'ch' => 'Chinees',
						'fr' => 'Frans',
						'tu' => 'Turks',
						'it' => 'Italiaans',
						'fs' => 'Fries');


if (isset($_POST['save'])) {

	$code		= mysqli_real_escape_string($db, $_POST['code']);
	$language 	= $languageArray[$code];

	if ($_GET['action'] == 'add' && $user->check_right('languages_add')) {

		$db->query("INSERT INTO `languages` (code, language) VALUES ('".$code."','".$language."')");

	} elseif ($user->check_right('languages_edit')) {
/*
		$id			= intval($_GET['id']);

		$result = $db->query("select 			code
								FROM 			languages
								WHERE 			id=".$id."
								");
		$row = mysqli_fetch_array($result);

		echo "Code: " .$row['code'];

		$db->query("UPDATE `languages` 		SET code='".$code."', language='".$language."' WHERE id=".$id);
		$db->query("UPDATE `language_fields` 	SET language='".$code."' WHERE language='".$row['code']."'");
		$db->query("UPDATE `pagina` 			SET language='".$code."' WHERE language='".$row['code']."'");
		$db->query("UPDATE `menu` 				SET language='".$code."' WHERE language='".$row['code']."'");
*/
	}

	header('Location: index.php?page=languages');
	die();
}

if (isset($_GET['del']) && intval($_GET['del']) > 0 && $user->check_right('languages_delete')) {

	$id			= intval($_GET['del']);

	$result = $db->query("select 			code
							FROM 			languages
							WHERE 			id=".$id."
							");
	$row = mysqli_fetch_array($result);

	$db->query("DELETE FROM `languages` 		WHERE id=".$id);
	$db->query("DELETE FROM `language_fields` 	WHERE language='".$row['code']."'");
	$db->query("DELETE FROM `pagina` 			WHERE language='".$row['code']."'");
	$db->query("DELETE FROM `menu` 			WHERE language='".$row['code']."'");

	header('Location: /cockpit/index.php?page=languages');
	die();
}

if (isset($_GET['active']) && intval($_GET['id']) > 0 && $user->check_right('languages_edit')) {

	$id = mysqli_real_escape_string($db, $_GET['id']);
	$active = mysqli_real_escape_string($db, $_GET['active']);
	if ($active == 0 || $active == 1) {

		$db->query("UPDATE languages SET active=".$active." WHERE id=".$id);
		header('Location: /cockpit/index.php?page=languages');
		die();
	}
}

if (!isset($_GET['action'])) {

		$result = $db->query("select 			`id`,
												`code`,
												`language`,
												`active`,
												`default`
								FROM 			languages
								ORDER BY 		id ASC
								");
?>
		<p class="bewerken well well-small">Talen</p>
		<p>Onderstaand een overzicht van de talen.</p>

		<table class="table table-striped table-hover">
			<thead>
				<tr>
<!-- 					<td>ID</td> -->
					<td>Actief</td>
					<td>Taal</td>
					<td>Code</td>
					<td>Default</td>
					<td>&nbsp;</td>
				</tr>
			</thead>
			<tbody>
			<?php while ($row = mysqli_fetch_array($result)) { ?>

				<tr>
					<td>
						<? if ($row['default']) { ?>
							<img src="/cockpit/images/traffic-light-<? echo ($row['active'] == true) ? 'green' : 'red'; ?>.png" />
						<? } else { ?>
							<a href="index.php?page=languages&active=<? echo ($row['active'] == true) ? '0' : '1'; ?>&id=<?php echo $row['id']; ?>">
								<img src="/cockpit/images/traffic-light-<? echo ($row['active'] == true) ? 'green' : 'red'; ?>.png" />
							</a>
						<? } ?>
					</td>
					<td><?=$row['language']; ?></td>
					<td><?=$row['code']; ?></td>
					<td><?=($row['default']) ? 'Ja' : 'Nee'; ?></td>
					<td>
						<?php if ($user->check_right('languages_delete') && !$row['default']) { ?>
							<a class="btn btn-mini btn-danger" style="color:white;" onclick="return confirm('Weet u zeker dat u deze taal wilt verwijderen?');" href="index.php?page=languages&del=<?php echo $row['id']; ?>" title="Klik hier om deze taal te verwijderen"><i class="fa fa-trash-o "></i></a>
						<?php } ?>
					</td>
				</tr>

			<? } ?>
			</tbody>
		<table>
		<br>
		<?php if ($user->check_right('languages_add')) { ?>
			<a class="btn btn-medium btn-success" onclick="window.location='index.php?page=languages&action=add';">
				<i class="icon-plus"> Voeg taal toe</i>
			</a>
		<?php } ?>

<? } elseif ($_GET['action'] == 'add' or $_GET['action'] == 'edit') {


		$default = false;
		if (isset($_GET['id']) && intval($_GET['id'])) {

			$result = $db->query("select 			`id`,
													`code`,
													`default`
									FROM 			languages
									WHERE			id = " . mysqli_real_escape_string($db, $_GET['id']) . "
									LIMIT			1
									");
			$row = mysqli_fetch_array($result);
			$default = ($row['default']) ? true : false;

		}
?>
		<p class="bewerken well well-small">Talen</p>
		<p><?=($_GET['action'] == 'add') ? 'Voeg een taal toe.' : 'Bewerk een taal.'; ?></p>

		<form action="" method="post" autocomplete="off">
			<input type="hidden" name="save" value="1" />
			<table>
				<tbody>
					<tr>
						<td>&nbsp;</td>
						<td>
							<select name="code">
								<option value="xx">Kies een taal</option>
								<? foreach ($languageArray as $key => $value) { ?>

									<option value="<?=$key; ?>" <?=($key==$row['code']) ? ' SELECTED ' : ''; ?>><?=$value; ?></option>
								<? } ?>
							</select><br /><br />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<a href="/cockpit/index.php?page=languages" class="btn btn-medim btn-default">
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
