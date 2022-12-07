// Custom JS for the Theme

// Equal Heights
$(function () {
	$(".item").matchHeight();
	$(".item1").matchHeight();
	$(".item2").matchHeight();
	$(".item3").matchHeight();
	$(".item4").matchHeight();
	$(".item5").matchHeight();
});

// Config
//-------------------------------------------------------------

var companyName = "Omega Autoverhuur"; // Enter your event title

// Initialize Tooltip
//-------------------------------------------------------------

// Initialize jQuery Placeholder
//-------------------------------------------------------------

$("input, textarea").placeholder();

// Toggle Header / Nav
//-------------------------------------------------------------

$(document).on("scroll", function () {
	if ($(document).scrollTop() > 39) {
		$("header").removeClass("large").addClass("small");
	} else {
		$("header").removeClass("small").addClass("large");
	}
});

// Vehicles Tabs / Slider
//-------------------------------------------------------------

$(".vehicle-data").hide();
var activeVehicleData = $(".vehicle-nav .active a").attr("href");
$(activeVehicleData).show();

$(".vehicle-nav-scroll").click(function () {
	var direction = $(this).data("direction");
	var scrollHeight = $(".vehicle-nav li").height() + 1;
	var navHeight = $("#vehicle-nav-container").height() + 1;
	var actTopPos = $(".vehicle-nav").position().top;
	var navChildHeight = $("#vehicle-nav-container").find(".vehicle-nav").height();
	var x = -(navChildHeight - navHeight);

	var fullHeight = 0;
	$(".vehicle-nav li").each(function () {
		fullHeight += scrollHeight;
	});

	navHeight = fullHeight - navHeight + scrollHeight;

	// Scroll Down
	if (direction == "down" && actTopPos > x && -navHeight <= actTopPos - scrollHeight * 2) {
		topPos = actTopPos - scrollHeight;
		$(".vehicle-nav").css("top", topPos);
	}

	// Scroll Up
	if (direction == "up" && 0 > actTopPos) {
		topPos = actTopPos + scrollHeight;
		$(".vehicle-nav").css("top", topPos);
	}

	return false;
});

$(".vehicle-nav li").on("click", function () {
	$(".vehicle-nav .active").removeClass("active");
	$(this).addClass("active");

	$(activeVehicleData).fadeOut("slow", function () {
		activeVehicleData = $(".vehicle-nav .active a").attr("href");
		$(activeVehicleData).fadeIn("slow", function () {});
	});

	return false;
});

// Vehicles Responsive Nav
//-------------------------------------------------------------

$("<div />").appendTo("#vehicle-nav-container").addClass("styled-select-vehicle-data");
$("<select />").appendTo(".styled-select-vehicle-data").addClass("vehicle-data-select");
$("#vehicle-nav-container a").each(function () {
	var el = $(this);
	$("<option />", {
		value: el.attr("href"),
		text: el.text(),
	}).appendTo("#vehicle-nav-container select");
});

$(".vehicle-data-select").change(function () {
	$(activeVehicleData).fadeOut("slow", function () {
		activeVehicleData = $(".vehicle-data-select").val();
		$(activeVehicleData).fadeIn("slow", function () {});
	});

	return false;
});

// Scroll to Top Button
//-------------------------------------------------------------------------------

$(window).scroll(function () {
	if ($(this).scrollTop() > 100) {
		$(".scrollup").removeClass("animated fadeOutRight");
		$(".scrollup").fadeIn().addClass("animated fadeInRight");
	} else {
		$(".scrollup").removeClass("animated fadeInRight");
		$(".scrollup").fadeOut().addClass("animated fadeOutRight");
	}
});

$(".scrollup, .navbar-brand").click(function () {
	$("html, body").animate({ scrollTop: 0 }, "slow", function () {
		$("nav li a").removeClass("active");
	});
	return false;
});

// Scroll To Animation
//-------------------------------------------------------------------------------

var scrollTo = $(".scroll-to");

scrollTo.click(function (event) {
	$(".modal").modal("hide");
	var position = $(document).scrollTop();
	var scrollOffset = 110;

	if (position < 39) {
		scrollOffset = 260;
	}

	var marker = $(this).attr("href");
	$("html, body").animate({ scrollTop: $(marker).offset().top - scrollOffset }, "slow");

	return false;
});

// setup autocomplete - pulling from locations-autocomplete.js
//-------------------------------------------------------------------------------

$(".autocomplete-location").autocomplete({
	lookup: locations,
});

// Newsletter Form
//-------------------------------------------------------------------------------

$("#newsletter-form").submit(function () {
	$("#newsletter-form-msg").addClass("hidden");
	$("#newsletter-form-msg").removeClass("alert-success");
	$("#newsletter-form-msg").removeClass("alert-danger");

	$("#newsletter-form input[type=submit]").attr("disabled", "disabled");

	$.ajax({
		type: "POST",
		url: "php/index.php",
		data: $("#newsletter-form").serialize(),
		dataType: "json",
		success: function (data) {
			if ("success" == data.result) {
				$("#newsletter-form-msg").css("visibility", "visible").hide().fadeIn().removeClass("hidden").addClass("alert-success");
				$("#newsletter-form-msg").html(data.msg[0]);
				$("#newsletter-form input[type=submit]").removeAttr("disabled");
				$("#newsletter-form")[0].reset();
			}

			if ("error" == data.result) {
				$("#newsletter-form-msg").css("visibility", "visible").hide().fadeIn().removeClass("hidden").addClass("alert-danger");
				$("#newsletter-form-msg").html(data.msg[0]);
				$("#newsletter-form input[type=submit]").removeAttr("disabled");
			}
		},
	});

	return false;
});

// Not Empty Validator Function
//-------------------------------------------------------------------------------

function validateNotEmpty(data) {
	if (data == "") {
		return true;
	} else {
		return false;
	}
}
