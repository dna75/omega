<div role="tabpanel">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#reserveringen" aria-controls="reserveringen" role="tab" data-toggle="tab">Reserveringen</a></li>
        <!-- 	    <li role="presentation" class=""><a href="#beschikbaarheid" aria-controls="beschikbaarheid" role="tab" data-toggle="tab">Beschikbaarheid</a></li> -->

        <!-- 	    <li role="" class=""><a href="http://www.binnendrachten.nl/cockpit/index.php?page=reserverenkerst" aria-controls="beschikbaarheid" role="" data-toggle="">Reserveringen Kerst</a></li> -->
    </ul>

    <!-- WEERGAVE TAB MET DAARIN DE DATEPICKER MET DE BESCHIKBAARHEID -->
    <!--
	<div class="tab-content">
    	<div role="tabpanel" class="tab-pane" id="beschikbaarheid">
	    	<div class="row">
		    	<div class="col-xs-12">
			    	<p style="margin: 10px 0;">Door op een datum in de onderstaande kalender te klikken kan er op die datum niet gereserveerd worden.</p>
		    	</div>
	    	</div>
	    	<div id="datepicker5"></div>
		</div>
-->
    <div role="tabpanel" class="tab-pane active" id="reserveringen">

        <?php

        if (isset($_POST['save'])) {

            $vraag = preg_replace("/(\r|\n)/", " ", $_POST['vraag']);

            if ($_GET['action'] == 'add') {

                $newdatum = date("Y-m-d", strtotime($_POST['datum']));
                $newaantal =  preg_replace("/[^0-9,.]/", "", $_POST['aantal']);
                $telefoon = preg_replace("/[^0-9]/", "", '' . $_POST['telefoon'] . '');

                $db->query('INSERT INTO reserveren (naam, telefoon, email, datum, tijd, aantal, vraag, aanhef)
	VALUES (\'' . db_escape($_POST['naam']) . '\',
	\'' . db_escape($telefoon) . '\',
	\'' . db_escape($_POST['email']) . '\',
	\'' . db_escape($newdatum) . '\',
	\'' . db_escape($_POST['tijd']) . '\',
	\'' . db_escape($newaantal) . '\',
	\'' . db_escape($vraag) . '\',
	\'' . db_escape($_POST['aanhef']) . '\');')
                    or die(mysqli_error($db));
            } else {
                $telefoon = preg_replace("/[^0-9]/", "", '' . $_POST['telefoon'] . '');
                $db->query('UPDATE reserveren SET naam = \'' . db_escape($_POST['naam']) . '\'
		, telefoon = \'' . db_escape($telefoon) . '\'
		, email = \'' . db_escape($_POST['email']) . '\'
		, datum = \'' . db_escape($_POST['datum']) . '\'
		, tijd = \'' . db_escape($_POST['tijd']) . '\'
		, aantal = \'' . db_escape($_POST['aantal']) . '\'
		, vraag = \'' . db_escape($vraag) . '\'
		, aanhef = \'' . db_escape($_POST['aanhef']) . '\'

		WHERE id = ' . intval($_GET['id']) . ';') or die(mysqli_error($db));
            }

            header('Location: index.php?page=reserveren');
            die();
        }

        if (isset($_GET['send'])) {


            /*
		 $result = $db->query('SELECT * FROM reserveren WHERE id = ' . intval($_GET['id']) . '') or die(mysqli_error($db));
		 $row = mysqli_fetch_object($result);


 		 echo'vraag='.$row -> vraag.'<br>';

		 echo'id='.$row -> id.'<br>';
		 echo'aantal='.$row -> aantal.'<br>';

		}
*/


            $db->query('UPDATE reserveren SET vraag = \'' . db_escape($_POST['vraag']) . '\'
		WHERE id = ' . intval($_GET['id']) . ';') or die(mysqli_error($db));


            $result = $db->query('SELECT * FROM reserveren WHERE id = ' . intval($_GET['id']) . '') or die(mysqli_error($db));
            $row = mysqli_fetch_object($result);

            $newdatum = date("d-m-Y", strtotime($row->datum));
            $tijd = date('H:i', strtotime($row->tijd));
            $aanhef = $row->aanhef;
            $vraag = nl2br($row->vraag);

            $aanhef = ($aanhef == 'Dhr') ? "heer" : "mevrouw";

            require_once '../swift/swift_required.php';

            $transport = Swift_SmtpTransport::newInstance('localhost', 25)
                ->setUsername('info@binnendrachten.nl')
                ->setPassword('merdbae');
            $mailer = Swift_Mailer::newInstance($transport);

            $message = Swift_Message::newInstance('Reservering Omega Autoverhuur');
            $message->setFrom(array('info@binnendrachten.nl' => 'Omega Autoverhuur'));
            $message->setTo(array($row->email => 'Bevestiging van reservering Omega Autoverhuur'));
            $message->setBody('<html><body>

  	Beste ' . $row->naam . ',<br><br>
  	Hierbij bevestigen wij uw reservering.<br><br>
  	De gegevens van uw reservering:<br>
  	Datum: ' . $newdatum . ' <br>
  	Aantal personen: ' . $row->aantal . ' <br>
	Aankomsttijd: ' . $row->tijd . '<br>
	Opmerking / vraag: ' . $vraag . '<br><br>

	Mocht u vragen hebben naar aanleiding van uw reservering of wilt u een wijziging doorgeven? Mail naar info@binnendrachten.nl of bel .<br><br>

	Met gastvrije groet,<br><br>

	Omega Autoverhuur<br><br>
	br><br>

	<img src="' . $base . '/images/logo.png" alt="Omega Autoverhuur" width="" height="" />

  </body></html>', 'text/html');

            $result = $mailer->send($message);
            $_SESSION['cform_sent'] = TRUE;

            echo '<span class="btn btn-medium btn-info">Er is een bevestiging gestuurd naar ' . $row->aanhef . ' ' . $row->naam . '</span>';


            $db->query('UPDATE reserveren SET bevestigd = \'1\' WHERE id = ' . intval($_GET['id']) . ';') or die(mysqli_error($db));
        }



        if (isset($_GET['del'])) {
            $db->query('DELETE FROM reserveren WHERE id = ' . intval($_GET['del']) . '') or die(mysqli_error($db));
        }


        /* Overzicht Reserveringen */
        if (!isset($_GET['action']) && !isset($_GET['date'])) : ?>

            <?php
            $verwijder = date('Y-m-d', strtotime('-0 hours'));
            $result = $db->query('SELECT * FROM reserveren WHERE datum >= "' . $verwijder . '" ORDER BY datum ASC') or die(mysqli_error($db)); ?>
            <p class="bewerken well well-sm">Reserveringen</p>
            <p>Onderstaand een overzicht van de reserveringen.</p>
            <div class="row " style="padding:15px;">
                <div class="col-xs-6 text-center" style="background-color: #ffe149; padding-top:10px;">
                    <p>Op deze dagen zijn tijden geblokkeerd</p>
                </div>
                <div class="col-xs-6 text-center" style="background-color: #f73f3f; padding-top:10px; color:#fff;">
                    <p>Deze dagen zijn volledig geblokkeerd</p>
                </div>
            </div>
            <!-- 	<p><a class="btn btn-info" style="color:#fff;" href="index.php?page=reserverenkerst">Reserveringen kerstdagen</a></p> -->

            <div class="hidden-xs" id="datepicker2"></div>
            <div class="visible-xs" id="datepickermobile"></div>

            <br>
            <?php if (mysqli_num_rows($result) > 0) : ?>

                <div class="table-responsive">
                    <table class="table-striped table table-condensed">
                        <thead class="reserverenheader">
                            <tr>
                                <th>Achternaam</th>
                                <th>Telefoon</th>
                                <!-- 					<th>Email</th> -->
                                <th>Datum</th>
                                <th>Tijd</th>
                                <th>Aantal</th>
                                <th>Vraag</th>
                                <th>Bevestigd</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_object($result)) :
                                $newdatum = date("d-m-Y", strtotime($row->datum));
                                $datum = $row->datum;
                                $id = $row->id;

                            ?>
                                <tr>
                                    <td><?php echo $row->naam; ?></td>
                                    <td><?php echo $row->telefoon; ?></a></td>
                                    <!-- 				<td><?php echo strip_tags($row->email); ?></td> -->
                                    <td><?php echo $newdatum; ?></td>
                                    <td><?php echo $row->tijd; ?></td>
                                    <td><?php echo $row->aantal; ?></td>
                                    <td>
                                        <? if ($row->vraag != '') { ?>
                                            <button type="button" class="btn btn-xs btn-primary datum" data-toggle="tooltip" data-placement="left" title="Vraag:<br><? echo $row->vraag; ?>"><i class="fa fa-comment-o"></i></button>
                                    </td>

                                <? } ?>
                                </td>
                                <td><?php if ($row->bevestigd > 0) {
                                        echo '<span class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="" style="color:green;"><i class="fa fa-check"</i></span>';
                                    } else {
                                        echo '<span class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Tooltip on top" style="color:red;"><i class="fa fa-ban"</i></span>';
                                    } ?></td>

                                <td>
                                    <a class="btn btn-xs btn-default" href="index.php?page=reserveren&action=edit&id=<?php echo $row->id; ?>" title="Klik hier om deze reservering te bewerken"><i class="fa fa-pencil icon-border"></i></a>

                                    <!-- knop bevestiging voor reserveringen ZONDER een Vraag -->
                                    <? if ($row->vraag == '') : ?>
                                        <a class="btn btn-xs btn-default" href="index.php?page=reserveren&send&id=<?php echo $row->id; ?>" title="Klik hier om een bevestiging te sturen"><i class="fa fa-envelope icon-border"></i></a>
                                    <? endif; ?>

                                    <!-- knop bevestiging voor reserveringen MET een Vraag -->
                                    <? if ($row->vraag != '') : ?>
                                        <a class="btn btn-xs btn-default" data-toggle="modal" data-target="#id<?php echo (isset($row)) ? $row->id : ''; ?>" title="Klik hier om een bevestiging te sturen reactie"><i class="fa fa-envelope icon-border"></i></a>
                                        <!-- Modal - Popup voor het beantwoorden van de vrag -->
                                        <div class="modal fade" id="id<?php echo (isset($row)) ? $row->id : ''; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel">Wil je een antwoord geven op de gestelde vraag?</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="index.php?page=reserveren&send&id=<?php echo $row->id ?>" method="post">
                                                            <input type="hidden" name="send" value="1" />
                                                            <textarea id="vraag" name="vraag" class="form-control" rows="5"><?php echo (isset($row)) ? $row->vraag : ''; ?></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuleer</button>
                                                        <button type="submit" class="btn btn-custom btn-large">Verstuur</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- 					Einde Modal -->
                                    <? endif; ?>

                                    <a class="btn btn-xs btn-danger" style="color:white;" onclick="return confirm('Weet u zeker dat u dit item wil verwijderen?');" href="index.php?page=reserveren&del=<?php echo $row->id; ?>" title="Klik hier om deze reservering te verwijderen"><i class="fa fa-trash-o"></i></a>

                                    <?
                                    if ($row->telefoon != '') {
                                        $eerder = $db->query('SELECT * FROM reserveren WHERE telefoon = ' . $row->telefoon . ' AND datum !="' . $row->datum . '" ORDER BY datum ASC') or die(mysqli_error($db));
                                    }

                                    if (mysqli_num_rows($eerder) > 0) { ?>
                                        <button type="button" class="btn btn-xs btn-success datum" data-toggle="tooltip" data-placement="left" title="Eerdere reservering<br>

					<?

                                        while ($rowdatum = mysqli_fetch_object($eerder)) {
                                            $newdatum = date("d-m-Y", strtotime($rowdatum->datum));

                                            echo $newdatum;
                                            echo '<br>';
                    ?>
					<?
                                        }

                    ?>
					"><i class="fa fa-bell"></i></button>
                                    <?
                                    }
                                    ?>

                                </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div> <!-- einde responsive table -->
            <?php else : ?>

                <p>Er werden geen reserveringen gevonden.</p>

            <?php endif; ?>

            <br>

            <a class="btn btn-medium btn-success" style="color:#fff;" onclick="window.location='index.php?page=reserveren&action=add';"><i class="fa fa-plus-square-o"></i> Voeg reservering toe</i></a>
            <a class="btn btn-primary btn-md" onclick="PrintElem('#printableArea')" target="_blank"><i class="fa fa-print"></i> Afdrukken reserveringen vandaag</a>
            <!-- <a href="userpages/excelexportres.php?date=<?= $date; ?>" style="color: #fff;" class="btn btn-primary btn-md" target="_blank"><i class="fa fa-file-excel-o"></i> Excel export reserveringen  -->
            <!-- <? // echo $date; 
                    ?></a> -->




        <?php elseif (isset($_GET['action']) && $_GET['action'] == 'add' or isset($_GET['action']) && $_GET['action'] == 'edit') : ?>

            <p class="bewerken well well-sm">Reserveringen</p>

            <?php if ($_GET['action'] == 'add') : ?>
                <p>Vul onderstaand formulier in om een nieuwe reservering aan te maken.</p>
            <?php endif; ?>

            <?php if ($_GET['action'] == 'edit') : ?>
                <?php $result = $db->query('SELECT * FROM reserveren WHERE id = ' . intval($_GET['id']) . '') or die(mysqli_error($db)); ?>
                <?php $row = mysqli_fetch_object($result); ?>
                <p>Bewerk onderstaande gegevens naar wens en klik op "Opslaan" om te wijzigingen op te slaan.</p>
            <?php endif; ?>


            <form action="" method="post">
                <input type="hidden" name="save" value="1" />
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Aanhef</td>
                            <td>
                                <select class="form-control" id="aanhef" name="aanhef">
                                    <option value="<?php echo (isset($row)) ? $row->aanhef : ''; ?>"><?php echo (isset($row)) ? $row->aanhef : ''; ?></option>
                                    <option value="Dhr">Dhr</option>
                                    <option value="Mevr">Mevr</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Naam</td>
                            <td><input type="text" class="form-control" name="naam" value="<?php echo (isset($row)) ? $row->naam : ''; ?>" /></td>
                        </tr>
                        <tr>
                            <td>Telefoon</td>
                            <td><input type="text" class="form-control" name="telefoon" value="<?php echo (isset($row)) ? $row->telefoon : ''; ?>" /></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><input type="text" class="form-control" name="email" value="<?php echo (isset($row)) ? $row->email : ''; ?>" /></td>
                        </tr>
                        <tr>
                            <td>Datum</td>
                            <td><input type="text" class="form-control datepicker" name="datum" value="<?php echo (isset($row)) ? $row->datum : ''; ?>" /></td>
                        </tr>
                        <tr>
                            <td>Tijd</td>
                            <td><input type="text" class="form-control" name="tijd" value="<?php echo (isset($row)) ? $row->tijd : ''; ?>" /></td>
                        </tr>
                        <tr>
                            <td>Aantal personen</td>
                            <td><input type="text" class="form-control" name="aantal" value="<?php echo (isset($row)) ? $row->aantal : ''; ?>" /></td>
                        </tr>
                        <tr>
                            <td>Vraag / Opmerking</td>
                            <td><textarea id="vraag" name="vraag" class="form-control" rows="5"><?php echo (isset($row)) ? $row->vraag : ''; ?></textarea></td>

                        </tr>
                        <tr>
                            <td colspan="2"><button type="submit" class="btn btn-medim btn-success"><i class="fa fa-floppy-o"></i> Opslaan</i></button>
                                <a class="btn btn-danger" style="color:#fff;" href="index.php"><i class="fa fa-undo"></i> Annuleer</a>
                            </td>


                        </tr>
                    </tbody>
                </table>
            </form>



        <?php endif; ?>


        <!-- Overzicht Reserveringen van een bepaalde dag -->
        <?
        if (isset($_GET['date']) && !isset($_GET['action'])) {

            $date = $_GET['date'];
            $resdatum = date("Y-m-d", strtotime($date));

            $result2 = $db->query('SELECT * FROM reserveren WHERE datum = "' . $resdatum . '" ORDER BY datum ASC') or die(mysqli_error($db)); ?>
            <p class="bewerken well well-sm">Reserveringen</p>
            <h5 class="well well-sm" style="text-transform: uppercase">overzicht van de reserveringen: <? echo $date; ?></h5>

            <div class="row" id="reservations">
                <div class="col-xs-12 col-md-4">
                    <div id="datepickermobile"></div>
                </div>
                <div class="col-xs-12 col-md-8">
                    <? include('./userpages/reservation_available.php'); ?>
                </div>
            </div>


            <?php if (mysqli_num_rows($result2) > 0) : ?>


                <table class="table-striped table">
                    <thead>
                        <tr>
                            <td>Achternaam</td>
                            <td>Telefoon</td>
                            <td>Datum</td>
                            <td>Tijd</td>
                            <td>Aantal</td>
                            <td>Vraag</td>
                            <td>Bevestiging</td>
                            <td>&nbsp;</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_object($result2)) :
                            $newdatum = date("d-m-Y", strtotime($row->datum));
                            $tel = $row->telefoon;
                            $klantvraag = $row->vraag;

                        ?>
                            <tr>
                                <td><?php echo $row->naam; ?></td>
                                <td><?php echo $row->telefoon; ?></a></td>
                                <td><?php echo $newdatum; ?></td>
                                <td><?php echo $row->tijd; ?></td>
                                <td><?php echo $row->aantal; ?></td>
                                <td>
                                    <? if ($row->vraag != '') { ?>
                                        <button type="button" class="btn btn-xs btn-primary datum" data-toggle="tooltip" data-placement="left" title="Vraag:<br><? echo $row->vraag; ?>"><i class="fa fa-comment-o"></button>
                                </td>

                            <? } ?>
                            </td>
                            <td><?php if ($row->bevestigd > 0) {
                                    echo '<span class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="" style="color:green;"><i class="fa fa-check"</i></span>';
                                } else {
                                    echo '<span class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Tooltip on top" style="color:red;"><i class="fa fa-ban"</i></span>';
                                } ?></td>

                            <td>
                                <a class="btn btn-xs btn-default" href="index.php?page=reserveren&action=edit&id=<?php echo $row->id; ?>" title="Klik hier om deze reservering te bewerken"><i class="fa fa-pencil icon-border"></i></a>
                                <!-- knop bevestiging voor reserveringen ZONDER een Vraag -->
                                <? if ($row->vraag == '') : ?>
                                    <a class="btn btn-xs btn-default" href="index.php?page=reserveren&send&id=<?php echo $row->id; ?>" title="Klik hier om een bevestiging te sturen"><i class="fa fa-envelope icon-border"></i></a>
                                <? endif; ?>

                                <!-- knop bevestiging voor reserveringen MET een Vraag -->
                                <? if ($row->vraag != '') : ?>
                                    <a class="btn btn-xs btn-default" data-toggle="modal" data-target="#id<?php echo (isset($row)) ? $row->id : ''; ?>" title="Klik hier om een bevestiging te sturen reactie"><i class="fa fa-envelope icon-border"></i></a>
                                    <!-- Modal - Popup voor het beantwoorden van de vrag -->
                                    <div class="modal fade" id="id<?php echo (isset($row)) ? $row->id : ''; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel">Wil je een antwoord geven op de gestelde vraag?</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="index.php?page=reserveren&send&id=<?php echo $row->id ?>" method="post">
                                                        <input type="hidden" name="send" value="1" />
                                                        <textarea id="vraag" name="vraag" class="form-control" rows="5"><?php echo (isset($row)) ? $row->vraag : ''; ?></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuleer</button>
                                                    <button type="submit" class="btn btn-primary btn-large">Verstuur</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 					Einde Modal -->
                                <? endif; ?>

                                <a class="btn btn-xs btn-danger" style="color:white;" onclick="return confirm('Weet u zeker dat u dit item wil verwijderen?');" href="index.php?page=reserveren&del=<?php echo $row->id; ?>" title="Klik hier om deze reservering te verwijderen"><i class="fa fa-trash-o"></i></a>
                                <?
                                $eerder = $db->query('SELECT * FROM reserveren WHERE telefoon = ' . $row->telefoon . ' AND datum !="' . $row->datum . '" ORDER BY datum ASC') or die(mysqli_error($db));

                                if (mysqli_num_rows($eerder) > 0) { ?>
                                    <button type="button" class="btn btn-xs btn-success datum" data-toggle="tooltip" data-placement="left" title="Eerdere reservering<br>

					<?

                                    while ($rowdatum = mysqli_fetch_object($eerder)) {
                                        $newdatum = date("d-m-Y", strtotime($rowdatum->datum));

                                        echo $newdatum;
                                        echo '<br>';
                    ?>
					<?
                                    }

                    ?>
					"><i class="fa fa-bell"></i></button>
                                <?
                                }
                                ?>


                            </td>
                            </tr>

                        <?php endwhile; ?>
                    </tbody>
                </table>

                <a class="btn btn-default" href="index.php"><i class="fa fa-arrow-circle-o-left"></i> Terug naar overzicht</a>
                <a class="btn btn-primary btn-md" onclick="PrintElem('#printableArea')" target="_blank"><i class="fa fa-print"></i> Afdrukken reserveringen van <? echo $date; ?></a>
                <a href="userpages/excelexportres.php?date=<?= $date; ?>" style="color: #fff;" class="btn btn-primary btn-md" target="_blank"><i class="fa fa-file-excel-o"></i> Excel export reserveringen <? echo $date; ?></a>

            <?php else : ?>

                <p style="padding-top:20px;">Er werden geen reserveringen voor <? echo $date; ?> gevonden.</p>
                <a class="btn btn-default" href="index.php"><i class="fa fa-arrow-circle-o-left"></i> Terug naar overzicht</a>


            <?php endif; ?>

        <?
        }
        ?>



        <!-- ********* Print Rerserveringen ********** -->
        <script type="text/javascript">
            function PrintElem(elem) {
                Popup($(elem).html());
            }

            function Popup(data) {
                var mywindow = window.open('', 'printableArea', 'height=400,width=600');
                mywindow.document.write('<html><head><title>Overzicht Reserveringen</title>');
                mywindow.document.write('<link rel="stylesheet" href="/css/bootstrap.css" type="text/css" />');
                mywindow.document.write('</head><body >');
                mywindow.document.write(data);
                mywindow.document.write('</body></html>');

                mywindow.print();
                mywindow.close();

                return true;
            }
        </script>

        <div id="printableArea" class="hidden">

            <?

            $datum = date("Y-m-d");
            $datum2 = date("d-m-Y");
            $vandaag = (!isset($_GET['date'])) ? $datum : $resdatum;

            $result = $db->query('SELECT * FROM reserveren WHERE datum = "' . $vandaag . '" ORDER BY datum ASC') or die(mysqli_error($db)); ?>
            <p class="bewerken well well-sm">Reserveringen</p>
            <p>Onderstaand een overzicht van de reserveringen van <? echo $datum2; ?>:</p>

            <table class="table-striped table">
                <thead>
                    <tr>
                        <td>Achternaam</td>
                        <td>Telefoon</td>
                        <td>Tijd</td>
                        <td>Aantal</td>
                        <td>Vraag</td>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_object($result)) :
                        $newdatum = date("d-m-Y", strtotime($row->datum));
                        $tel = $row->telefoon;


                    ?>
                        <tr>
                            <td><?php echo $row->naam; ?></td>
                            <td><?php echo $row->telefoon; ?></a></td>
                            <td><?php echo $row->tijd; ?></td>
                            <td><?php echo $row->aantal; ?></td>
                            <td><?php echo $row->vraag; ?></td>

                        </tr>
                    <?php endwhile; ?>
            </table>
        </div>