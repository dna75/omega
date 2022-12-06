<?php
ob_start();

define('SPINNERZ_INDEX', TRUE);

$_COOKIE['CKFINDER_BASE_URL'] = 'http://devtest.nannedijkstra.nl/';
$_COOKIE['CKFINDER_BASE_DIR'] = '/var/www/vhosts/nannedijkstra.nl/devtest.nannedijkstra.nl/';

ini_set('display_errors', 'off');
error_reporting(E_ALL);

include('./include/config.inc.php');
include($_SERVER['DOCUMENT_ROOT'] . '/cockpit/functions/corefunc.inc.php');

$db = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

include($develop . '/include/login.inc.php');
include($develop . '/include/language.class.php');
include($develop . '/include/build_list.func.php');
include('./include/get_tree.func.php');
include($develop . '/include/check_upload.func.php');
include($develop . '/include/swap.func.php');

/* include('./include/adodb5/adodb.inc.php'); */
require_once($develop . '/include/CustomPages/CustomPages.class.php');

$page = (isset($_GET['page'])) ? preg_replace('/[^a-z]+/', '', $_GET['page']) : '';
if ($page != 'login') $user->check_right('cockpit', true);

setlocale(LC_ALL, 'nl_NL');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Spinnerz">

    <title>SPINNERZ COCKPIT - <?= $domein; ?></title>

    <? include($develop . '/include/headerinclude.php') ?>
</head>

<body>

    <section id="container">
        <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
        <!--header start-->
        <header class="header black-bg">
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toon/Verberg menu"></div>
            </div>
            <!--logo start-->
            <a href="index.php" class="logo"><b>SPINNERZ</b></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                    <!-- settings start -->
                    <li><a href="<?php echo "index.php?page=stats"; ?>"><i class="fa fa-bar-chart fa-fw tooltips" data-placement="right" data-original-title="Statistieken"></i></a></li>
                    <li><a href="http://<?= $domein; ?>" target="_blank"><i class="fas fa-browser tooltips" data-placement="right" data-original-title="Bekijk website"></i></a>
                </ul>
            </div>
            <div class="top-menu">
                <ul class="nav pull-right top-menu" style="border-radius:0px !important;">
                    <?
                    $query = $db->query("SELECT * FROM users WHERE id = '" . intval($_SESSION['user_id']) . "' ")  or die(mysqli_error($db));
                    while ($row = mysqli_fetch_object($query)) { ?>
                        <li><a class="logout" href="<?php echo "index.php?page=logout"; ?>"><i class="fas fa-lock-alt"></i> <? echo $row->firstname . " " . $row->lastname; ?> </a></li>
                    <? } ?>
                </ul>
            </div>
        </header>
        <!--header end-->

        <!-- **********************************************************************************************************************************************************
            MAIN SIDEBAR MENU
            *********************************************************************************************************************************************************** -->
        <!--sidebar start-->
        <aside>
            <div id="sidebar" class="nav-collapse ">
                <!-- sidebar menu start-->
                <ul class="sidebar-menu" id="nav-accordion">
                    <li class="mt">
                        <a class="" href="index.php">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sub-menu">
                        <?php include($develop . '/include/inhoud.php'); ?>
                    </li>

                </ul>
                <!-- sidebar menu end-->
            </div>
        </aside>
        <!--sidebar end-->

        <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="col-sm-12 col-md-12 well well-sm" style="background-color:#fff; padding-right:10px;">

                    <?php
                    // Check if Custom page is set
                    if ($page == '') {

                        // Get admin details with custom variables
                        $result = new CustomPages('admindetails');
                        $result->fetch();

                        $count = count($result->getResults());

                        // If custom pages 'defaultPage' exsist
                        if ($count == 1) {
                            foreach ($result->getResults() as $row) {

                                if ($row['homelink']) { ?>
                                    <div class="row visible-xs">
                                        <div class="col-sm-12">
                                            <a href="index.php?page=<?= $row['homelink']; ?>" style="color:#fff;" class="btn btn-success btn-block"><?= strtoupper($row['homelinkdescription']); ?></a>
                                        </div>
                                    </div>
                    <? }

                                // Check if variables exist and not empty
                                if ($row['defaultPage'] && $row['defaultPage'] != '0' && file_exists(dirname($_SERVER["SCRIPT_FILENAME"]) . '/pages/' . $page)) {
                                    include(dirname($_SERVER["SCRIPT_FILENAME"]) . '/pages/' . $row['defaultPage']);
                                }
                                if ($row['defaultPage'] && $row['defaultPage'] != '0' && file_exists(dirname($_SERVER["SCRIPT_FILENAME"]) . '/userpages/' . $page)) {
                                    include(dirname($_SERVER["SCRIPT_FILENAME"]) . '/userpages/' . $row['defaultPage']);
                                }
                                // If row exsists but no content show the default instructions
                                else {
                                    include($develop . '/handleiding/handleiding.php');
                                }
                            }
                        }

                        // If the custompages don't exist show default instructions
                        else {
                            // include(dirname($_SERVER["SCRIPT_FILENAME"]) . '');
                            include($develop . '/handleiding/handleiding.php');
                        }
                    }

                    if ($page != '' && file_exists(dirname($_SERVER["SCRIPT_FILENAME"]) . '/pages/' . $page . '.php')) {
                        include(dirname($_SERVER["SCRIPT_FILENAME"]) . '/pages/' . $page . '.php');
                    } else if ($page != '' && file_exists(dirname($_SERVER["SCRIPT_FILENAME"]) . '/userpages/' . $page . '.php')) {
                        include(dirname($_SERVER["SCRIPT_FILENAME"]) . '/userpages/' . $page . '.php');
                    }
                    ?>

                </div>
            </section>
        </section>

        <!--main content end-->
        <!--footer start-->
        <footer class="site-footer">
            <div class="text-center">
                <? $url =  "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
                ?>
                <?php echo date("Y"); ?> - Spinnerz.nl
                <a href="#" class="go-top scrollup">
                    <i class="fa fa-angle-up"></i>
                </a>
            </div>
        </footer>
        <!--footer end-->
    </section>

    <? include($develop . '/include/footerinclude.php') ?>

    <!-- Extra Custom Code for the website - This file is by defaukt included as index_extra.php.ori -->

    <?
    if (is_file($develop . '/include/index_extra.php')) {
        require_once($develop . '/include/index_extra.php');
    }
    ?>

</body>

</html>