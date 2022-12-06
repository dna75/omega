<?php
$user->check_right('galleries_edit', true);

//include_once('include/upload.class.php');


$album_id = mysqli_real_escape_string($db, $_GET['id']);

$album = $db->query("select 			*
						FROM 			amplo_galleries
						WHERE 			id = $album_id
						LIMIT			1
						");
$album = mysqli_fetch_array($album);


$destpath = "../upload/gallery/" . date("Ymdhis" , strtotime($album['directory'])) . "/";

$result = $db->query("select 			*
						FROM 			amplo_images
						WHERE			album_id = $album_id
						ORDER BY 		id ASC
						");


if (isset($_GET['del']) && intval($_GET['del']) > 0 && $user->check_right('galleries_delete'))
{

	$id = mysqli_real_escape_string($db, $_GET['del']);

	$image = $db->query("select 			*
						FROM 			amplo_images
						WHERE			id = $id
						LIMIT			1
						");
	$image = mysqli_fetch_array($image);

	unlink($destpath . $image['filename']);
	unlink($destpath . "thumb/" . $image['filename']);
	unlink($destpath . "orig/" . $image['filename']);
	$db->query("DELETE FROM amplo_images WHERE id=$id");

	header('Location: /cockpit/index.php?page=gallery&id=' . $album_id);

}


?>


<p class="bewerken">Fotoalbum: '<?php echo $album['name']; ?>'</p>
<p>Bewerk de foto's van dit fotoalbum</p>

<!-- Load Queue widget CSS and jQuery -->
<style type="text/css">@import url(	/cockpit/scripts/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css);</style>

<!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
<script type="text/javascript" src="/cockpit/scripts/plupload/js/plupload.full.min.js"></script>
<script type="text/javascript" src="/cockpit/scripts/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js"></script>

<script type="text/javascript">
// Convert divs to queue widgets when the DOM is ready
$(function() {
	$("#uploader").pluploadQueue({

		url : 'ajax/plupload.php',
		max_file_size : '15mb',

		// Resize images on clientside if we can
		resize : {width : 1000, height : 1000, quality : 100},
		filters : [
			{title : "Image files", extensions : "jpg,gif,png"}
		],
		multipart_params : {
			"album_id" : "<?php echo $album_id; ?>"
		},
		preinit: attachCallbacks
	});



	// Client side form validation
	$('form').submit(function(e) {
        var uploader = $('#uploader').pluploadQueue();

        // Files in queue upload them first
        if (uploader.files.length > 0) {
            // When all files are uploaded submit form
            uploader.bind('StateChanged', function() {
                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                    $('form')[0].submit();
					window.location = 'gallery.php';
                }
            });

			uploader.bind('Error', function(up, err) {
				document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
			});

            uploader.start();
        } else {
            alert('You must queue at least one file.');
        }

        return false;
    });

	function attachCallbacks(uploader) {

		uploader.bind('FileUploaded', function(Up, File, Response) {

		  if( (uploader.total.uploaded + 1) == uploader.files.length) {

			window.location.reload();
		  }
		});
	}
});
</script>

<form ..>
	<div id="uploader">
		<p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
	</div>
</form>
<div id="console"></div>

<table class="table table-striped table-hover">
	<thead>
		<tr>
			<td>Foto</td>
			<td>Naam</td>
			<td>Opties</td>
		</tr>
	</thead>
	<tbody>
		<?php while ($row = mysqli_fetch_array($result))
	{ ?>

			<tr>
				<td><img width="75px" height="75px" class="zoom" src="<?php echo $destpath . 'thumb/' . $row['filename']; ?>" /></td>
				<td><?php echo $row['filename']; ?></td>
				<td>
					<?php if ($user->check_right('galleries_edit') && true == false)
		{ ?>
					<a class="btn btn-default btn-xs" href="index.php?page=image&id=<?php echo $row['id']; ?>" title="Klik hier om deze foto te bewerken"><i class="fa fa-pencil fa-border"></i></a>
					<?php } ?>
					<?php if ($user->check_right('galleries_delete'))
		{ ?>
						<a class="btn btn-xs btn-danger" style="color:white;" onclick="return confirm('Weet u zeker dat u deze foto wilt verwijderen?');" href="index.php?page=gallery&id=<?=$album_id;?>&del=<?php echo $row['id']; ?>" title="Klik hier om deze foto te verwijderen"><i class="fa fa-trash-o "></i></a>
					<?php } ?>
				</td>
			</tr>
		<? } ?>
	</tbody>
</table>

<br />
<a href="/cockpit/index.php?page=galleries" class="btn btn-medim btn-default">
	<i class="fa fa-chevron-left"> Terug</i>
</a>
