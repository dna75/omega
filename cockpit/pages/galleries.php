<?php

$user->check_right('galleries', true);

include_once(''.$develop.'/include/galleries.func.php');

define('devURL', $urldevelop);


function deleteDirectory($dir) {

    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
    }
    return rmdir($dir);
}

if (isset($_POST['save'])) {

	$name 			= mysqli_real_escape_string($db, $_POST['name']);
	$description	= mysqli_real_escape_string($db, $_POST['description']);
	$date			= mysqli_real_escape_string($db, $_POST['date']);

	$temp_date = explode("-", $date);
	$date = $temp_date[2] . '-' . $temp_date[1] . '-' . $temp_date[0];

	if ($_GET['action'] == 'add' && $user->check_right('galleries_add')) {

		$new_id =save_new_gallery($name, $description, $date);
		header('Location: index.php?page=gallery&id='.$new_id);

	} elseif ($user->check_right('galleries_edit')) {

		$id = mysqli_real_escape_string($db, $_GET['id']);
		update_gallery($id, $name, $description, $date);
		header('Location: index.php?page=galleries');
	}

}

if (isset($_GET['del']) && intval($_GET['del']) > 0 && $user->check_right('galleries_delete')) {

	$id = mysqli_real_escape_string($db, $_GET['del']);

	$album = $db->query("select 			*
							FROM 			amplo_galleries
							WHERE 			id = $id
							LIMIT			1
							");
	$album = mysqli_fetch_array($db, $album);

	$destpath = "../upload/gallery/" . date("Ymdhis" , strtotime($album['directory']));

	deleteDirectory($destpath);

	$db->query("DELETE FROM amplo_galleries WHERE id=$id");
	$db->query("DELETE FROM amplo_images WHERE album_id=$id");

	header('Location: /cockpit/index.php?page=galleries');
}

if (isset($_GET['active']) && intval($_GET['id']) > 0 && $user->check_right('galleries_edit')) {

	$id = mysqli_real_escape_string($_GET['id']);
	$active = mysqli_real_escape_string($_GET['active']);
	if ($active == 0 || $active == 1) {

		$db->query("UPDATE amplo_galleries SET active=$active WHERE id=$id");
		header('Location: /cockpit/index.php?page=galleries');
	}
}

if (!isset($_GET['action'])) {

		$result = $db->query("select 			*
								FROM 			amplo_galleries
								ORDER BY 		`order` ASC
								");

?>
		<p class="bewerken well well-md">Fotoalbums</p>
		<p>Onderstaand een overzicht van de foto-albums.</p>

		<div>
			<ul class="sortable_list_head bold">
				<li class="number">&nbsp;</li>
<!-- 				<li class="number">#</li> -->
				<li class="text200">Naam</li>
				<li class="date">Datum</li>
				<li class="options">&nbsp;</li>
			</ul>
			<ul id="album_list" class="list-inline sortable_list_main">
			<?php while ($row = mysqli_fetch_array($result)) { ?>

				<li id="album_<?=$row['id']; ?>;">
					<ul class="sortable_list_inner">
						<li class="number">
							<a href="index.php?page=galleries&active=<? echo ($row['active'] == true) ? '0' : '1'; ?>&id=<?php echo $row['id']; ?>">
								<img src="/cockpit/images/traffic-light-<? echo ($row['active'] == true) ? 'green' : 'red'; ?>.png" />
							</a>
						</li>
<!-- 						<li class="number"><?=$row['id']; ?></li> -->
						<li class="text200"><?=$row['name']; ?></li>
						<li class="date"><?=date("d-m-Y", strtotime($row['date'])); ?></li>
						<li class="options">
							<?php if ($user->check_right('galleries_edit')) { ?>
								<a class="btn btn-default btn-sm" href="index.php?page=galleries&action=edit&id=<?php echo $row['id']; ?>" title="Klik hier om de album gegevens te bewerken"><i class="fa fa-pencil"></i></a>
							<?php } ?>
							<?php if ($user->check_right('galleries_edit')) { ?>
								<a class="btn btn-default btn-sm" href="index.php?page=gallery&id=<?php echo $row['id']; ?>" title="Klik hier om de foto's te bewerken"><i class="fa fa-picture-o"></i></a>
							<?php } ?>
							<?php if ($user->check_right('galleries_delete')) { ?>
								<a class="btn btn-sm btn-danger" style="color:white;" onclick="return confirm('Weet u zeker dat u dit fotoalbum wilt verwijderen?');" href="index.php?page=galleries&del=<?php echo $row['id']; ?>" title="Klik hier om dit fotoalbum te verwijderen"><i class="fa fa-trash-o "></i></a>
							<?php } ?>
						</li>
					</ul>
				</li>
			<? } ?>
			</ul>
		</div>
		<br>
		<?php if ($user->check_right('galleries_add')) { ?>
			<a class="btn btn-medium btn-success" onclick="window.location='index.php?page=galleries&action=add';">
				<i class="fa fa-plus"> Voeg fotoalbum toe</i>
			</a>
		<?php } ?>

		 <script type="text/javascript">
			$(document).ready(function(){

				$(function() {
					$("#album_list").sortable({ opacity: 0.6, cursor: 'move', update: function() {
						var order = $(this).sortable("serialize") + '&action=updateAlbums';
						$.post("ajax/album_list_order_update.php", order, function(theResponse){
						});
					}
					});
				});

			});
		</script>

<? } elseif ($_GET['action'] == 'add' or $_GET['action'] == 'edit') {

		if (isset($_GET['id']) && intval($_GET['id'])) {

			$result = $db->query("select 			*
									FROM 			amplo_galleries
									WHERE			id = " . mysqli_real_escape_string($db, $_GET['id']) . "
									LIMIT			1
									");
			$row = mysqli_fetch_array($result);
		}
?>
		<p class="bewerken well well-small">Fotoalbum</p>
		<p><?=($_GET['action'] == 'add') ? 'Voeg een fotoalbum toe.' : 'Bewerk een fotoalbum.'; ?></p>

		<form action="" method="post" autocomplete="off">
			<input type="hidden" name="save" value="1" />
			<table class="table">
				<tbody>


					<tr>
						<td>Naam</td>
						<td><input type="text" class="form-control" name="name" value="<?php echo (isset($row)) ? $row['name'] : ''; ?>" /></td>
					</tr>
					<tr>
						<td>Omschrijving</td>
						<td><textarea name="description" class="form-control"><?php echo (isset($row)) ? $row['description'] : ''; ?></textarea></td>
					</tr>
					<tr>
						<td>Datum</td>
						<td><input type="text" name="date"  class="form-control datepicker" value="<?php echo (isset($row)) ? date("d-m-Y", strtotime($row['date'])) : ''; ?>" /></td>
					</tr>
					<tr>
						<td colspan="2">
							<a href="/cockpit/index.php?page=galleries" class="btn btn-medim btn-default">
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

<?php } ?>
