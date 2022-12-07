<?php
function get_tree($prefix = '01', $level = 6)
{
	global $user;

	$options = array();

	if ($user->check_right('page'))				$options[] = '<li class="sub-menu"><a href="index.php?page=pages"><i class="fa fa-pencil-square-o"></i>Pagina beheer</a></li>';
	if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=algemeen"><i class="fa fa-link"></i>Algemene gegevens</a></li>';
	if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=slides"><i class="fa fa-link"></i>Slider voorpagina beheer</a></li>';
	if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=auto"><i class="fa fa-link"></i>Auto beheer</a></li>';
	if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=reserveren"><i class="fa fa-link"></i>Reserveringen beheer</a></li>';
	if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=usp"><i class="fa fa-link"></i>USP beheer</a></li>';
	// if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=reviews"><i class="fa fa-link"></i>Review beheer</a></li>';
	// if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=galleries"><i class="fa fa-link"></i>Fotoalbums</a></li>';
	// if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=menukaart"><i class="fa fa-link"></i>Menukaart beheer</a></li>';
	// if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=arrangementen"><i class="fa fa-link"></i>Arrangementen beheer</a></li>';
	if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=openingstijden"><i class="fa fa-link"></i>Openingstijden</a></li>';
	// if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=galleries"><i class="fa fa-link"></i>Fotoalbums</a></li>';
	// if ($user->check_right('links'))			$options[] = '<li class="sub-menu"><a href="index.php?page=voordelen"><i class="fa fa-link"></i>Voordelen beheer</a></li>';
	if ($user->check_right('users')) 			$options[] = '<li class="sub-menu"><a class="padding" href="index.php?page=users"><i class="fa fa-users"></i>Gebruikersbeheer</a></li>';
	if ($user->check_right('rights_edit')) 		$options[] = '<li class="sub-menu"><a class="padding" href="index.php?page=rights"><i class="fa fa-key"></i>Rechtenbeheer</a></li>';
	if ($user->check_right('languages_edit')) 	$options[] = '<li class="sub-menu"><a class="padding" href="index.php?page=languages"><i class="fa fa-flag"></i>Talenbeheer</a></li>';
	if ($user->check_right('languages_edit')) 	$options[] = '<li class="sub-menu"><a class="padding" href="index.php?page=languagefields"><i class="fa fa-flag"></i>Taalvelden</a></li>';
	if ($user->check_right('users')) 			$options[] = '<li class="sub-menu"><a class="padding" href="index.php?page=admindetails"><i class="fa fa-flag"></i>Admin control panel</a></li>';

	/* 		$options[] = '<i class="fa fa-power-off"></i><a class="padding" href="index.php?page=logout">Logout</a>'; */

	$array = array(
		'' => $options,
	);

	return build_list('li', $array, array('' => 'menu', 'class' => 'active'));
}
