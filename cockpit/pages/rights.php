<?php
$user->check_right('rights_edit', true);

$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);


if (isset($_POST['save'])) {

	$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

	$db->query("TRUNCATE TABLE role_right");
	foreach($_POST['rights'] AS $value) {

		$role_right = explode('|',$value);
		$db->query("INSERT INTO 			role_right
											(right_id, role_id)
						VALUES 				($role_right[0], $role_right[1])");


	}
	header('Location: index.php?page=rights');
	die();
}

function get_role_right($role, $right) {
	$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

	$result = $db->query("SELECT id FROM role_right WHERE role_id = ".$role." AND right_id = ".$right);
	return (mysqli_num_rows($result) >= 1) ? true : false;
}

$roles = $db->query("select 			id,
										description
						FROM 			roles
						ORDER BY 		id ASC
						");

$rights = $db->query("select 			id,
										description,
										group_id
						FROM 			rights
						ORDER BY 		group_id, description ASC
						");
$role_array = array();
?>
<p class="bewerken well well-sm">Rechten</p>
<p>Onderstaand een overzicht van de rechten.</p>

<form action="" method="post">
<input type="hidden" name="save" value="1" />
<table class="table table-hover ">
	<thead>
		<tr>
			<td>&nbsp;</td>
			<?php while ($row = mysqli_fetch_array($roles)) {
					$role_array[] = $row['id']; ?>

					<td><?=$row['description']; ?></td>
			<? } ?>
		</tr>
	</thead>
	<tbody>
			<?php
			$group_id = 1;
			$first = false;
			while ($row = mysqli_fetch_array($rights)) {

				if ($group_id != $row['group_id'] || $first == false) {

					echo "<tr><td><strong>".$row['description']."</strong></td>";

					for ($i=0; $i < count($role_array); $i++) {

						echo "<td></td>";
					}

					echo "</tr>";

				}
				$group_id = $row['group_id'];
				$first = true;

			?>
				<tr style='background-color:white;'>
					<td><?=$row['description']; ?></td>

						<?php for ($i=0; $i < count($role_array); $i++) { ?>
							<td align="center">
								<input type="checkbox" name="rights[]" value="<?=$row['id'] . '|' . $role_array[$i]; ?>" <? if (get_role_right($role_array[$i], $row['id']) || $role_array[$i] == 5) echo ' CHECKED '; ?> <? if ($role_array[$i] == 5) echo " DISABLED"; ?>/>
								<? if ($role_array[$i] == 5) { ?>
									<input type="hidden" name="rights[]" value="<?=$row['id'] . '|' . $role_array[$i]; ?>" />
								<? } ?>
								</td>
						<? } ?>

				</tr>
			<? } ?>

	</tbody>
<table>
<br>
<button type="submit" class="btn btn-medium btn-success" onclick="window.location='index.php?page=rights';">
	<i class="fa fa-check"> Opslaan</i>
</button>
</form>
