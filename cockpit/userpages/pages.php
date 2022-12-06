<?php
defined('SPINNERZ_INDEX') or die('Access denied.');

$user->check_right('page', true);

require_once($develop.'/include/CustomPages/CustomPages.class.php');


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
 *	options			= You may use more than one option with a seperating space. The example 100 value below can be 
 *						altered, but there should never be a space inside the key=value construction.
 *
 *						The following options are currently available:
 *						- multilingual 		= Whether or not this field is multilingual (images cannot be multilingual)
 *						- required			= When selected the field is required
 *						- hide_overview		= Whether or not this field is shown in the overview in the backend
 *						- min_width=100		= Minimum image-width
 *						- min_height=100	= Minimum image-height
 *						- max_width=100		= Maximum image-width
 *						- max_height=100	= Maximum image-height
 *						- aspect_ratio		= When the image aspect-ratio is set the ratio is max_width:max_height
 *						- thumb_width=100	= Maximum thumb-width
 *						- thumb_height=100	= Maximum thumb-height
 *						- thumb_crop_on		= Whether or not the thumbnail should be cropped
 *						- hide_editor		= Hide the editor in an textarea
 *						
 *
 *	NOTES: 	- THE FIELDS ARE SHOWN IN THE ORDER OF THE FIELDS ARRAY, BUT THE MULTILINGUAL FIELDS
 *				ARE ALWAYS SHOWN LAST!!!
 *			- IMAGES CAN NEVER BE MULTILINGUAL
 *						- 
 **/

$pageName 	= 'pages'; 

$fields 	= array(
	'pagina' 				=> array('Pagina titel'					, 'text'		, 'multilingual','','De wordt als titel in de browser getoond (niet in de website)'),
	'menu_title'			=> array('Menu titel'					, 'text'		, 'hide_overview multilingual', '', 'De menu titel wordt binnen de site in het menu getoond'),
	'menu_subtitle'			=> array('Pagina sub-titel'			, 'text'		, 'hide_overview multilingual', '', 'De sub-titel wordt binnen elk pagina segment onder de hoofdtitel getoond'),
	'black'					=> array('Achtergrond zwart'			, 'boolean'		, 'hide_overview', '', 'Bij deze keuze wordt de achtergrond zwart'), 	
	'picture'		 		=> array('Afbeelding bovenaan de pagina', 'image'		, 'remove_orig hide_overview min_width=1600 min_height=800 max_width=1600 max_height=800 aspect_ratio'),

	'pagina_omschrijving'	=> array('Pagina omschrijving'			, 'text'		, 'hide_overview multilingual', '', 'Kernachtige omschrijving van de pagina voor Google zoekresultaten (gebruik hier bijvoorbeeld de eerste zin uit de tekst van de pagina)'),
	'keywords'				=> array('Keywords'						, 'text'		, 'hide_overview multilingual', '', 'De belangrijkste woorden waarop je in Google gevonden wil worden. Let op: alleen losse worden, gescheiden door een komma'),

	'hide'					=> array('Pagina deactiveren'			, 'boolean'		, 'hide_overview', '', 'Pagina op inactief zetten, de inhoud wordt niet getoond op de website'),
	'foodSection'			=> array('Toon in Eten en Drinken'		, 'boolean'		, 'hide_overview','','Link van deze pagina onderaan in de footer'),
	'menuactive'			=> array('Menukaarten insluiten'		, 'boolean'		, 'hide_overview','','Toon de verschillende menukaarten binnen deze pagina'),
	'arrangementactive'		=> array('Arrangementen insluiten'		, 'boolean'		, 'hide_overview','','Toon de verschillende arrangementen en buffetten binnen deze pagina'),
	'externepagina'			=> array('Externe pagina'				, 'text'		, 'hide_overview'),	
	'inhoud'				=> array('Inhoud pagina'				, 'textarea'	, 'hide_overview hidden_parent multilingual')
);

$maxLevels 	= 2; 		// HET MAXIMAAL AANTAL LEVELS VAN DE PAGINA-STRUCTUUR
$levels		= TRUE;		// BIJ PAGINA BEHEER MOET DEZE OP TRUE STAAN, ANDERS OP FALSE OF LEEG
$page = new CustomPages($pageName, $fields, 1, $levels, $maxLevels, $user);
$page->showPage(true);            