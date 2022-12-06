<?php
$user->check_right('languages', true);


if (isset($_POST['save'])) {

	$slug 		= mysqli_real_escape_string($db, $_POST['slug']);

	if ($_GET['action'] == 'add' && $user->check_right('languages_add')) {

		$result = $db->query("select 			`code`
								FROM 			languages
								") or die(mysqli_error($db));

		while ($row = mysqli_fetch_array($result)) {

			$field_text 	= mysqli_real_escape_string($db, $_POST['text_'.$row['code']]);
			$field_string 	= mysqli_real_escape_string($db, $_POST['string_'.$row['code']]);

			$db->query("INSERT INTO 		`language_fields`
											(slug, language, field_text, field_string)
							VALUES 			('".$slug."','".$row['code']."', '".$field_text."', '".$field_string."')
							") or die(mysqli_error($db));
		}

	} elseif ($user->check_right('languages_edit')) {

		$old_slug = mysqli_real_escape_string($db, $_POST['old_slug']);

		$result = $db->query("select 			`code`
								FROM 			languages
								") or die(mysqli_error($db));

		while ($row = mysqli_fetch_array($result)) {

			$field_text 	= mysqli_real_escape_string($db, $_POST['text_'.$row['code']]);
			$field_string 	= mysqli_real_escape_string($db, $_POST['string_'.$row['code']]);

			// check if the language field exists
			$temp = "SELECT 			id
						FROM			`language_fields`
						WHERE			slug = '".$old_slug."'
						AND				language = '".$row['code']."'
										";
			$temp_r	= $db->query($temp) or die ( mysqli_error($db) );

			if (mysqli_num_rows($temp_r) == 0) {

				$db->query("INSERT INTO 		`language_fields`
												(slug, language, field_text, field_string)
								VALUES 			('".$slug."','".$row['code']."', '".$field_text."', '".$field_string."')
								") or die(mysqli_error($db));

			} else {

				$db->query("UPDATE		 		`language_fields`
								SET				slug = '".$slug."',
												language = '".$row['code']."',
												field_text = '".$field_text."',
												field_string = '".$field_string."'
								WHERE			slug = '".$old_slug."'
								AND				language = '".$row['code']."'
								") or die(mysqli_error($db));
			}
		}

	}
	header('Location: index.php?page=languagefields');
	die();

}

if (isset($_GET['del']) && $user->check_right('languages_edit')) {


	$db->query("DELETE FROM 	`language_fields`
					WHERE 		id='" . mysqli_real_escape_string($db, $_GET['del']) . "'
					");

	header('Location: /cockpit/index.php?page=languagefields');
	die();
}

if (!isset($_GET['action'])) {

		$result = $db->query("select 			`id`,
												`slug`
								FROM 			language_fields
								WHERE			language = 'nl'
								ORDER BY 		slug ASC
								") or die(mysqli_error($db));
?>
		<p class="bewerken well well-small">Taalvelden</p>
		<p>Onderstaand een overzicht van de taalvelden.</p>

		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<td>Slug</td>
					<td>&nbsp;</td>
				</tr>
			</thead>
			<tbody>
			<?php while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { ?>

				<tr>
					<td><?=$row['slug']; ?></td>
					<td>
						<a class="btn btn-mini btn-default" href="index.php?page=languagefields&action=edit&slug=<?=$row['slug']; ?>" title="Klik hier om dit taalveld te bewerken"><i class="fa fa-pencil"></i></a>
						<a class="btn btn-mini btn-danger" style="color:white;" onclick="return confirm('Weet u zeker dat u dit taalveld wilt verwijderen?');" href="index.php?page=languagefields&del=<?php echo $row['id']; ?>" title="Klik hier om deze gebruiker te verwijderen"><i class="fa fa-trash-o "></i></a>
					</td>
				</tr>

			<? } ?>
			</tbody>
		<table>
		<br>
		<?php if ($user->check_right('languages_add')) { ?>
			<a class="btn btn-medium btn-success" onclick="window.location='index.php?page=languagefields&action=add';">
				<i class="icon-plus"> Voeg taalveld toe</i>
			</a>
		<?php } ?>

<? } elseif ($_GET['action'] == 'add' or $_GET['action'] == 'edit') {

		$default = false;
		if (isset($_GET['slug'])) {

			$result = $db->query("SELECT 			`slug`,
													`language`
									FROM 			language_fields
									WHERE			slug = '" . mysqli_real_escape_string($db, $_GET['slug']) . "'
									LIMIT			1
									");
			//$row = mysqli_fetch_array($result);
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
?>
		<p class="bewerken well well-small">Taalvelden</p>
		<p><?=($_GET['action'] == 'add') ? 'Voeg een taalveld toe.' : 'Bewerk een taalveld.'; ?></p>

		<form action="" method="post" autocomplete="off">
			<input type="hidden" name="save" value="1" />
			<input type="hidden" name="old_slug" value="<?=$row['slug']; ?>" />
			<table class="table">
				<tbody>
					<tr>
						<td>Slug</td>
						<td><input type="text" name="slug" value="<?=($row['slug']=='') ? '' : $row['slug']; ?>" /></td>
					</tr>

						<?

						if (isset($_GET['slug'])) {

						$result2 = $db->query("SELECT 			language,
																code
												FROM 			languages
												ORDER BY		id
												") or die(mysqli_error($db));

						} else {
							$result2 = $db->query("SELECT 			language,
																	code
													FROM 			languages
													ORDER BY		id
													") or die(mysqli_error($db));

						}
						while ($row2 = mysqli_fetch_array($result2)) {

						if (isset($_GET['slug'])) {

							$result3 = $db->query("SELECT 			l.language,
																	l.code,
																	lf.slug,
																	lf.field_text,
																	lf.field_string
													FROM 			languages l
													LEFT JOIN		language_fields lf
													ON				l.code = lf.language
													WHERE			lf.slug = '" . mysqli_real_escape_string($db, $_GET['slug']) . "'
													AND				l.language = '" . $row2['language'] . "'
													ORDER BY		l.id
													") or die(mysqli_error($db));
							$row3 = mysqli_fetch_array($result3);
						}

						?>

						<tr>
							<td>&nbsp;</td>
							<td><strong><?=($row2['language'] != '') ? $row2['language'] : ''; ?></strong></td>
						</tr>
						<tr>
							<td>String</td>
							<td><input type="text" name="string_<?=$row2['code']; ?>" value="<?=($row3['field_string']=='') ? '' : $row3['field_string']; ?>" /></td>
						</tr>
						<tr>
							<td>Text</td>
							<td><textarea name="text_<?=$row2['code']; ?>"><?=($row3['field_text']=='') ? '' : $row3['field_text']; ?></textarea>
						</tr>


						<? } ?>

					<tr>
						<td colspan="2">
							<a href="/cockpit/index.php?page=languagefields" class="btn btn-medim btn-default">
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
