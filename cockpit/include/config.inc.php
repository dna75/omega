<?php

ini_set( 'session.cookie_httponly', 1 );
session_start();

$host =  $_SERVER['SERVER_NAME']; //Outputs www.example.com

// zoek de nederlandse datum etc.
setlocale(LC_TIME, "Dutch");

$path = $_SERVER['DOCUMENT_ROOT'];

$whitelist = array('127.0.0.1','::1');

if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    $path .= "/cockpit/include/spinnerz.inc.php";

} else {
    $path .= "/cockpit/include/spinnerz-local.inc.php";
}

include_once($path);

//include ('./cockpit/include/spinnerz.inc.php');

$domein = $_SERVER['SERVER_NAME'];

//$ckurl = "spinnerz.nl/$domein";

// if the Server version
// $develop = $_SERVER['DOCUMENT_ROOT']."/cockpit";
// $urldevelop = $_SERVER['HTTP_HOST']."/cockpit/";
// $devsite = ""; // used in '/cockpit-root=/include/login.inc.php'

// if the cockpit-root files are NOT in the same directory
// $develop = $_SERVER['DOCUMENT_ROOT']."/cockpit-root";
// $urldevelop = "http://localhost:8888/cockpit-root/";
// $devsite = "spinnerz-CMS"; // used in '/cockpit-root=/include/login.inc.php'

// if the cockpit-root files are in the same dir as the site
$develop = $_SERVER['DOCUMENT_ROOT']."/cockpit";
$urldevelop = $_SERVER['HTTP_HOST']."/cockpit/";

$ipadres = $_SERVER['REMOTE_ADDR'];
$link = "http://" . $_SERVER['SERVER_NAME']."";

$id = (isset($_GET['id'])) ? intval($_GET['id']) : '';
$script = (isset($_GET['script'])) ? $_GET['script'] : '';
$menu = (isset($_GET['menu'])) ? $_GET['menu'] : '';
