<?php
defined('SPINNERZ_INDEX') or die('Access denied.');

$user->check_right('page', true);

require_once($develop . '/include/CustomPages/CustomPages.class.php');
?>

<style>

</style>


<p class="bewerken well well-small">AUTO / KLASSE BEHEER</p>

<? if (isset($_GET['success']) && intval($_GET['success']) == 1) { ?>
    <div class="alert alert-success" id="fade">
        <strong>Gelukt</strong> De aanpassing is uitgevoerd.
    </div>
<? } ?>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="#cars" class="active" aria-controls=" home" role="tab" data-toggle="tab">Autobeheer</a></li>
    <li role="presentation"><a class="" href="#carproperties" aria-controls="profile" role="tab" data-toggle="tab">Auto eigenschappen</a></li>
    <!-- <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li> -->
    <!-- <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li> -->
</ul>


<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="cars">

        <h2>Autobeheer</h2>
        <p>Gegevens van de auto / klasse</p>
        <!-- Start cars tab -->
        <div role="tabpanel" class="tab-pane active" id="cars">
            <!-- Overview cars -->

            <? if (!isset($_GET['edit']) && !isset($_GET['addCar'])) { ?>

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $query = $db->query("SELECT * FROM `cars`") or die(mysqli_error($db));
                        while ($row = mysqli_fetch_array($query)) {
                            echo '<tr>';
                            echo '<td>' . $row['name'] . '</td>';
                            echo '<td><a style="margin-right:25px;" class="btn btn-primary btn-sm" href="?page=auto&edit=' . $row['id'] . '"><i class="fa fa-pencil"></i> Bewerken</a> <a class="btn btn-danger btn-sm" href="?page=auto&delete=' . $row['id'] . '"><i class="fa fa-trash"> Verwijderen</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>

                <a href="index.php?page=auto&addCar=1" class="btn btn-success btn-block white">Auto toevoegen</a>
            <? } ?>
            <!-- End overview cars -->

            <!-- Add car -->
            <? if (isset($_GET['addCar']) && $_GET['addCar'] == 1) { ?>
                <h4 style="margin-top:30px;">Voer het merk / type / klasse in</h4>
                <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group g-3">
                        <label for="carname" class="col-sm-3 control-label">Auto type / klasse</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="carname" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="carimage" class="control-label col-sm-3">Auto afbeelding</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" name="carimage" placeholder="<?= isset($row) ? $row['image'] : ''; ?>" required>
                        </div>
                    </div>

                    <div class="form-group g-3">
                        <label for="aboutTitle" class="col-sm-3 control-label">Titel over deze auto</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="aboutTitle">
                        </div>
                    </div>

                    <div class="form-group g-3">
                        <label for="aboutText" class="col-sm-3 control-label">Tekst over deze auto</label>
                        <div class="col-sm-9">
                            <textarea name="aboutText" class="form-control" rows="7"></textarea>
                        </div>
                    </div>

                    <div class="form-group g-3">
                        <label for="priceaday" class="col-sm-3 control-label">Prijs per dag</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="priceaday" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="form-group g-3">
                        <label for="priceaweek" class="col-sm-3 control-label">Prijs per week (noteer day prijs)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="priceaweek" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="form-group g-3">
                        <label for="priceamonth" class="col-sm-3 control-label">Prijs per maand (noteer day prijs)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="priceamonth" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="form-group g-3">
                        <label for="priceweekendday" class="col-sm-3 control-label">Weekend prijs (per dag)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="priceweekendday" placeholder="0.00" required>
                        </div>
                    </div>

                    <button type="submit" name="addCar" class="btn btn-primary btn-block">Opslaan</button>
                </form>
            <? } ?>

            <!-- Edit cars -->
            <? if (isset($_GET['edit']) && $_GET['edit'] != '') { ?>
                <form id="cars" action="index.php?page=auto" class="form-horizontal" method="POST" enctype="multipart/form-data">

                    <?php
                    $query = $db->query("SELECT * FROM `cars` WHERE `id` = '" . $_GET['edit'] . "'") or die(mysqli_error($db));
                    $row = mysqli_fetch_array($query);
                    ?>

                    <div class="form-group">
                        <label for="carname" class="control-label col-sm-3">Auto type / Klasse</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="carname" placeholder="Auto naam / Type / Klasse" value="<?= isset($row) ? $row['name'] : ''; ?>">
                        </div>
                    </div>

                    <!-- upload image -->
                    <div class="form-group">
                        <label for="carimage" class="control-label col-sm-3">Auto afbeelding</label>
                        <div class="col-sm-9">
                            <input type="file" accept="image/gif, image/jpeg" class="form-control" name="carimage" placeholder="Auto afbeelding" <?= empty($row['carimage']) ? 'required' : ''; ?>>
                        </div>
                    </div>

                    <? if (isset($row['carimage'])) { ?>
                        <div class="row" style="margin-bottom:22px;">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9"><img class="img-fluid" style="max-width:400px;" src="/upload/cars/<?= $row['carimage']; ?>"></div>
                        </div>
                    <? } ?>

                    <div class="form-group g-3">
                        <label for="aboutTitle" class="col-sm-3 control-label">Titel over deze auto</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="aboutTitle" value="<?= isset($row) ? $row['aboutTitle'] : ''; ?>">
                        </div>
                    </div>

                    <div class=" form-group g-3">
                        <label for="aboutTitle" class="col-sm-3 control-label">Tekst over deze auto</label>
                        <div class="col-sm-9">
                            <textarea name="aboutText" class="form-control" rows="7"><?= isset($row) ? $row['aboutText'] : ''; ?></textarea>
                        </div>
                    </div>

                    <div class=" form-group g-3">
                        <label for="priceaday" class="col-sm-3 control-label">Prijs per dag</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="priceaday" placeholder="0.00" required value="<?= isset($row) ? $row['priceaday'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group g-3">
                        <label for="priceaweek" class="col-sm-3 control-label">Prijs per week (noteer dag prijs)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="priceaweek" placeholder="0.00" required value="<?= isset($row) ? $row['priceaweek'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group g-3">
                        <label for="priceamonth" class="col-sm-3 control-label">Prijs per maand (noteer dag prijs)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="priceamonth" placeholder="0.00" required value="<?= isset($row) ? $row['priceamonth'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group g-3">
                        <label for="priceweekendday" class="col-sm-3 control-label">Weekend prijs (per dag)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="priceweekendday" placeholder="0.00" required value="<?= isset($row) ? $row['priceweekendday'] : ''; ?>">
                        </div>
                    </div>


                    <?
                    // get car properties for existing items and empty item
                    $query = $db->query("SELECT car_properties.id AS cpID, car_properties.property AS cpProp, car_details.car_id, car_details.car_property AS cdCP  FROM `car_properties` LEFT JOIN `car_details` ON `car_properties`.`id` = `car_details`.`property_id`  WHERE `car_id` = '" . $_GET['edit'] . "'") or die(mysqli_error($db));

                    while ($row = mysqli_fetch_object($query)) {

                        echo '<div class="form-group">
                            <label for="carproperties" class="control-label col-sm-3">' . $row->cpProp . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="carproperties[]" value = "' . $row->cdCP . '">
                                <input type="hidden" name="carpropertiesid[]" value="' . $row->cpID . '">
                                <input type="hidden" name="car_id" value="' . intval($_GET['edit']) . '">
                            </div>
                        </div>';
                    }

                    if (mysqli_num_rows($query) == 0) {
                        $query = $db->query("SELECT * FROM car_properties") or die(mysqli_error($db));
                        while ($row = mysqli_fetch_object($query)) {

                            echo '<div class="form-group">
                            <label for="carproperties" class="control-label col-sm-3">' . $row->property . '</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="carproperties[]" value = "' . $row->car_property . '">
                                <input type="hidden" name="carpropertiesid[]" value="' . $row->id . '">
                                <input type="hidden" name="car_id" value="' . intval($_GET['edit']) . '">
                            </div>
                        </div>';
                        }
                    }


                    ?>
                    <button type="submit" name="carform" class="btn btn-primary">Opslaan</button>
                </form>
            <? } ?>
        </div>
    </div>
    <!-- End cars tab -->


    <!-- Start carproperties tab -->
    <? if (!isset($_GET['edit'])) { ?>
        <div role="tabpanel" class="tab-pane" id="carproperties">
            <h2>Eigenshcappen beheer</h2>
            <p>Eigenschappen van de auto / klasse</p>
            <form id="properties" action="/cockpit/index.php?page=auto" class="form-horizontal" method="POST">
                <div id="contain">
                    <?
                    // mysqlquery join left car_properties and car_details
                    $query = $db->query("SELECT * FROM `car_properties` ORDER BY `id` ASC");
                    while ($row = mysqli_fetch_array($query)) { ?>
                        <div class="form-group" id="mainDiv">
                            <label for="cartype" class="control-label col-sm-3">Eigenschap voertuig</label>
                            <div class="col-sm-8" id="subDiv">
                                <input type="text" class="form-control" name="property[]" id="property" placeholder="Eigenschap voertuig" value="<?= isset($row) ? db_escape($row['property']) : ''; ?>" required>
                            </div>
                            <div class=" col-sm-1">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeInput(this)">X</button>
                            </div>
                        </div>
                    <? } ?>
                </div>
                <button type="submit" name="propertiesform" class="btn btn-primary">Opslaan</button>
                <button class="btn btn-success" onclick=" addInput()">Eigenschap toevoegen</button>
            </form>
        </div>
    <? } ?>
    <!-- End carproperties tab -->


    <!-- SQL ACTIONS -->

    <!-- Add car DB -->
    <? if (isset($_POST['addCar'])) {

        // string relplace multiple vars
        $carname         = db_escape($_POST['carname']);
        $carimage        = date('dmYHis') . str_replace(" ", "", basename($_FILES["carimage"]["name"]));
        $aboutTitle      = db_escape($_POST['aboutTitle']);
        $aboutText       = db_escape($_POST['aboutTitle']);
        $priceaday       = number_format((float)db_escape($_POST['priceaday']), 2, '.', '');
        $priceaweek      = number_format((float)db_escape($_POST['priceaweek']), 2, '.', '');
        $priceamonth     = number_format((float)db_escape($_POST['priceamonth']), 2, '.', '');
        $priceweekendday = number_format((float)db_escape($_POST['priceweekendday']), 2, '.', '');
        $query           = $db->query("INSERT INTO `cars` (`name`, `carimage`, `aboutTitle`, `aboutText``, `priceaday`, `priceaweek`, `priceamonth`, `priceweekendday` ) 
                            VALUES (
                                '" . $carname . "', 
                                '" . $carimage . "', 
                                '" . $aboutTitle . "',
                                '" . $aboutText . "',
                                '" . $priceaday . "', 
                                '" . $priceaweek . "', 
                                '" . $priceamonth . "', 
                                '" . $priceweekendday . "')")
            or die(mysqli_error($db));
    }
    ?>

    <!-- Delete car -->
    <? if (isset($_GET['delete']) && $_GET['delete'] != '') {
        $queryDeleteDetails = $db->query("DELETE FROM `car_details` WHERE `car_id` = '" . $_GET['delete'] . "'") or die(mysqli_error($db));
        $queryCar = $db->query("DELETE FROM `cars` WHERE `id` = '" . $_GET['delete'] . "'") or die(mysqli_error($db));
    }
    ?>

    <!-- Edit Car details for specific car id including prices and car name-->
    <? if (isset($_POST['carform'])) {

        // check if upload file selected
        if ($_FILES["carimage"]["name"] != '') {
            $carimg = date('dmYHis') . str_replace(" ", "", basename($_FILES["carimage"]["name"]));
            $db->query("UPDATE `cars` SET 
                `name`            = '" . db_escape($_POST['carname']) . "',
                `carimage`        = '" . $carimg . "',
                `aboutTitle`      = '" . db_escape($_POST['aboutTitle']) . "',
                `aboutText`       = '" . db_escape($_POST['aboutText']) . "',
                `priceaday`       = '" . number_format((float)db_escape($_POST['priceaday']), 2, '.', '') . "',
                `priceaweek`      = '" . number_format((float)db_escape($_POST['priceaweek']), 2, '.', '') . "',
                `priceamonth`     = '" . number_format((float)db_escape($_POST['priceamonth']), 2, '.', '') . "',
                `priceweekendday` = '" . number_format((float)db_escape($_POST['priceweekendday']), 2, '.', '') . "'
        WHERE `id`              = '" . intval($_POST['car_id']) . "'") or die(mysqli_error($db));
        } else {
            $db->query("UPDATE `cars` SET 
                  `name`            = '" . db_escape($_POST['carname']) . "',
                  `aboutTitle`      = '" . db_escape($_POST['aboutTitle']) . "',
                  `aboutText`       = '" . db_escape($_POST['aboutText']) . "',
                  `priceaday`       = '" . number_format((float)db_escape($_POST['priceaday']), 2, '.', '') . "',
                  `priceaweek`      = '" . number_format((float)db_escape($_POST['priceaweek']), 2, '.', '') . "',
                  `priceamonth`     = '" . number_format((float)db_escape($_POST['priceamonth']), 2, '.', '') . "',
                  `priceweekendday` = '" . number_format((float)db_escape($_POST['priceweekendday']), 2, '.', '') . "'
            WHERE `id`              = '" . intval($_POST['car_id']) . "'") or die(mysqli_error($db));
        }

        $queryDelete = $db->query("DELETE FROM `car_details` WHERE `car_id` = '" . intval($_POST['car_id']) . "'") or die(mysqli_error($db));

        $cartype = db_escape($_POST['cartype']);
        $carproperties = db_escape($_POST['carproperties']);

        $car_id = db_escape($_POST['car_id']);
        $carpropertiesid = $_POST['carpropertiesid'];
        $carproperties = $_POST['carproperties'];

        $merged = array_combine($carpropertiesid, $carproperties);
        $id = intval($_GET['edit']);

        // print_r($merged);

        foreach ($merged as $key => $value) {
            $query = $db->query("INSERT INTO `car_details` (`car_id`,`car_property`, `property_id`) VALUES ('" . $car_id . "','" . $value . "', '" . $key  . "')") or die(mysqli_error($db));
        }
    } ?>

    <?
    if (isset($_FILES["carimage"]["name"]) && $_FILES["carimage"]["name"] != '') {

        $targetWidth = 0;
        $targetHeight = 415;

        $path = "../upload/cars/";
        upload("carimage", $path, $targetWidth, $targetHeight);
    } else {
    }
    ?>

    <!-- Add / Update / Remove Car properties -->
    <?
    if (isset($_POST['propertiesform'])) {

        // Check if form is submitted with id
        $query = $db->query("SELECT * FROM `car_properties` ORDER BY `id` ASC");
        while ($row = mysqli_fetch_array($query)) {
            $dbProperties[] = $row['property'];
        }

        $properties = $_POST['property'];

        $removedProperties = array_diff($dbProperties, $properties);
        foreach ($removedProperties as $removedProperty) {
            $db->query("DELETE FROM `car_properties` WHERE `property` = '$removedProperty'");
        }

        // Add new properties
        $notInArray = array_diff($properties, $dbProperties);
        foreach ($notInArray as $property) {
            $db->query("INSERT INTO `car_properties` (`property`) VALUES ('$property')");
        }

        // Add new properties to existing cars 
        foreach ($notInArray as $property) {
            $query = $db->query("SELECT * FROM `car_properties` WHERE `property` = '$property'");
            $row = mysqli_fetch_array($query);

            $queryDetails = $db->query("SELECT DISTINCT `car_id` FROM `car_details`");
            while ($rowDetails = mysqli_fetch_array($queryDetails)) {
                $carId = intval($rowDetails['car_id']);
                $db->query("INSERT INTO `car_details` (`car_id`, `property_id`) VALUES ('$carId', '$row[id]')");
            }
        }

        // Cleanup car_details table on remove of property
        $query = $db->query("SELECT `id` FROM `car_properties`");
        while ($row = mysqli_fetch_array($query)) {
            $dbPropertiesId[] = $row['id'];
        }

        $query = $db->query("select DISTINCT `property_id` FROM `car_details`");
        while ($row = mysqli_fetch_array($query)) {
            $dbPropertiesIdDetails[] = $row['property_id'];
        }

        // Find the differnce between the two arrays
        $diff = array_diff($dbPropertiesIdDetails, $dbPropertiesId);
        foreach ($diff as $propertyId) {
            $db->query("DELETE FROM `car_details` WHERE `property_id` = '$propertyId'"); // Remove from car_details
        }
        // End cleanup car_details table on remove of property
    }
    // prevent resubmitting
    if (isset($_POST['propertiesform']) || isset($_POST['carform']) || isset($_POST['addCar']) || isset($_GET['delete'])) {
        header("Location: /cockpit/index.php?page=auto&success=1");
    }
    ?>
    <!-- End Add / Update / Remove  -->

</div>

<!-- dynamicly add input fields -->
<script>
    function addInput() {

        let mainDiv = document.createElement("div");
        mainDiv.className = "form-group";
        mainDiv.setAttribute("id", "mainDiv");

        let subDiv = document.createElement("div");
        subDiv.className = "col-sm-8";
        subDiv.setAttribute("id", "subDiv");

        let newLabel = document.createElement('label');
        newLabel.innerHTML = "Eigenschap voertuig";
        newLabel.setAttribute('class', 'control-label col-sm-3');
        newLabel.setAttribute('id', 'control-label col-sm-3');

        let newInput = document.createElement('input');
        newInput.setAttribute('type', 'text');
        newInput.setAttribute('name', 'property[]');
        newInput.setAttribute('id', 'property');
        newInput.setAttribute('class', 'form-control');
        newInput.setAttribute('required', '');
        newInput.setAttribute('placeholder', 'Eigenschap voertuig');

        let hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');

        // auto create a id for every new field
        let id = Math.floor(Math.random() * 1000000000);
        hiddenInput.setAttribute('value', id);
        hiddenInput.setAttribute('name', 'id[]');
        hiddenInput.setAttribute('id', 'valueId');

        const divCol3 = document.createElement("div");
        divCol3.setAttribute("class", "col-sm-1");

        const remove = document.createElement("button");
        remove.setAttribute("class", "btn btn-danger btn-sm");
        remove.setAttribute("type", "button");
        remove.setAttribute("onclick", "removeInput(this)");
        remove.setAttribute("data-bs-toggle", "tooltip");
        remove.setAttribute("data-bs-placement", "top");
        remove.setAttribute("title", "Verwijder eigenschap");
        remove.innerHTML = "X";

        contain.append(mainDiv);
        mainDiv.append(newLabel);
        mainDiv.append(subDiv);
        subDiv.append(newInput);
        subDiv.append(hiddenInput);
        mainDiv.append(divCol3);
        divCol3.append(remove);
    }

    removeInput = function(e) {
        e.parentNode.parentNode.remove();
    }

    // fade and remove div with 3 second delay
    $(document).ready(function() {
        setTimeout(function() {
            $("#fade").fadeOut(300);
        }, 2000);
    });
</script>