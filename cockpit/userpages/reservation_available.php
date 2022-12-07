<style>
	.switch {
		position: relative;
		display: inline-block;
		width: 60px;
		height: 34px;
	}

	.switch input {
		opacity: 0;
		width: 0;
		height: 0;
	}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 26px;
		width: 26px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}

	input:checked+.slider {
		background-color: #eb0c0c;
	}

	input:focus+.slider {
		box-shadow: 0 0 1px #2196F3;
	}

	input:checked+.slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
		border-radius: 34px;
	}

	.slider.round:before {
		border-radius: 50%;
	}

	.btn-success {
		border-radius: 0;
		border: none;
		border-bottom: 2px solid #000;
	}

	.done {
		border: yellow 3px solid;
	}
</style>


<!-- Block Full Day / Date -->
<div class="row complete" style="padding-bottom: 12px;">
	<div class="col-xs-12">
		<? // $queryDate = mysql_query("SELECT * FROM reservation_dates WHERE datum = DATE_FORMAT('". $_GET['date'] ."', '%d-%m-%Y') ");
		$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);
		$query = $db->query("SELECT * FROM reservation_dates WHERE datum = '" . db_escape($_GET['date']) . "' ");

		if (mysqli_num_rows($query) == 1) {
			$checked = 'checked';
		}

		if (mysqli_num_rows($query) == 0) {
			$checked = '';
		}
		?>
		<div class="form-check form-check-inline" style="padding-left: 17px;">
			<label class="switch">
				<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="<?= $_GET['date']; ?>" <?= $checked; ?>>
				<span class="slider round"></span>
			</label>
			<p style="margin-top: 4px; padding-left:80px; margin-top:-30px;">Blokkeer de hele dag</p>
		</div>
	</div>
</div>

<!-- Block Seperate times -->
<div class="row" id="result">

	<?
	$result = array();
	$query = $db->query("SELECT * FROM reservation_times WHERE datum = '" . db_escape($_GET['date']) . "' ");
	while ($row = mysqli_fetch_array($query)) {
		$result[] = $row['time'];
	}

	$timestamp = strtotime(date("Y-m-d") . " 09:00");

	for ($i = 0; $i <= 35; $i++) {

		$time = date('H:i', $timestamp);
		$time .= ' UUR';

		if (in_array($time, $result)) {
			$color = "background-color:red !important";
		} else {
			$color = "white";
		}

		$timestamp += 15 * 60;

		if (isset($checked) && $checked != '') {
			$color = 'background-color: red;';
		} ?>

		<div class="col-xs-4 col-md-3 col-lg-2" id="mydiv">
			<button type="button" id="<?= $time; ?>" class="updateTime btn btn-block btn-success btn-sm text-center" style="padding:10px; margin-bottom:10px; <?= $color; ?>" <? if (isset($checked) && $checked != '') {
																																													echo 'disabled';
																																												} ?>>
				<?= $time; ?>
			</button>
		</div>

		<div id="test" class="test">
		</div>

	<? } ?>
</div>
</div>


<script type="text/javascript">
	$(document).ready(function() {

		$('input').click(function() {
			var uniqueId2 = $(this).attr('id');
			var checkbox = document.getElementById("<?= $_GET['date']; ?>");

			if (checkbox.checked == true) {
				var status = 'checked';
			} else {
				var status = '';
			}

			$.ajax({
				url: './ajax/reservation_insert_dates.php',

				type: 'POST',
				data: {
					uniqueId2: uniqueId2,
					status: status
				},
				success: function(response) {
					window.location.reload(true);
					// return false;
				}
			});
		});

		//$('.updateTime').click(function(){
		$('#result').on('click', '.updateTime', function(e) {

			var getUrlParameter = function getUrlParameter(sParam) {
				var sPageURL = window.location.search.substring(1),
					sURLVariables = sPageURL.split('&'),
					sParameterName,
					i;

				for (i = 0; i < sURLVariables.length; i++) {
					sParameterName = sURLVariables[i].split('=');

					if (sParameterName[0] === sParam) {
						return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
					}
				}
			};

			var uniqueId = $(this).attr('id');
			var sdate = getUrlParameter('date');

			$.ajax({
				cache: false,
				url: './ajax/reservation_insert_times.php',
				type: 'POST',
				data: {
					uniqueId: uniqueId,
					sdate: sdate
				},

				success: function() {
					//$("#result").html(mydiv);
					//$("#mydiv").load("./reservation_available.php")

					$("#result").load(location.href + ' #mydiv');
					//$("p:first").addClass("intro");

					//$("#result").html(#mydiv);

					//window.location.reload(true);
					//alert("The paragraph was clicked.");

				},
				async: "false",
			});
		});
	});
</script>