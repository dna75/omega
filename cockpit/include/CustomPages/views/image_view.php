<?
if (isset($_POST['crop']) && $_POST['crop']) {

	$this->crop_image();
}

defined('CUSTOMPAGES') or die('No direct script access alllowed');

$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

$images = $this->hasImages();
$result = $this->fetchOne($id, 'nl', false, false);

$tsrc = $destpath 	= '../upload/custom_pages/'.$this->page . '/' . $result[$images[$this->nr]['field']];
$exif = @exif_read_data($tsrc);
$ort = (isset($exif['Orientation'])) ? $exif['Orientation'] : 0;
list($sourceWidth, $sourceHeight, $type, $attr) = getimagesize($tsrc);

?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#image').imgAreaSelect({
            handles		: true,
            <? if ($images[$this->nr]['aspect_ratio']) { ?>
            	aspectRatio : "<?=$images[$this->nr]['max_width']; ?>:<?=$images[$this->nr]['max_height']; ?>",
            <? } ?>
            x1 			: 5,
            y1 			: 5,
            x2 			: <?=$sourceWidth - 5; ?>,
            y2 			: <?=(($images[$this->nr]['aspect_ratio'])) ? round(($sourceWidth - 10) * ($images[$this->nr]['max_height']/$images[$this->nr]['max_width'])) : $sourceHeight - 5; ?>,
            onSelectEnd	: function (img, selection) {
                $('input[name="x1"]').val(selection.x1);
                $('input[name="y1"]').val(selection.y1);
                $('input[name="x2"]').val(selection.x2);
                $('input[name="y2"]').val(selection.y2);
            }
        });
    });
</script>


<? if ($images!=false && $images[$this->nr]['field'] != '') { ?>

	<p><img src="<?=UPLOAD_PATH_CUSTOM. $this->page . '/' . $result[$images[$this->nr]['field']]; ?>" title="" id="image" /></p>

	<p>
		<form action="" method="post">
		    <input type="hidden" name="crop" value="true" />
		    <input type="hidden" name="field" value="<?=$images[$this->nr]['field']; ?>" />
		    <input type="hidden" name="id" value="<?php echo $id; ?>" />
		    <input type="hidden" name="x1" value="5" />
		    <input type="hidden" name="y1" value="5" />
		    <input type="hidden" name="x2" value="<?=$sourceWidth - 5; ?>" />
		    <input type="hidden" name="y2" value="<?=(($images[$this->nr]['aspect_ratio'])) ? ($sourceWidth - 10) * ($images[$this->nr]['max_height']/$images[$this->nr]['max_width']) : $sourceHeight - 5; ?>" />
		    <input type="submit" class="btn btn-success" value="<?=(count($images) > $this->nr+1) ? 'Opslaan en volgende' : 'Opslaan' ;  ?>" />
		</form>
	</p>

<? } ?>
