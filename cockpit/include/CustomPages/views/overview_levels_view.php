<?
defined('CUSTOMPAGES') or die('No direct script access alllowed');
global $user;

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

	<div>

		<ul class="sortable_list_head bold" style="padding-left:20px;">
			<?
				foreach ($this->fields as $field => $values) {
					if (!$this->getOption($values[2], 'hide_overview')) {
						$overview_width = ($this->getOption($values[2], 'overview_width') > 0) ? floor(intval($this->getOption($values[2], 'overview_width')) / 5) : 10;
			?>
						<li <?=($this->getFieldClassName($field) != '') ? '': ' style="width:'.$overview_width.'%;"'; ?> class="<?=$this->getFieldClassName($field); ?>">
							<?=$values[0]; ?><? if ($this->getOption($values[2], 'required')) echo '*'; ?>
						</li>
			<?
					}
				}
			?>
			<li class="options">&nbsp;</li>
		</ul>
		<ol id="album_list" class="album_list list-inline sortable_list_main">
			<? $this->get_items(0); ?>
		</ol>
	</div>
<br>
<? if ($this->add_items || in_array(5, $user->roles)) { ?>
	<? if ($this->add_items) { ?>
		<a class="btn btn-medium btn-success" onclick="window.location='index.php?page=<?=$this->page; ?>&mode=edit';">
			<i class="icon-plus"> Voeg item toe</i>
		</a>
	<? } ?>
<? } ?>
<? if ($this->order_select) { ?>

	<script type="text/javascript" src="/cockpit/scripts/nested_sortable.js"></script>
	<script type="text/javascript">

		$(document).ready(function(){

			$(function() {

				$('.album_list').nestedSortable({
					handle: 'div',
					items: 'li',
					toleranceElement: '> div',
					maxLevels: <?=$this->maxLevels; ?>,
					update: function() {
						order = $(this).nestedSortable('serialize') + '&action=updateAlbums';
						$.post("ajax/custom_page_levels_list_order_update.php", order, function(theResponse){
							var items = $('#album_list').children('li');
							check_children(items);
						});
					}
				});
			});

			function check_children(items) {

				//console.log(items);
				$.each( items, function( key, value ) {
					var ols = $(value).children('ol');
					var id = value.id.replace('album_','');
					console.log(ols);
					if (ols.length > 0 && ols.children('li').length > 0) {
						$('#levelSwitch'+id).css('display', 'inline');
						$('#levelSwitchDummy'+id).css('display', 'none');
						$('#deleteButton'+id).css('display', 'none');
						check_children(ols.children('li'));
					} else {
						//console.log(id);
						$('#levelSwitch'+id).css('display', 'none');
						$('#levelSwitchDummy'+id).css('display', '');
						$('#deleteButton'+id).css('display', '');
					}
				});



			}
		});

	</script>

<? } ?>
