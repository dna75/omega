<?php
if (file_exists('' . $develop . '/include/upload.class.php')) {
	require_once('' . $develop . '/include/upload.class.php');
} elseif (file_exists('' . $develop . '/include/upload.class.php')) {
	require_once('' . $develop . '/include/upload.class.php');
} else {
	die("NOGO");
}
require_once('libraries/wideimage/WideImage.php');

define('UPLOAD_PATH_CUSTOM', '/upload/custom_pages/');


define('devURL', 'https://devtest.nannedijkstra.nl/cockpit/');

/**
 *	Description of the parameters of the fields Array options:
 *
 *	Syntax:		$fields = array(
 *					fieldname => array('title', 'fieldtype', 'options'),
 *					fieldname => array('title', 'fieldtype', 'options')
 *				};
 *
 *	fieldname		= slug for the field (short name without spaces or weird characters)
 *	title			= Name of the label of the field (used only in the backend)
 *	fieldtype		= Fieldtypes currently available:
 *						- 'text'
 *						- 'textarea'
 *						- 'number'
 *						- 'boolean'
 *						- 'date'
 *						- 'image'
 *						- 'price'
 *						- 'password'
 *						- 'time'
 *	options			= You may use more than one option with a seperating space. The example 100 value below can be
 *						altered, but there should never be a space inbetween the key=value construction.
 *
 *						The following options are currently available:
 *						- multilingual 			= Whether or not this field is multilingual (images cannot be multilingual)
 *						- required				= When selected the field is required
 *						- hide_overview			= Whether or not this field is shown in the overview in the backend
 *						- overview_width=50 	= The width in the overview page
 *						- min_width=100			= Minimum image-width
 *						- min_height=100		= Minimum image-height
 *						- max_width=100			= Maximum image-width
 *						- max_height=100		= Maximum image-height
 *						- aspect_ratio			= When the image aspect-ratio is set the ratio is max_width:max_height
 *						- max_thumb_width=100	= Maximum thumb-width
 *						- max_thumb_height=100	= Maximum thumb-height
 *						- thumb_crop_on			= Whether or not the thumbnail should be cropped
 *						- hide_editor			= Hide the editor in an textarea
 *
 *
 *	NOTES: 	- THE FIELDS ARE SHOWN IN THE ORDER OF THE FIELDS ARRAY, BUT THE MULTILINGUAL FIELDS
 *				ARE ALWAYS SHOWN LAST!!!
 *			- IMAGES CAN NEVER BE MULTILINGUAL
 *						-
 **/

/*
CREATE TABLE IF NOT EXISTS `custom_pages` (
  	`id` 			int(11) unsigned NOT NULL AUTO_INCREMENT,
  	`main_id` 		int(11) NOT NULL,
  	`page`			varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  	`language` 		varchar( 2 ) NOT NULL DEFAULT 'nl',
  	`field_slug`	varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  	`text` 			varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  	`textarea` 		text COLLATE utf8_unicode_ci NOT NULL,
  	`number` 		int(11) NOT NULL,
  	`date` 			date DEFAULT NULL,
  	`active` 		int(11) NOT NULL DEFAULT '0',
  	`field_order` 	int(11) NOT NULL,
  	`order` 		int(11) NOT NULL,
  	PRIMARY KEY (`id`)
) 	ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
*/
class CustomPages
{

	public		$page,
		$id,
		$nr 			= 0,
		$facebook		= false,
		$copy_items		= false,
		$maxLevels		= 2,
		$print_items	= false,
		$excel_export	= false,
		$user			= null,
		$success_url	= false,
		$form_title		= true;


	private 	$fields,
		$db				= false,
		$language 		= 'nl',
		$levels			= false,
		$mode			= 'overview',
		$min_width		= 150,
		$min_height		= 150,
		$max_width		= 1600,
		$max_height		= 1600,
		$thumb_crop		= true,
		$thumb_width	= 100,
		$thumb_height	= 100,
		$auto_active	= 0,
		$add_items		= true,
		$delete_items	= true,
		$edit_items		= true,
		$order_select	= true,
		$buttons		= array(),
		$page_results;


	public function __construct($page, $fields = array(), $auto_active = 0, $levels = false, $maxLevels = 2, $user = null)
	{

		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$this->setDatabase(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);
		if (is_array($auto_active)) {

			$this->maxLevels	= (isset($auto_active['maxLevels'])) 	? $auto_active['maxLevels'] 	: 2;
			$this->levels		= (isset($auto_active['levels'])) 		? $auto_active['levels'] 		: false;
			$this->auto_active	= (isset($auto_active['auto_active'])) 	? $auto_active['auto_active'] 	: 0;
			$this->buttons		= (isset($auto_active['buttons'])) 		? $auto_active['buttons'] 		: array();
			$this->user			= (isset($auto_active['user'])) 		? $auto_active['user'] 			: null;
			$this->add_items	= (isset($auto_active['add_items']) 	&& $auto_active['add_items'] 	== false) 	? false : true;
			$this->edit_items	= (isset($auto_active['edit_items']) 	&& $auto_active['edit_items'] 	== false) 	? false	: true;
			$this->delete_items	= (isset($auto_active['delete_items']) 	&& $auto_active['delete_items'] == false) 	? false	: true;
			$this->excel_export	= (isset($auto_active['excel_export']) 	&& $auto_active['excel_export'] == true) 	? true	: false;
			$this->facebook		= (isset($auto_active['facebook']) 		&& $auto_active['facebook'] 	== false) 	? false	: true;
			$this->order_select	= (isset($auto_active['order_select']) 	&& $auto_active['order_select'] == false) 	? false	: true;
			$this->success_url	= (isset($auto_active['success_url']) 	&& $auto_active['success_url']	!= '') 		? $auto_active['success_url'] : false;
			$this->form_title	= (isset($auto_active['form_title']) 	&& $auto_active['form_title']	== false) 	? false : true;
		} else {

			$this->auto_active	= $auto_active;
			$this->user			= $user;
			$this->maxLevels	= $maxLevels;
			$this->levels		= $levels;
		}
		$this->mode			= ($levels) ? 'overview_levels' : 'overview';
		$this->mode			= (isset($_GET['mode'])) ? mysqli_real_escape_string($db, $_GET['mode']) : $this->mode;
		$this->mode			= (isset($auto_active['view']) 	&& $auto_active['view'] != '') ? $auto_active['view'] : $this->mode;
		$this->nr			= (isset($_GET['nr'])) ? intval($_GET['nr']) : 0;
		$this->page			= mysqli_real_escape_string($db, $page);
		$this->fields		= $fields;
		if (!defined('CUSTOMPAGES')) {
			define('CUSTOMPAGES', TRUE);
		}
	}


	private function setDatabase($host, $user, $pass, $database)
	{

		$this->db = new mysqli($host, $user, $pass, $database);
	}


	public function showPage($backend = false, $order = false, $reverse = false)
	{

		$GLOBALS['showpageorder'] 	= $order;
		$GLOBALS['showpagereverse'] = $reverse;

		if ($backend) include_once('views/' . $this->mode . '_view.php');
	}

	public function fetchOne($id, $language = false, $multilingual = false, $autoField = true)
	{

		if (intval($id) > 0) {

			$language = (!$language) ? $this->language : $language;
			$language = ($multilingual) ? '' : " AND language	= '" . $language . "' ";
			$array = array();

			$result = $this->db->query("SELECT 			*
										FROM 			custom_pages
										WHERE 			page 		= '" . $this->page . "'
										AND 			main_id		= '" . $id . "'
										" . $language . "
										ORDER BY		field_order, language
														") or die(__FILE__ . ':' . __LINE__ . ' - ' . mysqli_error($db));
			if ($result->num_rows > 0) {

				while ($row = $result->fetch_assoc()) {

					$value = ($autoField) ? $this->getAutoFieldValue($row) : $this->getFieldValue($row, $this->fields[$row['field_slug']][1]);

					if (!$multilingual) {
						$array[$row['field_slug']] = $value;
					} else {

						$array[$row['field_slug'] . '_' . $row['language']] = array(
							'value' 	=> $value,
							'language' 	=> $row['language']
						);
					}
				}
				$array = $this->setAllFields($array);
				$this->page_results = $array;
				return $array;
			}
		}
		$this->page_results = false;
		return false;
	}

	/**
	 *	De $page->fetch functie haalt de resultaten uit de database.
	 *	Deze functie heeft de volgende parameter:
	 *		1 Language		= De taal										, '' = standaard taal		(kan ook helemaal leeggelaten worden)
	 **/
	public function fetch($language = false, $neatFields = false, $autoField = true)
	{

		$language 	= (!$language) ? $this->language : $language;
		$array 		= array();
		$i 			= 0;

		$result = $this->db->query("SELECT 			DISTINCT main_id, active
									FROM 			custom_pages
									WHERE 			page 		= '" . $this->page . "'
									AND				language	= '" . $language . "'
									ORDER BY		`order`		ASC
													") or die(__FILE__ . ':' . __LINE__ . ' - ' . mysqli_error($db));
		if ($result->num_rows > 0) {

			while ($row = $result->fetch_assoc()) {

				$main_id 				= $row['main_id'];
				$array[$i] 				= array();
				$array[$i]['id'] 		= $main_id;
				$array[$i]['active']	= $row['active'];

				$result2 = $this->db->query("SELECT		field_slug,
														text,
														textarea,
														number,
														boolean,
														date,
														active
											FROM		custom_pages
											WHERE		main_id 	= " . $row['main_id'] . "
											AND			language	= '" . $language . "'
											AND			page 		= '" . $this->page . "'
											ORDER BY	`field_order`
														") or die(__FILE__ . ':' . __LINE__ . ' - ' . mysqli_error($db));
				if ($result2->num_rows > 0) {

					while ($row2 = $result2->fetch_assoc()) {

						if ($autoField) {
							$array[$i][$row2['field_slug']] = $this->getAutoFieldValue($row2, $neatFields);
						} else {
							$array[$i][$row2['field_slug']] = (isset($this->fields[$row2['field_slug']][1])) ? $this->getFieldValue($row2, $this->fields[$row2['field_slug']][1], $neatFields) : '';
						}
					}
				}
				$array[$i] = $this->setAllFields($array[$i]);
				$i++;
			}
		}

		$this->page_results = $array;
		return $array;
	}

	private function setAllFields($array)
	{

		foreach ($this->fields as $key => $field) {
			if (!isset($array[$key])) $array[$key] = '';
		}
		return $array;
	}

	public function getFieldClassName($field)
	{

		if (isset($this->fields[$field][3]) && $this->fields[$field][3] != '') return $this->fields[$field][3];
		return '';
	}


	public function getTooltip($field)
	{

		if (isset($this->fields[$field][4]) && $this->fields[$field][4] != '') return $this->fields[$field][4];
		return '';
	}


	/**
	 *	De $page->fetch functie haalt de resultaten uit de database.
	 *	Deze functie heeft de volgende parameter:
	 *		1 Language		= De taal										, '' = standaard taal		(kan ook helemaal leeggelaten worden)
	 **/
	public function fetchParent($parent_id, $language = false, $neatFields = false, $autoField = true, $setPageResult = true)
	{

		$language 	= (!$language) ? $this->language : $language;
		$array 		= array();
		$i 			= 0;

		$result = $this->db->query("SELECT 			DISTINCT main_id, active
									FROM 			custom_pages
									WHERE 			page 		= '" . $this->page . "'
									AND				parent_id	= '" . intval($parent_id) . "'
									ORDER BY		`order`
													") or die(__FILE__ . ':' . __LINE__ . ' - ' . mysqli_error($db));
		if ($result->num_rows > 0) {

			while ($row = $result->fetch_assoc()) {

				$main_id 				= $row['main_id'];
				$array[$i] 				= array();
				$array[$i]['id'] 		= $main_id;
				$array[$i]['active']	= $row['active'];

				$result2 = $this->db->query("SELECT		field_slug,
														text,
														textarea,
														number,
														boolean,
														date,
														active
											FROM		custom_pages
											WHERE		main_id 	= " . $row['main_id'] . "
											AND			language	= '" . $language . "'
											AND			page 		= '" . $this->page . "'
											ORDER BY	`field_order`
														") or die(__FILE__ . ':' . __LINE__ . ' - ' . mysqli_error($db));
				if ($result2->num_rows > 0) {

					while ($row2 = $result2->fetch_assoc()) {

						$array[$i][$row2['field_slug']] = ($autoField) ? $this->getAutoFieldValue($row2, $neatFields) : $this->getFieldValue($row2, $this->fields[$row2['field_slug']][1], $neatFields);
					}
				}
				$array[$i] = $this->setAllFields($array[$i]);
				$i++;
			}
		}
		if ($setPageResult) $this->page_results = $array;
		return $array;
	}

	public function get_items($id, $ol = false, $classes = '', $result = false)
	{

		$result = ($result === false) ? $this->fetchParent($id, false, true, false, false) : $result;

		if (!empty($result)) {
			if ($ol) echo "<ol>";
			foreach ($result as $row) {

				$new_result = $this->fetchParent($row['id'], false, true, false, false);
				$children = (empty($new_result) || count($new_result) == 0) ? false : true;

?>

				<li id="album_<?= $row['id']; ?>" class="<?= $classes; ?>" style="display: <?= ($classes == '') ? '' : 'none'; ?>;">
					<div style="height: 40px;">
						<span class="number">
							<? if ($this->maxLevels > 0 && $this->levels == true) { ?>
								<a id="levelSwitch<?= $row['id']; ?>" style="display: <?= ($children) ? '' : 'none'; ?>;" href="#" onClick="$('.subLi<?= $row['id']; ?>').slideToggle(200); $(this).find('i').toggleClass('fa-chevron-right') ; $(this).find('i').toggleClass('fa-chevron-down'); return false;">
									<i class="fa fa-fw fa-chevron-right" style="color: grey;"></i>
								</a>
								<i id="levelSwitchDummy<?= $row['id']; ?>" style="display: <?= ($children) ? 'none' : ''; ?>;" class="fa fa-fw" style="color: grey;"></i>
							<? } ?>
							<a href="index.php?page=<?= $_GET['page']; ?>&active=<? echo ($row['active'] == true) ? '0' : '1'; ?>&id=<?php echo $row['id']; ?>">
								<img src="/cockpit/images/traffic-light-<? echo ($row['active'] == true) ? 'green' : 'red'; ?>.png" />
							</a>
						</span>
						<?
						foreach ($this->fields as $field => $values) {
							$overview_width = (isset($values[2]) && $this->getOption($values[2], 'overview_width') > 0) ? floor(intval($this->getOption($values[2], 'overview_width')) / 5) : 10;
							if (!isset($values[2]) || !$this->getOption($values[2], 'hide_overview')) {
						?>
								<span style="width:<?= $overview_width; ?>%;" class="<?= $this->getFieldClassName($field); ?>">
									<? if (isset($row[$field]) && $row[$field] != '') {
										if ($values[1] == 'image') {
											echo '<img src="' . UPLOAD_PATH_CUSTOM . $this->page . '/thumb/' . $row[$field] . '" width="25px" height="25px />"';
										} elseif ($values[1] == 'date') {
											echo ($row[$field] == '1970-01-01' || !$row[$field]) ? '-' : date('d-m-Y', strtotime($row[$field]));
										} else {
											echo $row[$field];
										}
									}
									?>
								</span>
						<?
							}
						}
						?>
						<span class="options" style=" float: right; margin: 2px 2px 0 0">
							<?
							if ($this->facebook) { ?>

								<a class="btn btn-mini btn-default" href="/cockpit/index.php?page=facebook&id=<?= $row['id']; ?>" title="Klik hier om dit item te posten">Facebook</i></a>

							<? } ?>
							<? if ($this->page != 'pages' or ($this->user != null && $this->user->check_right('page_edit', false))) { ?>
								<? if ($this->edit_items) { ?>
									<a class="btn btn-mini btn-default" href="index.php?page=<?= $this->page; ?>&mode=edit&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Klik hier om dit item te bewerken"><i class="fa fa-pencil"></i></a>
								<? } ?>
							<? } ?>
							<? if ($this->page != 'pages' or ($this->user != null && $this->user->check_right('page_delete', false))) { ?>
								<? if ($this->delete_items) { ?>
									<a id="deleteButton<?= $row['id']; ?>" class="btn btn-mini btn-danger" style="color:white; display: <?= ($children) ? 'none' : ''; ?>;" onclick="return confirm('Weet u zeker dat u dit item wilt verwijderen?');" href="index.php?page=<?= $this->page; ?>&del=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Klik hier om het item te verwijderen"><i class="fa fa-trash-o "></i></a>
								<? } ?>
							<? } ?>
						</span>
					</div>
					<? $this->get_items($row['id'], true, 'subLi' . $row['id'], $new_result); ?>
				</li>
<? }
			if ($ol) echo "</ol>";
		}
	}


	public function save($cropRedirect = false)
	{

		$main_id = (isset($_GET['id'])) ? intval($_GET['id']) : 0;
		$files_uploaded = array();
		$active = 1;
		$order = 0;
		$first_time = true;


		if ($main_id > 0) {
			$query2 = $this->db->query("SELECT		MAX(`active`) as `active`, MAX(`order`) as `order`
										FROM		custom_pages
										WHERE		page 		= '" . $this->page . "'
										AND			main_id		= '" . $main_id . "'
										LIMIT 		1
													") or die(mysqli_error($db));

			if ($query2->num_rows > 0) {
				$result2 	= $query2->fetch_assoc();
				$active 	= ($first_time) ? $result2['active'] : $active;
				$order		= ($first_time) ? $result2['order']	: $order;
			}
		}
		foreach ($this->fields as $field => $values) {

			if (
				$this->page == 'pages'
				&& 	$field == 'externepagina'
				&&	($this->user == null || !$this->user->check_right('page_external_page', false))
			) continue;

			if (isset($_POST[$field . '_remove_image']) && $_POST[$field . '_remove_image'] == 'on') {

				$this->deleteImage($field, $main_id, true);
			}

			$result = $this->db->query("SELECT 			`code`, `language`
										FROM			`languages`
										ORDER BY		id ASC
														") or die(mysqli_error($db));
			while ($row = $result->fetch_assoc()) {


				$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

				$postValue 		= ($this->getOption($values[2], 'multilingual')) ? $_POST[$field . '_' . @$row['code']] : @$_POST[$field];
				$postValue		= mysqli_real_escape_string($db, $postValue);
				$valuesArray	= array('', '', 0, 0, NULL);
				$updated 		= false;
				$update 		= true;
				$removed		= false;
				$remove_orig	= false;

				switch ($values[1]) {

					case 'password':
						$valuesArray[0] = $postValue;
						break;
					case 'price':
						$postValue = str_replace(',', '.', $postValue);
						$valuesArray[0] = number_format($postValue, 2, '.', '');
						break;
					case 'textarea':
						$valuesArray[1] = $postValue;
						break;
					case 'number':
						$valuesArray[2] = $postValue;
						break;
					case 'boolean':
						$valuesArray[3] = (isset($postValue) && $postValue == 'on') ? 1 : 0;
						break;
					case 'date':
						$temp_date = explode("-", $postValue);
						$valuesArray[4] = $temp_date[2] . '-' . $temp_date[1] . '-' . $temp_date[0];
						break;
					case 'icon':
						$valuesArray[0] = $postValue;
						break;
					case 'image':
						// IMAGE UPLOAD HERE
						$remove_image = true;
						if (!empty($_FILES[$field]["name"]) && $files_uploaded[$field] == '') {

							$destpath 	= '../upload/custom_pages/' . $this->page . '/';
							$file = array(
								'name' 		=> $_FILES[$field]['name'],
								'tmp_name' 	=> $_FILES[$field]['tmp_name'],
								'size' 		=> $_FILES[$field]['size'],
								'error' 	=> $_FILES[$field]['error'],
								'type' 		=> $_FILES[$field]['type']
							);
							$upload = new Upload($destpath, $file);

							$max_width 			= ($this->getOption($values[2], 'max_width') != false) ? $this->getOption($values[2], 'max_width') : $this->max_width;
							$max_height 		= ($this->getOption($values[2], 'max_height') != false) ? $this->getOption($values[2], 'max_height') : $this->max_height;
							$thumb_width		= ($this->getOption($values[2], 'max_thumb_width') != false) ? $this->getOption($values[2], 'max_thumb_width') : $this->thumb_width;
							$thumb_height		= ($this->getOption($values[2], 'max_thumb_height') != false) ? $this->getOption($values[2], 'max_thumb_height') : $this->thumb_height;
							$thumb_crop			= ($this->getOption($values[2], 'thumb_crop_on') != false) ? true : false;

							// The minimum size should always be the smallest max-size

							if ($this->getOption($values[2], 'resize') == false) {
								$upload->resize($max_width, $max_height);
							} else {
								$w = $max_width;
								$h = $max_height;
								if ($w > $h) {
									$h = $w;
								} else {
									$w = $h;
								}

								$upload->resize($w, $h);
							}
							$upload->thumb($thumb_width, $thumb_height, $thumb_crop);
							$upload->save_image();

							if ($this->getOption($values[2], 'remove_orig')) $remove_orig = true;
							$this->deleteImage($field, $main_id, false);

							$filename = $upload->filename;

							$valuesArray[0] = $filename;
							$files_uploaded[$field] = $filename;
							$remove_image = false;
						} elseif ($files_uploaded[$field] != '') {

							$valuesArray[0] = $files_uploaded[$field];
						} else {

							$update = false;
						}
						break;
					default:
						$valuesArray[0] = $postValue;
						break;
				}
				if ($main_id > 0 && $update) {

					$query2 = $this->db->query("SELECT		`id`
												FROM		custom_pages
												WHERE		page 		= '" . $this->page . "'
												AND			language	= '" . $row['code'] . "'
												AND			field_slug	= '" . $field . "'
												AND			main_id		= '" . $main_id . "'
															") or die(mysqli_error($db));

					if ($query2->num_rows > 0) {

						$result2 = $query2->fetch_assoc();
						$this->db->query("UPDATE	custom_pages
											SET		text 			= '" . $valuesArray[0] . "',
													textarea		= '" . $valuesArray[1] . "',
													number 			= '" . $valuesArray[2] . "',
													boolean			= '" . $valuesArray[3] . "',
													date 			= '" . $valuesArray[4] . "'
											WHERE	id				= '" . $result2['id'] . "'
													") or die(mysqli_error($db));
						if ($result2['id'] > 0) $updated = true;
					}
				}
				$first_time = false;

				if (!$updated && $update) {
					$this->db->query("INSERT INTO	custom_pages
													(`main_id`, `page`, `language`, `field_slug`,
													`text`, `textarea`, `number`, `boolean`, `date`,
													`active`, `order`)
										VALUES		('" . $main_id . "', '" . $this->page . "', '" . $row['code'] . "', '" . $field . "',
													'" . $valuesArray[0] . "', '" . $valuesArray[1] . "', '" . $valuesArray[2] . "', '" . $valuesArray[3] . "', '" . $valuesArray[4] . "',
													'" . $active . "', '" . $order . "')
													") or die(mysqli_error($db));
					if ($main_id == 0) {
						$insertId = $this->db->insert_id;
						$this->db->query("UPDATE		custom_pages
											SET			main_id	= '" . $insertId . "'
											WHERE		id		= '" . $insertId . "'
									") or die(mysqli_error($db));
						$main_id = $insertId;
					}
				}

				if ($remove_orig) $this->deleteImage($field, $main_id, false);
			}
		}
		if ($cropRedirect) {
			$nr	= $this->nextCropImage($main_id);
			if ($nr > -1) {
				if ($nr >= 0) {
					header("Location: /cockpit/index.php?page=" . $this->page . "&mode=image&id=" . $main_id . "&nr=" . $nr);
					die();
				}
			}
		}
		return $main_id;
	}

	private function update_order($id)
	{

		if ($this->page != 'pages') {
			$this->db->query("UPDATE		`custom_pages`
								SET		`order` 			= `order` + 1
								WHERE	`page`				= '" . $this->page . "'
								");
			$this->db->query("UPDATE		`custom_pages`
								SET		`order` 			= 0
								WHERE	`main_id`			= '" . $id . "'
								");
		} else {
			$query2 = $this->db->query("SELECT		MAX(`order`)		AS `order`
										FROM		custom_pages
										WHERE		page 		= '" . $this->page . "'
													") or die(mysqli_error($db));
			if ($query2->num_rows > 0) {
				$result2 	= $query2->fetch_assoc();
				$order 		= $result2['order'] + 1;
			}
			$this->db->query("UPDATE		`custom_pages`
								SET		`order` 			= '" . $order . "'
								WHERE	`main_id`			= '" . $id . "'
								");
		}
	}


	public function crop_image()
	{

		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$id 		= (intval($_GET['id']) > 0) ? intval($_GET['id']) : 0;
		$result 	= $this->fetchOne($id, 'nl');
		$field		= mysqli_real_escape_string($db, $_POST['field']);
		$x1			= intval($_POST['x1']);
		$y1			= intval($_POST['y1']);
		$x2			= intval($_POST['x2']);
		$y2			= intval($_POST['y2']);
		$url		= '..' . UPLOAD_PATH_CUSTOM . $this->page . '/' . $result[$field];
		$thumb_url	= '..' . UPLOAD_PATH_CUSTOM . $this->page . '/thumb/' . $result[$field];

		$thumb_width	= $this->getOption($this->fields[$field][2], 'max_thumb_width');
		$thumb_height 	= $this->getOption($this->fields[$field][2], 'max_thumb_height');
		$thumb_crop 	= $this->getOption($this->fields[$field][2], 'thumb_crop_on');
		$max_width 		= $this->getOption($this->fields[$field][2], 'max_width');
		$max_height 	= $this->getOption($this->fields[$field][2], 'max_height');
		$min_width 		= $this->getOption($this->fields[$field][2], 'min_width');
		$min_height 	= $this->getOption($this->fields[$field][2], 'min_height');
		$aspect_ratio 	= $this->getOption($this->fields[$field][2], 'aspect_ratio');
		$thumb_width	= ($thumb_width > 0) ? $thumb_width : $this->thumb_width;
		$thumb_height	= ($thumb_height > 0) ? $thumb_height : $this->thumb_height;
		$max_width 		= ($max_width > 0) ? $max_width : $this->max_width;
		$max_height		= ($max_width > 0) ? $max_height : $this->max_height;
		$min_width 		= ($min_width > 0) ? $min_width : $this->min_width;
		$min_height		= ($min_width > 0) ? $min_height : $this->min_height;

		$image = WideImage::load($url);
		$width			= $x2 - $x1;
		$height			= $y2 - $y1;
		if ($image->getWidth() < $x1 + $width) $width = $image->getWidth() - $x1;
		if ($image->getHeight() < $y1 + $height) $height = $image->getHeight() - $y1;
		$cropped = $image->crop($x1, $y1, $width, $height);

		$resize = false;

		if ($width < $min_width || $height < $min_width) {
			$resized = $cropped->resize($min_width, $min_height, 'outside');
			$resize = true;
		}

		if ($width > $max_width || $height > $max_height) {
			$resized = $cropped->resize($max_width, $max_height, 'inside');
			$resize = true;
		}

		// if ($width > $max_width || $height > $max_height) {
		// 	$resized = $cropped->resize($max_width, $max_height, 'inside');
		// 	$resize = true;
		// }

		$thumb_way = ($thumb_crop) ? 'outside' : 'inside';
		if ($resize) {
			$thumb = $resized->resize($thumb_width, $thumb_height, $thumb_way);
			$resized->saveToFile($url);
		} else {
			$thumb = $cropped->resize($thumb_width, $thumb_height, $thumb_way);
			$cropped->saveToFile($url);
		}
		if ($thumb_crop != false) {
			$thumb->crop('center', 'center', $thumb_width, $thumb_height)->saveToFile($thumb_url);
		} else {
			$thumb->saveToFile($thumb_url);
		}


		$this->db->query("UPDATE		custom_pages
							SET			boolean 	= 1
							WHERE		main_id		= '" . $id . "'
							AND			field_slug	= '" . $field . "'
										");

		$nr	= $this->nextCropImage($id);
		if ($nr >= 0) {
			header("Location: /cockpit/index.php?page=" . $this->page . "&mode=image&id=" . $id . "&nr=" . $nr);
		} else {
			$levels = ($this->levels) ? '_levels' : '';
			header("Location: /cockpit/index.php?page=" . $this->page . "&mode=overview" . $levels);
		}
		die();
	}

	public function nextCropImage($id)
	{

		$images = $this->hasImages();
		$nr = 0;
		if (!empty($images)) {
			foreach ($images as $image) {

				if ($image['resize']) {

					$result = $this->db->query("SELECT 	main_id
												FROM	custom_pages
												WHERE	main_id		= '" . intval($id) . "'
												AND		field_slug	= '" . $image['field'] . "'
												AND		boolean		= '0'
											");

					if ($result->num_rows != 0)
						return $nr;
				}
				$nr++;
			}
		}
		return -1;
	}

	public function delete()
	{

		$main_id = (isset($_GET['del']) && intval($_GET['del']) > 0) ? intval($_GET['del']) : 0;

		foreach ($this->fields as $field => $values) {

			if ($values[1] == 'image') {

				$this->deleteImage($field, $main_id);
			}
		}
		$this->db->query("DELETE FROM 	custom_pages
							WHERE		main_id = '" . $main_id . "'
										") or die(mysql_error);
	}

	public function copy_item()
	{

		$main_id = (isset($_GET['cid']) && $_GET['cid'] > 0) ? intval($_GET['cid']) : 0;

		if ($main_id == 0) header("Location: /cockpit/index.php?page=" . $this->page);

		$result = $this->db->query("SELECT		*
									FROM		custom_pages
									WHERE		main_id 		= '" . $main_id . "'
												") or die(mysqli_error($db));
		if ($result->num_rows > 0) {

			$new_main_id = 0;
			$destpath 	= '../upload/custom_pages/' . $this->page . '/';

			$image_array = array();

			foreach ($this->fields as $field => $values) {

				if ($values[1] == 'image') {

					$image_array[$field] = $this->copyImage($field, $main_id);
				}
			}

			while ($row = $result->fetch_assoc()) {

				$text = (isset($image_array[$row['field_slug']])) ? $image_array[$row['field_slug']] : $row['text'];

				$this->db->query('INSERT INTO	`custom_pages`
												(`main_id`, `parent_id`, `page`, `language`,
												`field_slug`, `text`, `textarea`, `number`, `boolean`,
												`date`, `active`, `field_order`, `order`)
									VALUES		("' . $new_main_id . '", "' . $row['parent_id'] . '", "' . $row['page'] . '", "' . $row['language'] . '",
												"' . $row['field_slug'] . '", "' . $text . '", "' . $row['textarea'] . '", "' . $row['number'] . '", "' . $row['boolean'] . '",
												"' . $row['date'] . '", "' . $row['active'] . '", "' . $row['field_order'] . '", "' . $row['order'] . '")
												') or die(mysqli_error($db));

				if ($new_main_id == 0) {
					$new_main_id = $this->db->insert_id;
					$this->db->query("UPDATE		`custom_pages`
									SET		`main_id`		= '" . $new_main_id . "'
									WHERE	`id`			= '" . $new_main_id . "'");
				}
			}
			header("Location: /cockpit/index.php?page=" . $this->page . "&mode=edit&id=" . $new_main_id);
		} else {
			header("Location: /cockpit/index.php?page=" . $this->page);
		}
	}

	private function deleteImage($field, $main_id, $remove_all = true)
	{
		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$result = $this->db->query("SELECT		DISTINCT text
									FROM		custom_pages
									WHERE		page 		= '" . $this->page . "'
									AND			field_slug	= '" . mysqli_real_escape_string($db, $field) . "'
									AND			main_id		= '" . intval($main_id) . "'
									LIMIT		1
												") or die(mysqli_error($db));
		if ($result->num_rows > 0) {

			$destpath 	= '../upload/custom_pages/' . $this->page . '/';

			while ($row = $result->fetch_assoc()) {

				if ($row['text'] != '') {

					if (file_exists($destpath . $row['text']) && $remove_all)
						unlink($destpath . $row['text']);
					if (file_exists($destpath . "thumb/" . $row['text']) && $remove_all)
						unlink($destpath . "thumb/" . $row['text']);
					if (file_exists($destpath . "orig/" . $row['text']))
						unlink($destpath . "orig/" . $row['text']);

					if ($remove_all) {
						$this->db->query("UPDATE 		custom_pages
											SET			text 			= ''
											WHERE		main_id 		= '" . $main_id . "'
											AND			field_slug 		= '" . $field . "'
														") or die(mysqli_error);
					}
				}
			}
		}
	}

	private function copyImage($field, $main_id, $remove_all = true)
	{

		$result = $this->db->query("SELECT		DISTINCT text
									FROM		custom_pages
									WHERE		page 		= '" . $this->page . "'
									AND			field_slug	= '" . mysqli_real_escape_string($field) . "'
									AND			main_id		= '" . intval($main_id) . "'
									LIMIT		1
												") or die(mysqli_error($db));
		if ($result->num_rows > 0) {

			$destpath 	= '../upload/custom_pages/' . $this->page . '/';

			while ($row = $result->fetch_assoc()) {

				if ($row['text'] != '') {

					$name 		= pathinfo($destpath . $row['text'], PATHINFO_FILENAME);
					$extension 	= pathinfo($destpath . $row['text'], PATHINFO_EXTENSION);
					$file 		= $name . '_1.' . $extension;

					if (file_exists($destpath . $row['text']))
						copy($destpath . $row['text'], $destpath . $file);
					if (file_exists($destpath . "thumb/" . $row['text']))
						copy($destpath . "thumb/" . $row['text'], $destpath . "thumb/" . $file);
					if (file_exists($destpath . "orig/" . $row['text']))
						copy($destpath . "orig/" . $row['text'], $destpath . "orig/" . $file);
					return $file;
				}
			}
		}
	}

	public function setLanguage($language)
	{

		$this->language = $language;
	}

	public function hasImages()
	{

		$images = array();
		foreach ($this->fields as $field => $values) {

			if ($values[1] == 'image') {

				$max_width 		= $this->getOption($values[2], 'max_width');
				$max_height 	= $this->getOption($values[2], 'max_height');
				$min_width 		= $this->getOption($values[2], 'min_width');
				$min_height 	= $this->getOption($values[2], 'min_height');
				$aspect_ratio 	= $this->getOption($values[2], 'aspect_ratio');
				$required 		= $this->getOption($values[2], 'required');
				$resize			= $this->getOption($values[2], 'resize');
				$max_width 		= ($max_width > 0) ? $max_width : $this->max_width;
				$max_height		= ($max_width > 0) ? $max_height : $this->max_height;
				$min_width 		= ($min_width > 0) ? $min_width : $this->min_width;
				$min_height		= ($min_width > 0) ? $min_height : $this->min_height;

				$images[] = array(
					'field' 		=> $field,
					'max_width' 	=> $max_width,
					'max_height'	=> $max_height,
					'min_width' 	=> $min_width,
					'min_height'	=> $min_height,
					'aspect_ratio'	=> $aspect_ratio,
					'required'		=> $required,
					'resize'		=> $resize
				);
			}
		}
		return (!empty($images)) ? $images : false;
	}

	private function hasMultilingual()
	{

		foreach ($this->fields as $field => $values) {

			if ($this->getOption($values[2], 'multilingual')) return true;
		}
		return false;
	}

	private function getOption($string, $value)
	{

		$options 		= explode(' ', strtolower($string));
		$optionValues	= array();

		for ($i = 0; $i < count($options); $i++) {

			if (strpos($options[$i], '=') > 0) {

				$temp 				= explode('=', $options[$i]);
				$options[$i]		= $temp[0];
				$optionValues[$i]	= $temp[1];
			}

			if (trim($options[$i]) == trim($value)) {

				return (isset($optionValues[$i]) && $optionValues[$i] != '') ? $optionValues[$i] : true;
			}
		}
		return false;
	}

	private function getFieldValue($row, $type, $neatFields = false)
	{

		switch ($type) {

			case 'textarea':
				return $row['textarea'];
				break;
			case 'number':
				return $row['number'];
				break;
			case 'price':
				return $row['text'];
				break;
			case 'time':
				return $row['text'];
				break;
			case 'password':
				return $row['text'];
				break;
			case 'boolean':
				if ($neatFields) {
					return ($row['boolean']) ? 'Ja' : 'Nee';
				} else {
					return  $row['boolean'];
				}
				break;
			case 'date':
				return date("Y-m-d", strtotime($row['date']));
				break;
			default:
				return $row['text'];
				break;
		}
	}

	private function getAutoFieldValue($row, $neatFields = false)
	{

		if ($row['text'] != '') 						return $row['text'];
		if ($row['textarea'] != '') 					return $row['textarea'];
		if ($row['date'] != '0000-00-00 00:00:00') 		return date("Y-m-d", strtotime($row['date']));
		if ($row['number'] > 0) 						return $row['number'];

		if ($neatFields) {
			return ($row['boolean']) ? 'Ja' : 'Nee';
		} else {
			return  $row['boolean'];
		}
	}

	/**
	 *	SORT
	 *
	 *	Met de $page->sort functie kun je de resultaten sorteren
	 *	Deze functie heeft de volgende parameters:
	 *		1 Field			= Het veld waarop gesorteerd moet worden			, Deze is verplicht
	 *		2 Reverse		= Of de resultaten omgedraaid moeten worden			, Deze is niet verplicht en staat standaard op false (niet omdraaien)
	 **/
	public function sort($fields, $reverse = false)
	{

		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$rows = $this->page_results;
		if (count($rows) < 1) return false;

		$GLOBALS['field_sort_field'] = mysqli_real_escape_string($db, $fields);
		usort($rows, function ($elem1, $elem2) {
			if (!isset($elem1[$GLOBALS['field_sort_field']]) || !isset($elem2[$GLOBALS['field_sort_field']])) return false;
			return strcmp($elem1[$GLOBALS['field_sort_field']], $elem2[$GLOBALS['field_sort_field']]);
		});

		if ($reverse) $rows = array_reverse($rows);

		$this->page_results = $rows;
	}

	/**
	 *	Met de $page->reverse functie kun je de resultaten omdraaien
	 *	Deze functie heeft geen parameters
	 **/
	public function reverse()
	{

		if (count($this->page_results) > 1) $this->page_results = array_reverse($this->page_results);
	}

	/**
	 *	Met de $page->random functie kun je de resultaten randomizen
	 *	Deze functie heeft geen parameters
	 **/
	public function random()
	{

		if (count($this->page_results) > 1) {

			$keys 	= array_keys($this->page_results);
			$new 	= array();
			shuffle($keys);

			foreach ($keys as $key) {
				$new[$key] = $this->page_results[$key];
			}
			$this->page_results = $new;
		}
	}

	/**
	 *	Met de $page->limit functie kun je het aantal resultaten bepalen
	 *	Deze functie heeft de volgende parameter:
	 *		1 Aantal		= Het aantal resultaten								, Deze is verplicht
	 *		2 Vanaf			= Vanaf welk resultaat								, Deze is niet verplicht en staat standaard op 0
	 **/
	public function limit($to, $from = 0)
	{

		if (intval($to) > 0) $this->page_results = array_slice($this->page_results, intval($from), intval($to));
	}

	/**
	 *	Met de $page->filter functie kun je de resultaten filteren, je kunt dit meerdere malen doen als dit nodig is.
	 *	Deze functie heeft de volgende parameters:
	 *		1 Field			= Het veld waarop gecontroleerd wordt			, Deze is verplicht
	 *		2 Operator		= De operator waarmee gecontroleerd wordt		, Deze is verplicht en kan één van de volgende dingen zijn:
	 *																				= , == , ! , != , < , <= , > , >= , like
	 *		3 Value			= De waarde van de voorwaarden					, Deze is verplicht
	 *		4 Casesensitive = Bij false wordt er hoofdlettergevoelig gezocht, Deze is niet verplicht en staat standaard op true (niet hoofdlettergevoelig)
	 **/
	public function filter($field, $operator, $value = '', $case = true)
	{

		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

		$field = mysqli_real_escape_string($db, $field);
		$results = $this->page_results;
		if (!is_array($results) || count($results) == 0) return $results;

		$array 	= array();
		$case 	= (is_bool($value) || is_numeric($value) || ($value instanceof Date) || ($value instanceof DateTime)) ? false : $case;
		$value 	= ($case) ? strtolower($value) : $value;

		foreach ($results as $key => $fields) {

			$field_value = ($case) ? strtolower($fields[$field]) : $fields[$field];

			$remove = false;
			switch (strtolower($operator)) {

				case '==':
				case '=':
					if ($field_value != $value) $remove = true;
					break;
				case '!=':
				case '!':
					if ($field_value == $value) $remove = true;
					break;
				case 'like':
					if (stripos($field_value, $value) === false) $remove = true;
					break;
				case '>':
					if ($field_value <= $value) $remove = true;
					break;
				case '<':
					if ($field_value >= $value) $remove = true;
					break;
				case '>=':
					if ($field_value < $value) $remove = true;
					break;
				case '<=':
					if ($field_value > $value) $remove = true;
					break;
			}
			if (!$remove) $array[] = $fields;
		}

		$this->page_results = $array;
	}

	public function getResults()
	{
		return $this->page_results;
	}

	private function getIconArray()
	{
		include_once('icons.class.php');
		$icons = new Icons();
		return $icons->get_icons();
	}
}
?>