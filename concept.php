<?php
if ($_SERVER['REMOTE_ADDR'] == '82.172.63.44') {
	$reportErrors = 'on';
} else {
	$reportErrors = 'off';
}

ini_set('display_errors', $reportErrors);
error_reporting(E_ALL);

ob_start();
include "./cockpit/include/config.inc.php";
//include "./functions/func.inc.php";
include "./functions/corefunc.inc.php";

$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

// Check connection
if ($db->connect_errno) {
	die('Sorry we have some database problems');
}

include($develop . '/include/language.class.php');

$lang = (isset($_GET['lang'])) ? $_GET['lang'] : '';
$language = new Language($lang);
$language->multilingual_site = false;

require_once($develop . '/include/CustomPages/CustomPages.class.php');

setlocale(
	LC_ALL,
	'Dutch_Netherlands',
	'Dutch',
	'nl_NL',
	'nl',
	'nl_NL.ISO8859-1',
	'nl_NL.UTF-8',
	'nld_nld',
	'nld',
	'nld_NLD',
	'NL_nl'
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Omega Autoverhuur - Leeuwarden</title>

	<!-- Bootstrap -->
	<link href="/css/styles.min.css" rel="stylesheet" />

	<!-- Bootstrap -->
	<link href="/css/animate.css" rel="stylesheet" />

	<!-- Google Font Lato -->
	<link href="http://fonts.googleapis.com/css?family=Lato:400,700,900,400italic,700italic,900italic" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="/resources/demos/style.css" />


	<?
	$items = new CustomPages('algemeen');
	$items->fetchOne(1); // je kan ook een id ophalen met: $items->fetchOne(87);

	$itemAlgemeen = $items->getResults();
	?>

</head>

<body id="top" data-spy="scroll" data-target=".navbar" data-offset="260">

	<?
	$start = $itemAlgemeen['startrental'];
	$end   = $itemAlgemeen['endrental'];
	?>

	<!-- #region Navbar -->
	<nav class="navbar fixed-top navbar-expand-lg bg-light">
		<div class="container">
			<a class="navbar-brand p-3" href="#top"><img src="/img/logo.svg" style="width: 180px" /></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarText">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item"><a href="#top" class="nav-link active scroll-to">HOME</a></li>
					<li><a href="#services" class="nav-link scroll-to">OVER ONS</a></li>
					<li><a href="#vehicles" class="nav-link active scroll-to">VOERTUIGEN</a></li>
					<!-- <li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Blog <span class="caret"></span></a>
										<ul class="dropdown-menu" role="menu">
											<li><a href="blog-posts.html">Blog Posts</a></li>
											<li><a href="blog-single-post.html">Blog Single Post</a></li>
											<li><a href="error404.html">Error 404</a></li>
										</ul>
									</li> -->
					<!-- <li><a href="#locations" class="nav-link active scroll-to">LOCATIE</a></li> -->
					<li><a href="#contact" class="nav-link active scroll-to">CONTACT</a></li>
				</ul>
				<span class="navbar-text  fw-bold"><span style="font-size:13px;">7 DAGEN P/W BEREIKBAAR VAN 08:00 - 22:00</span><br><i class="fa-light fa-mobile me-1"></i><i class="fa-brands fa-whatsapp me-1" style="color:green;"></i> <?= $itemAlgemeen['mobile']; ?></span>
			</div>
		</div>
	</nav>
	<!-- #endregion Navbar -->

	<!-- #region rental top -->
	<section id="teaser">
		<div class="container mt-5 pt-md-3">
			<div class="row">
				<div class="col-md-7 col-xs-12 order-md-2">
					<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
						<!-- Wrapper for slides start -->
						<div class="carousel-inner">

							<?
							$result = new CustomPages('slides');
							$result->fetch();

							// OPTIONAL STUFF
							$result->filter('active', '!=', '0');
							// $result->filter('gender'    , '!='    , '0');
							// $result->filter('date_to'    , '>='    , date('d-m-Y', strtotime('-12 hours')));
							// $result->sort('date_to', true);
							// $result->reverse();
							// $result->limit(100);
							// END OPTIONAL STUFF

							$i = 1;

							foreach ($result->getResults() as $row) {

								if ($i == 1) {
									$active = 'active';
								} else {
									$active = '';
								}
							?>

								<div class="carousel-item <?= $active; ?>">
									<h1 class="title"><?= $row['title']; ?><span class="subtitle yellow mb-3"><?= $row['subtitle']; ?></span></h1>
									<div class="d-none d-md-block">
										<img src="/upload/custom_pages/slides/<?= $row['picture']; ?>" class="img-fluid" alt="<?= $row['title']; ?>" />
									</div>
								</div>
							<?
								$i++;
							}
							?>
						</div>
						<!-- Wrapper for slides end -->

						<!-- Slider Controls start -->
						<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left"></span>
						</a>
						<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right"></span>
						</a>
						<!-- Slider Controls end -->
					</div>
				</div>
				<div class="col-md-5 col-sm-12 order-md-1">
					<div class="reservation-form-shadow">
						<h3 class="yellow text-uppercase fw-bold text-center">Bereken je prijs online</h3>
						<p class="text-center">Eenvoudig en snel een auto huren.</p>
						<!-- #region main -->
						<form action="" id="form" class="form-horizontal">
							<div class="row mb-3">
								<label for="car selection" class="col-form-label col-md-5 pt-2">
									<h5>Kies een voertuig</h5>
								</label>
								<div class="col-md-7">
									<select class="form-select form-select-lg" name="cartype" id="cartype">
										<option value="">Maak een keuze</option>
										<? $query = $db->query("SELECT * FROM `cars` order by `id` asc") or die(mysqli_error($db));
										while ($row = mysqli_fetch_array($query)) { ?>
											<option value="<?= db_escape($row['name']); ?>"><?= db_escape($row['name']); ?></option>
										<? } ?>
									</select>
								</div>
							</div>

							<div class="row" mb-3>
								<label for="Rental start" class="col-form-label col-md-5">Startdatum</label>
								<div class="col-md-7">
									<input type="text" name="startShow" class="form-control" id="datepicker" placeholder="Kies een datum" /></p>
									<input type="hidden" name="start" id="alt-date" />
								</div>
							</div>

							<div class="row mb-3">
								<label for="start time" class="col-form-label col-md-5">Starttijd</label>
								<div class="col-md-7">
									<select class="form-select" name="startTime" id="startTime">
										<?php echo dropdown($start, $end); ?>
									</select>
								</div>
							</div>

							<div class="row" mb-3>
								<label for="Rental end" class="col-form-label col-md-5">Einddatum</label>
								<div class="col-md-7">
									<input type="text" name="endShow" class="form-control" id="datepicker2" placeholder="Kies een datum" /></p>
									<input type="hidden" name="end" id="alt-date2" />
								</div>
							</div>

							<div class="row mb-3">
								<label for="end time" class="col-form-label col-md-5">Eindtijd</label>
								<div class="col-md-7">
									<select class="form-select" name="endTime" id="endTime">
										<?php echo dropdown($start, $end); ?>
									</select>
								</div>
							</div>

							<input type="hidden" name="days" id="days" />
							<input type="hidden" name="dayNumber" id="dayNumber" />

						</form>
						<!-- #endregion main -->

						<input type="hidden" id="totalSum" value="100" onchange="updateTotal()" />

						<div class="d-flex  p-5 py-3 bg-secondary justify-content-between">
							<div><span class="flex-grow-1 price">Huurprijs</span><br><span class="text-white">(exclusief eigenrisico)</span></div>
							<div class="price ms-1" id="result"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="arrow-down"></div>
	<!-- Teaser end -->
	<!-- #endregion -->

	<!-- #region services -->
	<!-- Services start -->
	<section id="services" class="container">
		<div class="row">
			<div class="col-md-12 title">
				<h2 class="title wow fadeInDown animated">WAAROM KIES JIJ VOOR OMEGA AUTOVERHUUR</h2>
				<span class=" underline">&nbsp;</span>
			</div>

			<?
			$result = new CustomPages('USP');
			$result->fetch();

			// OPTIONAL STUFF
			$result->filter('active', '!=', '0');

			foreach ($result->getResults() as $row) {
			?>

				<!-- Service Box start -->

				<div class="col-md-6 item">
					<div class="service-box wow fadeInLeft item2" data-wow-offset="100">
						<div class="service-icon">+</div>
						<div class="item3 service-title" style="height:50px;"><?= $row['title']; ?></div>
						<div class="clearfix"></div>
						<p class="service-content item4"><?= word_teaser($row['description'], 60); ?></p>
					</div>
				</div>
			<?  } ?>
		</div>
	</section>
	<!-- Services end -->
	<!-- #endregion -->

	<!-- #region Newsletter start -->
	<section id="newsletter" class="wow slideInLeft" data-wow-offset="300">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="alert hidden" id="newsletter-form-msg"></div>
				</div>
				<div class="col-md-5 col-xs-12">
					<h2 class="title">Blijf op de hoogte van speciale aanbiedingen <span class="subtitle">schrijf je in voor de Omega nieuwsbrief</span></h2>
				</div>
				<div class="col-md-7">
					<div class="newsletter-form pull-left">
						<form action="#" method="post" name="newsletter-form" id="newsletter-form">
							<input type="hidden" name="action" value="send_newsletter_form" />
							<div class="input-group">
								<input type="email" name="newsletter-email" class="form-control" placeholder="Voer je e-mailadres in" />
								<span class="input-group-btn">
									<input class="btn btn-default button" type="submit" value="VERSTUUR" />
								</span>
							</div>
						</form>
					</div>
					<!-- <div class="social-icons pull-right">
							<ul>
								<li>
									<a class="facebook" href="#"><i class="fa fa-facebook"></i></a>
								</li>
								<li>
									<a class="instagram" href="#"><i class="fa fa-instagram"></i></a>
								</li>
								<li>
									<a class="twitter" href="#"><i class="fa fa-linkedin"></i></a>
								</li>
							</ul> -->
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</section>
	<!-- #endregion Newsletter end -->

	<!-- #region Vehicles start -->
	<section id="vehicles" class="container">
		<div class="row">
			<div class="col-md-12">
				<h2 class="title wow fadeInDown" data-wow-offset="200">Onze voertuigen - <span class="subtitle">bekijk de mogelijkheden</span></h2>
			</div>

			<!-- Vehicle nav start -->

			<div class="col-md-3 vehicle-nav-row wow fadeInUp" data-wow-offset="100">
				<div id="vehicle-nav-container">
					<ul class="vehicle-nav">
						<?
						$query = $db->query("SELECT * FROM `cars` ORDER BY `id` ASC") or die(mysqli_error($db));
						$i = 1;

						while ($row = mysqli_fetch_array($query)) {
							if ($i == 1) {
								$active = "active";
							} else {
								$active = "";
							}
						?>
							<li class="<?= $active; ?>"><a href="#<?= $row['id']; ?>"><?= $row['name']; ?></a><span class="active">&nbsp;</span></li>
						<?
							$i++;
						}
						?>
					</ul>
				</div>

				<div class="vehicle-nav-control">
					<a class="vehicle-nav-scroll" data-direction="up" href="#"><i class="fa fa-chevron-up"></i></a>
					<a class="vehicle-nav-scroll" data-direction="down" href="#"><i class="fa fa-chevron-down"></i></a>
				</div>
			</div>
			<!-- Vehicle nav end -->

			<!-- Vehicle  data start -->
			<?
			$query = $db->query("SELECT * FROM `cars` ORDER BY `id` ASC") or die(mysqli_error($db));
			while ($row = mysqli_fetch_array($query)) {
				$queryFeatures = $db->query("
				SELECT `car_details`.`car_property`AS `property`, `car_properties`.`property` AS `carValue`  FROM `car_details` 
				LEFT JOIN `cars` 
				ON `car_details`.`car_id` = `cars`.`id`
				LEFT JOIN `car_properties`
				ON `car_properties`.`id` = `car_details`.`property_id`
				WHERE `car_details`.`car_id` = '" . $row['id'] . "'
				")
					or die(mysqli_error($db));
			?>

				<div class="vehicle-data col-md-9 col-12" id="<?= $row['id']; ?>">
					<div class="row">
						<div class="col-md-8 col-12 wow fadeIn" data-wow-offset="100">
							<div class="vehicle-img">
								<img class="img-fluid cars" src="/upload/cars/<?= $row['carimage']; ?>" alt="Huur een <?= $row['name']; ?> in Leeuwarden" />
							</div>
						</div>

						<div class="col-md-4 col-12 col wow fadeInUp" data-wow-offset="200">
							<div class="vehicle-price"><span class="info">Al vanaf</span> € <?= number_format((float)db_escape($row['priceamonth']), 2, ',', ''); ?> <span class="info">huur per dag</span></div>
							<table class="table vehicle-features">
								<? while ($rowFeatures = mysqli_fetch_array($queryFeatures)) { ?>
									<tr>
										<td><?= $rowFeatures['carValue']; ?></td>
										<td><?= $rowFeatures['property']; ?></td>
									</tr>
								<? } ?>
							</table>
							<a href="#teaser" class="reserve-button scroll-to"><span class="glyphicon glyphicon-calendar"></span> Reserveer nu</a>
						</div>
					</div>
				</div>
			<?
			}
			?>
		</div>
	</section>
	<!-- #endregion Vehicles end -->

	<!-- #region Reviews start -->
	<section id="reviews" class="wow fadeInUp data-wow-offset=50">
		<div class="container">
			<div class="row text-center">
				<div class="col-md-12 stars">
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star big"></span>
					<span class="glyphicon glyphicon-star"></span>
					<span class="glyphicon glyphicon-star"></span>
				</div>
				<div class="col-md-8 offset-md-2">
					<!-- <div id="reviews-carousel" class="carousel slide carousel-fade" data-ride="carousel"> -->
					<div id="reviews-carousel" class="carousel slide" data-bs-ride="carousel">
						<div class="carousel-inner">
							<!-- Review item 1 start -->
							<div class="carousel-item active">
								<div class="review">Lorem ipsum dolor sit amet, officia excepteur ex fugiat reprehenderit enim labore culpa sint ad nisi Lorem pariatur mollit ex esse exercitation amet. Nisi anim cupidatat excepteur officia. Reprehenderit nostrud nostrud ipsum Lorem est aliquip amet voluptate voluptate dolor minim nulla est proident. Nostrud officia pariatur ut officia. Sit irure elit esse ea nulla sunt ex occaecat reprehenderit commodo officia dolor Lorem duis laboris cupidatat officia voluptate. Culpa proident adipisicing id nulla nisi laboris ex in Lorem sunt duis officia eiusmod. Aliqua reprehenderit commodo ex non excepteur duis sunt velit enim. Voluptate laboris sint cupidatat ullamco ut ea consectetur et est culpa et culpa duis.</div>
								<div class="author">– Nanne</div>
							</div>
							<!-- Review item 1 end -->

							<!-- Review item 2 start -->
							<div class="carousel-item">
								<div class="review">Lorem ipsum dolor sit amet, officia excepteur ex fugiat reprehenderit enim labore culpa sint ad nisi Lorem pariatur mollit ex esse exercitation amet. Nisi anim cupidatat excepteur officia. Reprehenderit nostrud nostrud ipsum Lorem est aliquip amet voluptate voluptate dolor minim nulla est proident. Nostrud officia pariatur ut officia. Sit irure elit esse ea nulla sunt ex occaecat reprehenderit commodo officia dolor Lorem duis laboris cupidatat officia voluptate. Culpa proident adipisicing id nulla nisi laboris ex in Lorem sunt duis officia eiusmod. Aliqua reprehenderit commodo ex non excepteur duis sunt velit enim. Voluptate laboris sint cupidatat ullamco ut ea consectetur et est culpa et culpa duis.</div>
								<div class="author">– Nanne</div>
							</div>
							<!-- Review item 2 end -->
						</div>

						<!-- Review Nav start -->
						<ol class="carousel-indicators">
							<li data-target="#reviews-carousel" data-slide-to="0" class="active"></li>
							<li data-target="#reviews-carousel" data-slide-to="1"></li>
							<li data-target="#reviews-carousel" data-slide-to="2"></li>
						</ol>
						<!-- Review Nav end -->
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- #endregion Reviews end -->

	<!-- Locations start -->
	<!-- <section id="locations">
			<div class="container location-select-container wow bounceInDown" data-wow-offset="200">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="location-select">
							<div class="row">
								<div class="col-md-6">
									<h2>Car Rental Locations</h2>
								</div>
								<div class="col-md-6">
									<div class="styled-select-location">
										<select id="location-map-select"></select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="arrow-down-location">&nbsp;</div>
			</div>
			<div class="map wow bounceInUp" data-wow-offset="100">map by gmap3</div>
		</section> -->
	<!-- Locations end -->

	<!-- #region Information start -->
	<section id="information" class="container">
		<!-- Single photo start -->
		<div class="row wow fadeInLeft" data-wow-offset="100">
			<div class="col-md-6 col-xs-12 order-md-2">
				<img src="img/golf.jpeg" alt="Info Img" class="img-fluid" />
			</div>
			<div class="col-md-6 order-md-1">
				<h2 class="title">Altijd kwaliteit</h2>
				<h3 class="subtitle">Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat.</h3>
				<p>Lorem ipsum dolor sit amet, officia excepteur ex fugiat reprehenderit enim labore culpa sint ad nisi Lorem pariatur mollit ex esse exercitation amet. Nisi anim cupidatat excepteur officia. Reprehenderit nostrud nostrud ipsum Lorem est aliquip amet voluptate voluptate dolor minim nulla est proident. Nostrud officia pariatur ut officia. Sit irure elit esse ea nulla sunt ex occaecat reprehenderit commodo officia dolor Lorem duis laboris cupidatat officia voluptate. Culpa proident adipisicing id nulla nisi laboris ex in Lorem sunt duis officia eiusmod. Aliqua reprehenderit commodo ex non excepteur duis sunt velit enim. Voluptate laboris sint cupidatat ullamco ut ea consectetur et est culpa et culpa duis.</p>
				<a href="" class="btn">Lees meer</a>
			</div>
		</div>
		<!-- Single photo end -->

		<!-- Video start -->
		<div class="row wow fadeInRight" data-wow-offset="50">
			<div class="col-md-6">
				<img src="img/golf.jpeg" alt="Info Img" class="img-fluid" />
				<!-- <div class="video">
						<iframe width="420" height="315" src="" allowfullscreen></iframe>
					</div> -->
			</div>
			<div class="col-md-6">
				<h2 class="title">Zorgvuldig en betrouwbaar</h2>
				<!-- <h3 class="subtitle">You can also show youtube videos in this section!</h3> -->
				<p>Lorem ipsum dolor sit amet, officia excepteur ex fugiat reprehenderit enim labore culpa sint ad nisi Lorem pariatur mollit ex esse exercitation amet. Nisi anim cupidatat excepteur officia. Reprehenderit nostrud nostrud ipsum Lorem est aliquip amet voluptate voluptate dolor minim nulla est proident. Nostrud officia pariatur ut officia. Sit irure elit esse ea nulla sunt ex occaecat reprehenderit commodo officia dolor Lorem duis laboris cupidatat officia voluptate. Culpa proident adipisicing id nulla nisi laboris ex in Lorem sunt duis officia eiusmod. Aliqua reprehenderit commodo ex non excepteur duis sunt velit enim. Voluptate laboris sint cupidatat ullamco ut ea consectetur et est culpa et culpa duis.</p>
			</div>
		</div>
		<!-- Video end -->
	</section>
	<!-- #endregion Information end -->

	<!-- Partners start -->
	<!-- <section id="partners" class="wow fadeIn" data-wow-offset="50">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2>Meet Our Partners</h2>
						<span class="underline">&nbsp;</span>
						<p>To contribute to positive change and achieve our sustainability goals, we partner with many extraordinary organizations around the world. Their expertise enables us to do far more than we could alone, and their passion and talent inspire us. It is our pleasure to introduce you to a handful of the organizations whose accomplishments and commitments are representative of all the organizations we are fortunate to call our partners.</p>
					</div>
					<div class="col-md-3 col-xs-6 text-center">
						<img src="img/partner1.png" alt="Partner" class="img-fluid wow fadeInUp" data-wow-delay="0.5s" data-wow-offset="200" />
					</div>
					<div class="col-md-3 col-xs-6 text-center">
						<img src="img/partner2.png" alt="Partner" class="img-fluid wow fadeInUp" data-wow-delay="1s" data-wow-offset="200" />
					</div>
					<div class="col-md-3 col-xs-6 text-center">
						<img src="img/partner3.png" alt="Partner" class="img-fluid wow fadeInUp" data-wow-delay="1.5s" data-wow-offset="200" />
					</div>
					<div class="col-md-3 col-xs-6 text-center">
						<img src="img/partner4.png" alt="Partner" class="img-fluid wow fadeInUp" data-wow-delay="2s" data-wow-offset="200" />
					</div>
				</div>
			</div>
		</section> -->
	<!-- Partners end -->

	<!-- #region Contact start -->
	<section id="contact" class="container wow bounceInUp" data-wow-offset="50">
		<div class="row">
			<div class="col-md-12">
				<h2>Neem contact met ons op</h2>
			</div>
			<div class="row">
				<div class="col-md-7">
					<form action="#" method="post" id="contact-form" name="contact-form">
						<input type="hidden" name="action" value="Verstuur" />


						<div class="form-group">
							<input type="text" class="form-control first-name text-field" name="first-name" placeholder="Voornaam" />
							<input type="text" class="form-control last-name text-field" name="last-name" placeholder="Achternaam" />
							<div class="clearfix"></div>
						</div>

						<div class="form-group">
							<input type="tel" class="form-control telephone text-field" name="telephone" placeholder="Telefoon" />
						</div>

						<div class="form-group">
							<input type="email" class="form-control email text-field" name="email" placeholder="Email" />
						</div>

						<div class="form-group">
							<textarea class="form-control message" name="message" placeholder="Bericht / vraag"></textarea>
						</div>

						<input type="submit" class="btn submit-message" name="submit-message" value="Verstuur" />
					</form>
				</div>
				<div class="col-md-5">
					<p class="contact-info">
						Meer informatie of een vraag over de verhuurmogelijkheden? <br />
						<span class="address"><span class="highlight">Adres: </span><Br><?= $itemAlgemeen['companyname']; ?><br><?= $itemAlgemeen['address']; ?><br><? $itemAlgemeen['zipcode']; ?> <?= $itemAlgemeen['city']; ?></span>
					</p>
					<p class="contact-info">
						Neem contact met ons op via:<Br>
						<i class="fa-light fa-mobile me-2"></i><i class="me-2 fa-brands fa-whatsapp"></i> 06 51 51 90 90<br>
						7 DAGEN P/W BEREIKBAAR VAN 08:00 - 22:00
					</p>

					<p class="contact-info"><span class=" highlight">Openingstijden kantoor:</span>
						<?
						$items = new CustomPages('openingstijden');
						$items->fetchOne(66); // je kan ook een id ophalen met: $items->fetchOne(87);

						$item = $items->getResults();

						if (!empty($item)) { ?>
							<br>Maandag t/m vrijdag: <?= $item['mondaytofridayfrom']; ?> - <?= $item['mondaytofridayto']; ?>
							<br>Zaterdag: <?= $item['saturdayfrom']; ?> - <?= $item['saturdayto']; ?>
							<br>Zondag: <?= (!empty($item['sundayclosed'])) ? $item['sundayfrom'] . ' - ' . $item['sundayto'] : 'Gesloten'; ?>
						<? } ?>
				</div>
			</div>
		</div>
	</section>
	<!-- #endregion Contact end -->

	<a href="#" class="scrollup">ScrollUp</a>

	<!-- Footer start -->
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="clearfix"></div>
					<p class="copyright">©2022 Omega Autoverhuur, All Rights Reserved</p>
				</div>
			</div>
		</div>
	</footer>
	<!-- Footer end -->

	<!-- Checkout Modal Start -->
	<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="#" method="post" id="checkout-form" name="checkout-form">
					<input type="hidden" name="action" value="send_inquiry_form" />

					<!-- Modal header start -->
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Reservering afronden</h4>
					</div>
					<!-- Modal header end -->

					<!-- Modal body start -->
					<div class="modal-body">
						<!-- Checkout Info start -->
						<div class="checkout-info-box">
							<h3><i class="fa fa-info-circle"></i> Nulla tempor velit proident non incididunt.</h3>
							<p>Est nulla id excepteur aute ipsum sint anim non et fugiat aute cillum anim consectetur.</p>
						</div>
						<!-- Checkout Info end -->

						<!-- Checkout Rental Info start -->
						<div class="checkout-vehicle-info">
							<div class="location-date-info">
								<h3>Gegevens verhuur</h3>
								<div class="info-box">
									<span class="glyphicon glyphicon-calendar"></span>
									<h4 class="info-box-title">Afhaal datum / tijd</h4>
									<p class="info-box-description"><span id="pick-up-date-ph"></span> at <span id="pick-up-time-ph"></span></p>
									<input type="hidden" name="pick-up" id="pick-up" value="" />
								</div>
								<div class="info-box">
									<span class="glyphicon glyphicon-calendar"></span>
									<h4 class="info-box-title">Retour datum / tijd</h4>
									<p class="info-box-description"><span id="drop-off-date-ph"></span> at <span id="drop-off-time-ph"></span></p>
									<input type="hidden" name="drop-off" id="drop-off" value="" />
								</div>
								<!-- <div class="info-box">
										<span class="glyphicon glyphicon-map-marker"></span>
										<h4 class="info-box-title">Pick-Up Location</h4>
										<p class="info-box-description" id="pickup-location-ph"></p>
										<input type="hidden" name="pickup-location" id="pickup-location" value="" />
									</div> -->
								<!-- <div class="info-box">
										<span class="glyphicon glyphicon-map-marker"></span>
										<h4 class="info-box-title">Drop-Off Location</h4>
										<p class="info-box-description" id="dropoff-location-ph"></p>
										<input type="hidden" name="dropoff-location" id="dropoff-location" value="" />
									</div> -->
							</div>

							<div class="vehicle-info">
								<h3>Voertuig: <span id="selected-car-ph"></span></h3>
								<a href="#vehicles" class="scroll-to">[Model]</a>
								<input type="hidden" name="selected-car" id="selected-car" value="" />
								<div class="clearfix"></div>
								<div class="vehicle-image">
									<img class="img-fluid" id="selected-vehicle-image" src="img/vehicle1.jpg" alt="Vehicle" />
								</div>
							</div>

							<div class="clearfix"></div>
						</div>
						<!-- Checkout Rental Info end -->

						<hr />

						<!-- Checkout Personal Info start -->
						<div class="checkout-personal-info">
							<div class="alert hidden" id="checkout-form-msg">test</div>
							<h3>Jouw gegevens</h3>
							<div class="form-group left">
								<label for="first-name">Voornaam</label>
								<input type="text" class="form-control" name="first-name" id="first-name" placeholder="Voer je voornaam in" />
							</div>
							<div class="form-group right">
								<label for="last-name">Achternaam</label>
								<input type="text" class="form-control" name="last-name" id="last-name" placeholder="Voer je achternaam in" />
							</div>
							<div class="form-group left">
								<label for="phone-number">Telefoonnummer</label>
								<input type="text" class="form-control" name="phone-number" id="phone-number" placeholder="Voer je telefoonnummer in" />
							</div>
							<div class="form-group right age">
								<label for="age">Leeftijd</label>
								<div class="styled-select-age">
									<select name="age" id="age">
										<option value="18">18</option>
										<option value="19">19</option>

									</select>
								</div>
							</div>
							<div class="form-group left">
								<label for="email-address">E-mailadres:</label>
								<input type="email" class="form-control" name="email-address" id="email-address" placeholder="Voer je e-mailadres in" />
							</div>
							<div class="form-group right">
								<label for="email-address-confirm">Bevestig e-mailadres</label>
								<input type="email" class="form-control" name="email-address-confirm" id="email-address-confirm" placeholder="Herhaal je e-mailadres" />
							</div>
							<div class="clearfix"></div>
						</div>
						<!-- Checkout Personal Info end -->

						<!-- Checkout Address Info start -->
						<div class="checkout-address-info">
							<div class="form-group address">
								<label for="address">Adres</label>
								<input type="text" class="form-control" name="address" id="address" placeholder="Straat + huisnummer" />
							</div>
							<div class="form-group city">
								<label for="city">Plaats</label>
								<input type="text" class="form-control" name="city" id="city" placeholder="Voer je woonplaats in" />
							</div>
							<div class="form-group zip-code">
								<label for="zip-code">Postcode</label>
								<input type="text" class="form-control" name="zip-code" id="zip-code" placeholder="Voer je postcode in" />
							</div>
							<div class="clearfix"></div>
						</div>
						<!-- Checkout Address Info end -->

						<div class="newsletter">
							<div class="form-group">
								<div class="checkbox">
									<input id="check1" type="checkbox" name="newsletter" value="yes" />
									<label for="check1">Ja ik wil me inschrijven voor de nieuwsbrief en aanbiedingen</label>
								</div>
							</div>
						</div>
					</div>
					<!-- Modal body end -->

					<!-- Modal footer start -->
					<div class="modal-footer">
						<span class="btn-border btn-gray">
							<button type="button" class="btn btn-default btn-gray" data-dismiss="modal">Annuleer</button>
						</span>
						<span class="btn-border btn-yellow">
							<button type="submit" class="btn btn-primary btn-yellow">Reserveer nu</button>
						</span>
					</div>
					<!-- Modal footer end -->
				</form>
			</div>
		</div>
	</div>
	<!-- Checkout Modal end -->

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="/js/jquery-1.11.0.min.js"></script>

	<!-- Include all compiled plugins (below), or include individual files as needed -->

	<script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

	<script src="/js/jquery.placeholder.js"></script>
	<!--[if !(gte IE 8)]><!-->
	<script src="/js/wow.min.js"></script>
	<script>
		// Initialize WOW
		//-------------------------------------------------------------
		new WOW({
			mobile: false
		}).init();
	</script>
	<!--<![endif]-->

	<script src="/js/custom.js"></script>
	<script src="/js/jquery.matchHeight.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
	<script src="https://kit.fontawesome.com/121652c01a.js" crossorigin="anonymous"></script>


	// disabled days datepicker
	<script>
		// Check disabled times
		$("#datepicker").bind("change keyup", function() {
			let d = new Date(document.getElementById("alt-date").value),
				month = '' + (d.getMonth() + 1),
				day = '' + d.getDate(),
				year = d.getFullYear();
			date = [year, month, day].join('-');

			if (date == "2022-11-30") { // Check if times are disabled for this date
				// array disabled times
				let times = ["09:00", "09:15"];
				$("#startTime option").each(function() {
					if ($.inArray($(this).val(), times) != -1) {
						$(this).remove();
					}
				});
			}
			if (date != "2022-11-30") { // If times are not disabled for this date add times back to dropdown
				$("#startTime").html('<?php echo dropdown($start, $end); ?>');
			}
		});

		// datepicker disable specific dates
		var disabledDays = ["2022-11-28", "2022-11-29"];

		// pass disabled dates array to datepicker
		function disabled(date) {
			var string = jQuery.datepicker.formatDate("yy-mm-dd", date);
			return [disabledDays.indexOf(string) == -1];
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
				altFormat: "yy-mm-dd",
				altField: "#alt-date",
				firstDay: 1,
				changeMonth: true,
				changeYear: true,
				minDate: +2,
				beforeShowDay: disabled,
			});
		});

		// datepicker 2 
		$("#datepicker").bind("change keyup", function() {
			$("#datepicker2").datepicker("option", "minDate", $(this).val()); // reset minDate after changing Start date
			var minDate = ''; // create var
			var minDate = $("#datepicker").val(); // get value from datepicker (start date)

			var curEndDate = $("#datepicker2").val(); // get value from datepicker (end date)

			if (curEndDate <= minDate && curEndDate != '') { // if end date is smaller than start date clear end date
				document.getElementById('datepicker2').value = ''
			}

			// datepicker end date
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
				altFormat: "yy-mm-dd",
				altField: "#alt-date2",
				firstDay: 1,
				changeMonth: true,
				changeYear: true,
				minDate: minDate,
			});
		});

		// get all dates in between start and end date and put them in an array
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
				var days = ["zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag"];
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

			document.getElementById("dayNumber").value = dates;
		});

		$("#datepicker2, #datepicker, #startTime, #endTime, #cartype").change(function() {
			var cartype = $("#cartype").val();
			var start = $("#datepicker").val();
			var startTime = $("#startTime").val();
			var end = $("#datepicker2").val();
			var endTime = $("#endTime").val();
			var days = $("#days").val();
			var dayNumber = $("#dayNumber").val();
			var price = $("#price").val();
			var qty = $("#qty").val();
			var totalSum = $("#totalSum").val();
			$.ajax({
				type: "POST",
				url: "ajax.php?cartype=" + cartype + "&start=" + start + "&startTime=" + startTime + "&end=" + end + "&endTime=" + endTime + "&days=" + days + "&dayNumber=" + dayNumber + "&price=" + price + "&qty=" + qty + "&totalSum=" + totalSum,
				success: function(result) {
					$("#result").html(result);
				},
			});
		});
	</script>

</body>

</html>