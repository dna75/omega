<?php
defined('SPINNERZ_INDEX') or die('Access denied.');

$user->check_right('page', true);

require_once($develop . '/include/CustomPages/CustomPages.class.php');
/**
 *    Description of the parameters of the fields Array options:
 *
 *    Syntax:        $fields = array(
 *                    fieldname => array('title', 'fieldtype', 'options'),
 *                    fieldname => array('title', 'fieldtype', 'options')
 *                };
 *
 *    fieldname        = slug for the field (short name without spaces or weird characters)
 *    title            = Name of the label of the field (used only in the backend)
 *    fieldtype        = Fieldtypes currently available:
 *                        - 'text'
 *                        - 'textarea'
 *                        - 'number'
 *                        - 'boolean'
 *                        - 'date'
 *                        - 'image'
 *                        - 'select'
 *                        - 'icon'
 *                        - 'number'
 *                        - 'price'

 *    options            = You may use more than one option with a seperating space. The example 100 value below can be
 *                        altered, but there should never be a space inside the key=value construction.
 *
 *                        The following options are currently available:
 *                        - multilingual         = Whether or not this field is multilingual (images cannot be multilingual)
 *                        - required            = When selected the field is required
 *                        - hide_overview        = Whether or not this field is shown in the overview in the backend
 *                        - min_width=100        = Minimum image-width
 *                        - min_height=100    = Minimum image-height
 *                        - max_width=100        = Maximum image-width
 *                        - max_height=100    = Maximum image-height
 *                        - aspect_ratio        = When the image aspect-ratio is set the ratio is max_width:max_height
 *                        - max_thumb_width=100    = Maximum thumb-width
 *                        - max_thumb_height=100    = Maximum thumb-height
 *                        - thumb_crop_on        = Whether or not the thumbnail should be cropped
 *                        - hide_editor        = Hide the editor in an textarea
 *                        - overview_width=    = The width in the overview
 *                        - hidden_parent        = Hide field if parent contains children
 *                        - Class             = XS ruimte over 5 // MD ruite over 8
 *
 *    NOTES:     - THE FIELDS ARE SHOWN IN THE ORDER OF THE FIELDS ARRAY, BUT THE MULTILINGUAL FIELDS
 *                ARE ALWAYS SHOWN LAST!!!
 *            - IMAGES CAN NEVER BE MULTILINGUAL
 *                        -
 **/

$pageName     = 'openingstijden';

$fields     = array(
    //    'name'             => array('Evenement naam'            , 'text'        , 'overview_width=130 or hide_overview',         'class class',             'Tooltip'),
    'mondaytofridayfrom'             => array('Maandag t/m vrijdag vanaf', 'time', ''),
    'mondaytofridayto'             => array('Maandag t/m vrijdag tot', 'time', ''),
    'saturdayfrom'                      => array('Zaterdag vanaf', 'time', ''),
    'saturdayto'             => array('Zaterdag tot', 'time', ''),
    'saturdayclosed'        => array('Zaterdag gesloten', 'boolean', 'hide_overview'),
    'sundayfrom'            => array('Zondag vanaf', 'time', ''),
    'sundayto'             => array('Zondag tot', 'time', ''),
    'sundayclosed'        => array('Zondag gesloten', 'boolean', 'hide_overview'),

);



$config  = array(
    'auto_active'     => 0,
    'levels'         => false,
    'maxLevels'     => 1,
    'user'             => null,
    'facebook'        => false,
    'add_items'        => false,
    'delete_items'    => false,
    'edit_items'    => true,
    'order_select'    => false,
    // Custom Button
    /*
    'buttons'        => array(
    //                        Kleurcode        Knopje (+ eventueel tekst)                javascript functie naam
    'Email'     => array('btn-success'    , '<i class="fa fa-fw fa-pencil"></i>'        , 'send_mail'),
    'Email2'    => array('btn-danger'    , '<i class="fa fa-fw fa-pencil"></i>'        , 'gotopage'),
),
*/
);

$page = new CustomPages($pageName, $fields, $config);
$page->copy_items     = false;
//$page->showPage(true);
//$page->showPage(true, 'name', true);           // Dit zal orderen op name (want orderfield = 'name') en deze omdraaien (want reverse  = true)
$page->showPage(true, 'date_to', true);          // Dit zal orderen op date_to (want orderfield = 'date_to') en deze niet omdraaien (want reverse  = false)
//$page->showPage(true, false, true);              // Dit zal de normale order gebruiken (want orderfield = false) en deze reversen (want reverse  = true)
//$page->showPage(true);                                 // Deze werkt gewoon op de oude manier. orderfield en reverse zijn niet verplicht ingevuld te worden en staan standaard op false.
//$page->showPage(true, 'date_to');                // Dit werk op dezelfde manier als voorbeeld 2. Reverse is dus niet verplicht in te vullen en staat standaard op false.
?>

<!- Custom buttons code -->
    <!--
<script type="text/javascript">

function send_mail(id) {
alert(id);
}

function gotopage(id) {
location.href = '/cockpit/index.php?page=agenda&mode=edit&id='+id;
}

</script>
-->