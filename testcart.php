<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Bootstrap demo</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="/resources/demos/style.css" />
</head>

<body>
	<!-- #region main -->
	<h1 class="text-center">Hello World</h1>
	<form action="" id="form">
		<input type="text" id="name" name="name" value="John Doe" />
		<input type="text" id="qty" name="qty" value="1" onkeyup="dateDiff()" />
		<input type="text" id="price" name="price" value="100" onkeyup="dateDiff()" />
		<input type="text" name="country" value="USA" />
		<input type="text" name="email" value="" />
		<p>Start: <input type="text" name="startShow" id="datepicker" /></p>
		<p>Start: <input type="text" name="start" id="alt-date" /></p>
		<p>Einde: <input type="text" name="endShow" id="datepicker2" /></p>
		<p>Einde: <input type="text" name="end" id="alt-date2" /></p>
		<p>Dagen: <input type="text" name="days" id="days" /></p>
		<p>Dag nummer <input type="text" name="dayNumber" id="dayNumber" /></p>
	</form>
	<!-- #endregion main -->

	<input type="text" id="totalSum" value="100" onchange="updateTotal()" />
	<div id="total">Total</div>

	<div id="result" style="border: 3px solid red">RESULT DIV</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

	<script>
		// dynamic price update
		function updatePrice() {
			var price = 0;
			var qty = document.getElementById("qty").value;
			var price = document.getElementById("price").value;
			var total = qty * price;
			document.getElementById("totalSum").value = total;
			document.getElementById("total").innerHTML = total;
		}

		// Datepicker Start / End date
		$(function() {
			$("#datepicker").datepicker({
				closeText: "Sluiten",
				prevText: "Vorig",
				nextText: "Volgende",
				currentText: "Vandaag",
				monthNames: ["januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december"],
				monthNamesShort: ["jan", "feb", "mrt", "apr", "mei", "jun", "jul", "aug", "sep", "okt", "nov", "dec"],
				dayNames: ["zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag"],
				dayNamesShort: ["zon", "maa", "din", "woe", "don", "vri", "zat"],
				dayNamesMin: ["zo", "ma", "di", "wo", "do", "vr", "za"],
				weekHeader: "Wk",
				dateFormat: "dd-mm-yy",
				altFormat: "mm-dd-yy",
				altField: "#alt-date",
				firstDay: 1,
				changeMonth: true,
				changeYear: true,
				minDate: +2,
			});
		});

		$(function() {
			$("#datepicker2").datepicker({
				closeText: "Sluiten",
				prevText: "Vorig",
				nextText: "Volgende",
				currentText: "Vandaag",
				monthNames: ["januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december"],
				monthNamesShort: ["jan", "feb", "mrt", "apr", "mei", "jun", "jul", "aug", "sep", "okt", "nov", "dec"],
				dayNames: ["zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag"],
				dayNamesShort: ["zon", "maa", "din", "woe", "don", "vri", "zat"],
				dayNamesMin: ["zo", "ma", "di", "wo", "do", "vr", "za"],
				weekHeader: "Wk",
				dateFormat: "dd-mm-yy",
				altFormat: "mm-dd-yy",
				altField: "#alt-date2",
				firstDay: 1,
				changeMonth: true,
				changeYear: true,
				minDate: +2,
			});
		});


		$("#datepicker2, #datepicker").bind("change keyup", function() {
			let date1 = new Date(document.getElementById("alt-date").value);
			let date2 = new Date(document.getElementById("alt-date2").value);
			const diffTime = Math.abs(date2 - date1);
			const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
			document.getElementById("days").value = diffDays + "dagen";
			// display each date between start and end date
			const dates = []; // all dates between start and end date in an array
			for (let i = 0; i <= diffDays; i++) {
				let newDate = new Date(date1);
				newDate.setDate(newDate.getDate() + i);

				// Day Name
				var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
				var d = new Date(newDate);
				var dayName = days[d.getDay()];

				// Day number
				let dayNumber = newDate.getDay();
				let day = newDate.getDate();
				let month = newDate.getMonth() + 1;
				let year = newDate.getFullYear();
				let formattedDate = dayNumber + "-" + dayName + "-" + day + "-" + month + "-" + year;
				// format date Day Month Year

				dates.push(formattedDate);
			}

			console.log(dates);

			document.getElementById("dayNumber").value = dates;
		});

		$("#datepicker2").change(function() {
			var name = $("#name").val();
			var email = $("#email").val();
			var country = $("#country").val();
			var start = $("#alt-date").val();
			var end = $("#alt-date2").val();
			var days = $("#days").val();
			var dayNumber = $("#dayNumber").val();
			var price = $("#price").val();
			var qty = $("#qty").val();
			var totalSum = $("#totalSum").val();
			$.ajax({
				type: "POST",
				url: "ajax.php?name=" + name + "&email=" + email + "&country=" + country + "&start=" + start + "&end=" + end + "&days=" + days + "&dayNumber=" + dayNumber + "&price=" + price + "&qty=" + qty + "&totalSum=" + totalSum,
				data: $("#form").serialize(),
				success: function(result) {
					$("#result").html(result);
				},
			});
		});
	</script>
</body>

</html>