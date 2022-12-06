<?
defined('CUSTOMPAGES') or die('No direct script access alllowed');

if (isset($_POST['save'])) {

	$id = $this->save(true);
	if ($this->success_url != false)
		header("Location: " . $this->success_url);
	else
		header("Location: ../cockpit/index.php?page=" . $this->page . "&saved=1");
}

function show_field(&$validation, $page, $result, $field, $values, $language = '', $multilingual = false, $required = false, $hidden = false, $hide_editor = false, $icons = array())
{

	$fieldName 	= ($multilingual) ? $field . '_' . $language : $field;
	$fieldValue	= isset($result[$field . '_' . $language]['value']) ? $result[$field . '_' . $language]['value'] : '';
	$hidden		= ($hidden) ? ' hidden ' : '';

	switch ($values[1]) {

		case 'textarea':
			if ($required) $validation .= " validation += validateRequired('" . $fieldName . "','textarea'); ";
?>
			<div class="form-group <?= $hidden; ?>">
				<label for="<?= $fieldName; ?>" id="<?= $fieldName; ?>_label" class="col-sm-3 control-label">
					<?= $values[0]; ?><? if ($required) echo '*'; ?>
					<?= (isset($values[4]) && $values[4] != '') ? ' <btn class="btn btn-xs btn-default"><i class="fa fa-fw fa-info" data-toggle="tooltip" data-placement="bottom" title="' . $values[4] . '"></i></btn> ' : ''; ?>
				</label>

				<div class="col-sm-9">
					<textarea class="<?= $fieldName; ?>" name="<?= $fieldName; ?>" id="<?= $fieldName; ?>" <? if ($hide_editor) { ?> rows="10" cols="100" <? } ?>><?= (isset($fieldValue)) ? $fieldValue : ''; ?></textarea>

					<? if (!$hide_editor) { ?>


						<script type="text/javascript">
							CKEDITOR.replace('<?= $fieldName; ?>', {
								width: 'auto',
							});
						</script>
					<? } ?>
				</div>
			</div>
		<?
			break;
		case 'boolean':
			if ($required) $validation .= " validation += validateRequired('" . $fieldName . "','boolean'); ";
		?>
			<div class="form-group <?= $hidden; ?>">
				<label for="<?= $fieldName; ?>" id="<?= $fieldName; ?>_label" class="col-sm-3 control-label">
					<?= $values[0]; ?><? if ($required) echo '*'; ?>
					<?= (isset($values[4]) && $values[4] != '') ? ' <btn class="btn btn-xs btn-default"><i class="fa fa-fw fa-info" data-toggle="tooltip" data-placement="bottom" title="' . $values[4] . '"></i></btn>' : ''; ?>
				</label>
				<div class="col-sm-9">
					<input type="checkbox" class="myinput large" name="<?= $fieldName; ?>" id="<?= $fieldName; ?>" <?= ($fieldValue == 1) ? ' checked="checked" ' : ''; ?> </div>
				</div>
			</div>
		<?
			break;

		case 'time':
			if ($required) $validation .= " validation += validateRequired('" . $fieldName . "','boolean'); ";
		?>
			<div class="form-group <?= $hidden; ?>">
				<label for="<?= $fieldName; ?>" id="<?= $fieldName; ?>_label" class="col-sm-3 control-label">
					<?= $values[0]; ?><? if ($required) echo '*'; ?>
					<?= (isset($values[4]) && $values[4] != '') ? ' <btn class="btn btn-xs btn-default"><i class="fa fa-fw fa-info" data-toggle="tooltip" data-placement="bottom" title="' . $values[4] . '"></i></btn>' : ''; ?>
				</label>
				<div class="col-sm-9">
					<input type="text" class="form-control time" name="<?= $fieldName; ?>" id="<?= $fieldName; ?>" data-inputmask="'alias': 'time'" value="<?= $fieldValue; ?>" />
				</div>
			</div>

		<?
			break;

		case 'select':
			if ($required) $validation .= " validation += validateRequired('" . $fieldName . "','boolean'); ";
		?>
			<div class="form-group <?= $hidden; ?>">
				<label for="<?= $fieldName; ?>" id="<?= $fieldName; ?>_label" class="col-sm-3 control-label">
					<?= $values[0]; ?><? if ($required) echo '*'; ?>
					<?= (isset($values[4]) && $values[4] != '') ? ' <btn class="btn btn-xs btn-default"><i class="fa fa-fw fa-info" data-toggle="tooltip" data-placement="bottom" title="' . $values[4] . '"></i></btn>' : ''; ?>
				</label>
				<div class="col-sm-9">
					<select class="form-control" name="<?= $fieldName; ?>" id="<?= $fieldName; ?>">
						<?
						$options = explode('|', $values[3]);
						foreach ($options as $key) {
						?>
							<option value="<?= $key; ?>" <?= ($fieldValue == $key) ? ' selected ' : ''; ?>><?= $key; ?></option>
						<?
						}
						?>
					</select>
				</div>
			</div>
		<?
			break;
		case 'date':
			if ($required) $validation .= " validation += validateRequired('" . $fieldName . "','text'); ";
		?>
			<div class="form-group <?= $hidden; ?>">
				<label for="<?= $fieldName; ?>" id="<?= $fieldName; ?>_label" class="col-sm-3 control-label">
					<?= $values[0]; ?><? if ($required) echo '*'; ?>
					<?= (isset($values[4]) && $values[4] != '') ? ' <btn class="btn btn-xs btn-default"><i class="fa fa-fw fa-info" data-toggle="tooltip" data-placement="bottom" title="' . $values[4] . '"></i></btn>' : ''; ?>
				</label>
				<div class="col-sm-9">
					<input type="text" class="form-control datepicker hasdatepicker" name="<?= $fieldName; ?>" id="<?= $fieldName; ?>" value="<?= ($fieldValue != '') ? date("d-m-Y", strtotime($fieldValue)) : ''; ?>" />
				</div>
			</div>
		<?
			break;
		case 'image':
			if ($required) $validation .= " validation += validateRequired('" . $fieldName . "','image', '" . $fieldValue . "'); ";
		?>
			<div class="form-group <?= $hidden; ?>">
				<label for="<?= $fieldName; ?>" id="<?= $fieldName; ?>_label" class="col-sm-3 control-label">
					<?= $values[0]; ?><? if ($required) echo '*'; ?>
					<?= (isset($values[4]) && $values[4] != '') ? ' <btn class="btn btn-xs btn-default"><i class="fa fa-fw fa-info" data-toggle="tooltip" data-placement="bottom" title="' . $values[4] . '"></i></btn>' : ''; ?>
				</label>
				<div class="col-sm-9">
					<input type="file" class="form-control" name="<?= $fieldName; ?>" id="<?= $fieldName; ?>" />
					<? if ($fieldValue != '') { ?>
						<label>Huidige afbeelding:</label><br />
						<img src="<?= UPLOAD_PATH_CUSTOM . $page; ?>/thumb/<?= $fieldValue; ?>" />
						<br />Bestand: '<?= $fieldValue; ?>'<br /><br />
						<input name="<?= $fieldName; ?>_remove_image" id="<?= $fieldName; ?>_remove_image" type="checkbox" /> Verwijder afbeelding
					<? } ?>
				</div>
			</div>
		<?
			break;
		case 'icon':
			if ($required) $validation .= " validation += validateRequired('" . $fieldName . "','text'); ";
		?>
			<div class="form-group <?= $hidden; ?>">
				<label for="<?= $fieldName; ?>" id="<?= $fieldName; ?>_label" class="col-sm-3 control-label">
					<?= $values[0]; ?><? if ($required) echo '*'; ?>
					<?= (isset($values[4]) && $values[4] != '') ? ' <btn class="btn btn-xs btn-default"><i class="fa fa-fw fa-info" data-toggle="tooltip" data-placement="bottom" title="' . $values[4] . '"></i></btn>' : ''; ?>
				</label>
				<div class="col-sm-9">
					<input type="hidden" class="form-control" name="<?= $fieldName; ?>" id="<?= $fieldName; ?>" value="<?= $fieldValue; ?>" />
					<span id="<?= $fieldName; ?>_icon_example"><?= ($fieldValue) ? '<i class="fa-4x ' . $fieldValue . '"></i>' : ''; ?></span>
					<a class="btn btn-default" href="#" onCLick="$('#<?= $fieldName; ?>_icons').toggle(); return false;">Selecteer</a>
					<div style="display:none; margin: 5px; border: 1" id="<?= $fieldName; ?>_icons">
						<?
						foreach ($icons as $icon) {
							switch ($icon['set']) {
								case 'fa':
									echo '<i class="fa fa-fw fa-' . $icon['icon'] . ' fa-2x fa-border" onClick="selectIcon(\'' . $fieldName .  '\',\'fa fa-' . $icon['icon'] . '\'); return false;"></i>';
									break;
							}
						}
						?>
					</div>
				</div>

			</div>
		<?
			break;
		case 'password':
			if ($required) $validation .= " validation += validateRequired('" . $fieldName . "','text'); ";
		?>
			<div class="form-group <?= $hidden; ?>">
				<label for="<?= $fieldName; ?>" id="<?= $fieldName; ?>_label" class="col-sm-3 control-label">
					<?= $values[0]; ?><? if ($required) echo '*'; ?>
					<?= (isset($values[4]) && $values[4] != '') ? ' <btn class="btn btn-xs btn-default"><i class="fa fa-fw fa-info" data-toggle="tooltip" data-placement="bottom" title="' . $values[4] . '"></i></btn>' : ''; ?>
				</label>
				<div class="col-sm-9">
					<input type="password" class="form-control" name="<?= $fieldName; ?>" id="<?= $fieldName; ?>" value="<?= $fieldValue; ?>" />
				</div>
			</div>
		<?
			break;
		default:
			if ($required) $validation .= " validation += validateRequired('" . $fieldName . "','text'); ";
		?>
			<div class="form-group <?= $hidden; ?>">
				<label for="<?= $fieldName; ?>" id="<?= $fieldName; ?>_label" class="col-sm-3 control-label">
					<?= $values[0]; ?><? if ($required) echo '*'; ?>
					<?= (isset($values[4]) && $values[4] != '') ? ' <btn class="btn btn-xs btn-default"><i class="fa fa-fw fa-info" data-toggle="tooltip" data-placement="bottom" title="' . $values[4] . '"></i></btn>' : ''; ?>
				</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="<?= $fieldName; ?>" id="<?= $fieldName; ?>" value="<?= $fieldValue; ?>" />
				</div>
			</div>
<?
			break;
	}
}


$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
$result = $this->fetchOne($id, 'nl', true, false);
$validation = '';
?>
<? if ($this->form_title) { ?>
	<p class="bewerken well well-small">Bewerk <?= $this->page; ?></p>
<? } ?>
<form class="form-horizontal" id="editForm" action="" method="post" enctype="multipart/form-data" autocomplete="off">
	<input type="hidden" name="save" value="true" />
	<?
	foreach ($this->fields as $field => $values) {

		$hide_editor = ($this->getOption($values[2], 'hide_editor')) ? 1 : 0;
		$hidden = false;
		if ($this->getOption($values[2], 'hidden_parent')) {
			$array = $this->fetchParent($id);
			if (!empty($array) && $id > 0) $hidden = true;
		}
		if (
			!$this->getOption($values[2], 'multilingual')
			&& 	($this->page != 'pages'
				|| 	$field != 'externepagina'
				|| 	$field != 'includeExternal'
				||	($this->user != null && $this->user->check_right('page_external_page', false)))
		) {
			show_field($validation, $this->page, $result, $field, $values, $this->language, false, $this->getOption($values[2], 'required'), $hidden, $hide_editor, $this->getIconArray());
		} else {

			$multilingualFields[$field] 				= $values;
			$multilingualFields[$field]['hidden'] 		= $hidden;
			$multilingualFields[$field]['hide_editor'] 	= $hide_editor;
		}
	}


	$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);
	$tab_javascripts = '';
	$result3 = $db->query("SELECT 			`code`, `language`
							FROM			`languages`
							ORDER BY		id ASC
											") or die(mysqli_error($db));
	while ($row3 = mysqli_fetch_assoc($result3)) {

		$languages[$row3['code']] = $row3['language'];

		$tab_javascripts .= "$('#tab_" . $row3['code'] . " a').click(function (e) {
								alert('Hai');
							});
							";
	}

	if (count($languages) > 1 && $this->hasMultilingual()) {

	?> <ul id="tabs" class="nav nav-tabs"> <?
											$count = 0;
											foreach ($languages as $code => $lang) {

											?> <li <? if ($count == 0) echo 'class="active"'; ?>><a href="#tab_<?= $code; ?>" data-toggle="tab"><?= $lang; ?></a></li> <?
																																										$count++;
																																									}
																																										?> </ul>
		<div class="tab-content">
			<? }

		$count = 0;
		foreach ($languages as $code => $lang) {

			if (count($languages) > 1 && $this->hasMultilingual()) {
			?> <div id="tab_<?= $code; ?>" class="tab-pane fade <? if ($count == 0) echo 'in active'; ?>" style="padding:10px; position: relative;"> <?
																																					}
																																					if (isset($multilingualFields)) {
																																						foreach ($multilingualFields as $field => $values) {

																																							if (
																																								$this->page != 'pages'
																																								|| 	$field != 'externepagina'
																																								||	($this->user != null && $this->user->check_right('page_external_page', false))
																																							) {

																																								show_field($validation, $this->page, $result, $field, $values, $code, true, $this->getOption($values[2], 'required'), $values['hidden'], $values['hide_editor'], $this->getIconArray());
																																							}
																																						}
																																					}
																																					$count++;

																																					if (count($languages) > 1 && $this->hasMultilingual()) {
																																						?> </div> <?
																																								}
																																							}
																																									?>

		<div class="col-sm-9 col-sm-offset-3">
			<!--
	<? if ($this->print_items) { ?>
		<button type="button" class="btn btn-medim btn-default hidden-print" value="opslaan" onCLick="window.print(); return false;"><i class="fa fa-print"></i> Printen</button>
	<? } ?>
	-->
			<button type="button" class="btn btn-medim btn-success hidden-print" value="opslaan" id="formSend"><i class="fa fa-check"></i> Opslaan</button>
			<a href="javascript:history.go(-1)" class="btn btn-medim btn-danger hidden-print" style="color:#fff;"><i class="fa fa-check"></i> Annuleren</a>

		</div>


		</div> <!-- end tab-content -->


</form>

<script type="text/javascript">
	$().ready(function($) {

		<? if (!$this->success_url) { ?>
			$('#tabs').tab();
			<?= $tab_javascripts; ?>
		<? } ?>
		$("#formSend").click(function() {

			var validation = '';

			<?= $validation; ?>

			if (validation == '') {
				$('#editForm').submit();
			} else {
				alert('Niet alle verplichte velden zijn ingevuld.');
			}
		});

	});

	function validateRequired(id, type, value) {

		var validate = '';
		switch (type) {
			case 'boolean':
				if ($('#' + id).val() == '') validate = 'error';
				break;
			case 'image':
				if ($('#' + id).val() == '' && value != '' && $('#' + id + '_remove_image').prop('checked')) validate = 'error';
				if ($('#' + id).val() == '' && value == '') validate = 'error';
				break;
			default:
				if ($('#' + id).val() == '') validate = 'error';
				break;
		}
		var color = (validate != '') ? 'red' : '';
		$("#" + id + '_label').css("color", color);
		return validate;
	}

	function selectIcon(field, value) {

		$('#' + field).val(value);
		$('#' + field + '_icon_example').html('<i class="' + value + '"></i>');
		$('#' + field + '_icons').toggle();
	}
</script>