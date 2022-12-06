<?
defined('CUSTOMPAGES') or die('No direct script access alllowed');
global $user;

$devURL = "https://devtest.nannedijkstra.nl/cockpit/";

if (isset($_GET['del'])) {

	$this->delete();
}

if (isset($_GET['cid'])) {

	$this->copy_item();
}

if (isset($_GET['active']) && intval($_GET['id']) > 0) {

	$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

	$id = mysqli_real_escape_string($db, $_GET['id']);
	$active = mysqli_real_escape_string($db, $_GET['active']);
	if ($active == 0 || $active == 1) {

		$db->query("UPDATE custom_pages SET active=$active WHERE main_id=$id");
		header('Location: /cockpit/index.php?page='.$_GET['page']);
	}
}

$this->fetch('',true, false);
if ($GLOBALS['showpageorder'] 	!= false) $this->sort($GLOBALS['showpageorder']);
if ($GLOBALS['showpagereverse'] != false) $this->reverse();
$result = $this->page_results;

?>

<p class="bewerken well well-small">Overzicht <?=$this->page; ?></p>

<? if (isset($_GET['saved'])) { ?>

<div class="alert alert-success" role="alert">De wijziging is opgeslagen.</div>

<? } ?>
<? if (isset($_GET['event_posted'])) { ?>

	<div class="alert alert-success" role="alert">Het evenement is gepost.</div>

<? } ?>

<p>Onderstaand een overzicht van de <?=$this->page; ?>.</p>

	<div class="row">

		<div class="col-xs-12">
			<ul class="sortable_list_head bold col-xs-12" style="padding-left: 0;">
				<li class="col-xs-1">&nbsp;</li>
				<?
					foreach ($this->fields as $field => $values) {
						if (!$this->getOption($values[2], 'hide_overview')) {
							$overview_width = ($this->getOption($values[2], 'overview_width') > 0) ? floor(intval($this->getOption($values[2], 'overview_width')) / 5) : 10;
				?>
							<li <?=($this->getFieldClassName($field)!='') ? '' : ' style="width:'.$overview_width.'%;"'; ?> class="<?=$this->getFieldClassName($field); ?>">
								<?=$values[0]; ?><? if ($this->getOption($values[2], 'required')) echo '*'; ?>
							</li>
				<?
						}
					}
				?>
				<li class="col-xs-3 pull-right">&nbsp;</li>
			</ul>
		</div>
		<ul id="album_list" class="list-inline sortable_list_main2 col-xs-12">
		<?php foreach ($result as $row) { ?>

			<li id="album_<?=$row['id']; ?>" class="col-xs-12">
				<ul class="sortable_list_inner" style="padding-left: 0;">
					<li class="col-xs-1">
						<a href="index.php?page=<?=$_GET['page']; ?>&active=<? echo ($row['active'] == true) ? '0' : '1'; ?>&id=<?php echo $row['id']; ?>">
							<img src="/cockpit/images/traffic-light-<? echo ($row['active'] == true) ? 'green' : 'red'; ?>.png" />
						</a>
					</li>
					<?
						foreach ($this->fields as $field => $values) {
							$overview_width = ($this->getOption($values[2], 'overview_width') > 0) ? floor(intval($this->getOption($values[2], 'overview_width')) / 5) : 10;
							if (!$this->getOption($values[2], 'hide_overview')) {
					?>
						<li <?=($this->getFieldClassName($field)!='') ? '' : ' style="width:'.$overview_width.'%;"'; ?> class="<?=$this->getFieldClassName($field); ?>">
							<?
								if ($values[1] == 'image') {
									if (isset($row[$field])) echo '<img src="'.UPLOAD_PATH_CUSTOM.$this->page.'/thumb/'.$row[$field].'" width="25px" height="25px />"';
								} elseif ($values[1] == 'date')	{
									echo ($row[$field] == '1970-01-01' || !$row[$field]) ? '-': date('d-m-Y', strtotime($row[$field]));
								} elseif ($values[1] == 'icon')	{
									echo (!$row[$field]) ? '-': '<i class="' . $row[$field] . '"></i>';
								} else {
									echo $row[$field];
								}
							?>
						</li>
					<?
							}
						}
					?>
					<li class="col-xs-6 col-md-3 pull-right">

						<?
							if (!empty($this->buttons)) {
								foreach ($this->buttons as $name => $params) {
									if (count($params) == 3) {
						?>
									<a class="btn btn-mini <?=$params[0]; ?>" onClick="<?=$params[2]; ?>(<?=$row['id'];?>); return false;" href="#"><?=$params[1]; ?></a>
						<?
									}
								}
							}
						?>
						<?
						if ($this -> facebook) {
                            $button_color = (isset($row['facebook_send']) && $row['facebook_send'] == 1) ? 'green' : 'default'; ?>
							<a class="btn btn-mini btn-<?=$button_color; ?>" href="/cockpit/index.php?page=facebook&id=<?=$row['id'];?>" data-toggle="tooltip" data-placement="top" title="Klik hier om dit item te posten"><i class="fa fa-fw fa-facebook"></i></a>
						<? } ?>
						<? if ($this->copy_items || in_array(5, $user->roles)) { ?>
							<a class="btn btn-mini btn-default" href="index.php?page=<?=$this->page; ?>&cid=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Klik hier om dit item te kopiëren"><i class="fa fa-fw fa-files-o"></i></i></a>
						<? } ?>
						<? if ($this->edit_items || in_array(5, $user->roles)) { ?>
							<a class="btn btn-mini btn-default" href="index.php?page=<?=$this->page; ?>&mode=edit&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Klik hier om dit item te bewerken"><i class="fa fa-pencil"></i></a>
						<? } ?>
						<? if ($this->delete_items || in_array(5, $user->roles)) { ?>
							<a class="btn btn-mini btn-danger" style="color:white;" onclick="return confirm('Weet u zeker dat u dit item wilt verwijderen?');" href="index.php?page=<?=$this->page; ?>&del=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Klik hier om dit item te verwijderen"><i class="fa fa-trash-o "></i></a>
						<? } ?>
					</li>
				</ul>
			</li>
		<? } ?>
		</ul>
	</div>
<br>
<?  if ($this->add_items || in_array(5, $user->roles)) { ?>
	<a class="btn btn-medium btn-success" onclick="window.location='index.php?page=<?=$this->page; ?>&mode=edit';">
		<i class="icon-plus"> Voeg item toe</i>
	</a>

<? } ?>



<? if ($this->excel_export) { ?>
     <a class="btn btn-mini btn-default" href="???" data-toggle="tooltip" data-placement="top" title="Klik hier om de items te exporteren"><i class="fa fa-fw fa-pencil"></i> Excel export</a>
<? } ?>


<? if ($this->order_select || in_array(5, $user->roles)) { ?>
	 <script type="text/javascript">
		$(document).ready(function(){

			$(function() {
				$("#album_list").sortable({ opacity: 0.6, cursor: 'move', update: function() {
					var order = $(this).sortable("serialize") + '&action=updateAlbums';
					$.post("ajax/custom_page_list_order_update.php", order, function(theResponse){
						console.log(theResponse);
					});
				}
				});
			});

		});
	</script>
<? } ?>
