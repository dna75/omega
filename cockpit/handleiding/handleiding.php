<p class="bewerken">Bezoekers vandaag</p>
<div class="row">
	<div class="col-sm-12">
		<? include_once './pages/statsindex.php'; ?>
	</div>
</div>

<p class="bewerken">Handleiding</p>
<p class="handleidingintro">De meest gebruikte functies worden besproken. In de verschillende tabbladen zijn worden de verschillende functies uitgelicht. Klik op een item om de functionaliteit te bekijken.</p>
<ul class="nav nav-tabs">
	<li class="active hidden-xs"><a href="#pagina" data-toggle="tab"><i class="fa fa-file-text"></i> Pagina beheer</a></li>
	<li class="active visible-xs"><a href="#pagina" data-toggle="tab"><i class="fa fa-file-text"></i> Pagina's</a></li>

	<li><a href="#extra" data-toggle="tab" class="hidden-xs"><i class="fa fa-plus"></i> Extra opties</a></li>
	<li><a href="#extra" data-toggle="tab" class="visible-xs"><i class="fa fa-plus"></i> Extra</a></li>

	<li><a href="#editor" data-toggle="tab" class="hidden-xs"><i class="fa fa-pencil"></i> Editor opties</a></li>
	<li><a href="#editor" data-toggle="tab" class="visible-xs"><i class="fa fa-pencil"></i> Editor</a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content">
	<div class="tab-pane active" id="pagina"><br>
		<p class="bewerken handleidingintro"><i class="fa fa-file-text"></i> Pagina beheer</p>
		<p class="handleidingintro">Onder 'pagina beheer' worden de pagina’s van de website weergegeven. Klik op 'Voeg item toe' om een nieuwe pagina toe te voegen. Je kan de paginavolgorde van het menu aanpassen door een pagina te verslepen naar een andere positie. Door op het 'prullenbak icoon' te klikken kan je een pagina definitief verwijderen. Klik op het 'potlood icoon' om de pagina te bewerken.<br><br>
			Wil je tijdelijk een pagina niet tonen op de website? Klik op het 'stoplicht icoon'. Wanneer deze op rood staat wordt de pagina niet getoond binnen de site.</p><br>

		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
							Titel browser
						</a>
					</h4>
				</div>
				<div id="collapseOne" class="panel-collapse collapse">
					<div class="panel-body">
						De titel van de Browser wordt bovenaan de browser weergegeven. Voor het beste resultaat is het verstandig dat de refereerd aan de inhoud van de pagina.
						Hou er rekening mee dat de voor de naam van de website wordt geplaatst. Het is dus niet nodig je bedrijfsnaam in deze titel te vermelden.
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
							Titel menu
						</a>
					</h4>
				</div>
				<div id="collapseTwo" class="panel-collapse collapse">
					<div class="panel-body">
						De 'Titel menu' wordt binnen de website weergegeven in het hoofdmenu. Zorg ervoor dat je geen leestekens in een menutitel gebruikt.
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
							Pagina omschrijving
						</a>
					</h4>
				</div>
				<div id="collapseThree" class="panel-collapse collapse">
					<div class="panel-body">
						Een korte omschrijving van de betreffende pagina. Het is belangrijk dat je hier pakkende korte omschrijving invult. Deze komt terug bij de zoek resultaten binnen google en andere zoekmachines. Het is belangrijk dat de tekst wel betrekking heeft op de inhoud. In de zoekresultaten komt de tekst ook terug in de korte omschrijving bij de resultaten. Deze tekst is erg belangrijk voor de vindbaarheid van de pagina/site.
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse4">
							Keywords (Sleutelwoorden)
						</a>
					</h4>
				</div>
				<div id="collapse4" class="panel-collapse collapse">
					<div class="panel-body">
						Sleutelwoorden zijn woorden waarop je gevonden wilt worden in Google. De belangrijkste zoekwoorden dienen vooraan geplaatst te worden. Maximaal 12 sleutelwoorden per pagina wordt aangeraden. Let er op dat je elk woord scheidt door een ‚komma’ + een spatie. <strong>Bijvoorbeeld: spinnerz, reclame, vormgeving</strong>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse5">
							Pagina status
						</a>
					</h4>
				</div>
				<div id="collapse5" class="panel-collapse collapse">
					<div class="panel-body">
						Wanneer een de status op actief staat dan is de betreffende pagina ook zichtbaar voor de bezoekers van de website. Wanneer je bijvoorbeeld een pagina pas later wil tonen op de site of wanneer u bezig bent met het uitwerken van een pagina dan kunt u deze pagina ‚inactief’ maken.
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse6">
							Hoofdmenu
						</a>
					</h4>
				</div>
				<div id="collapse6" class="panel-collapse collapse">
					<div class="panel-body">
						Wanneer de pagina ‚actief’ is dan wordt deze ook in het hoofdmenu getoond aan de bezoekers van de website.
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse7">
							Tekst aanpassen
						</a>
					</h4>
				</div>
				<div id="collapse7" class="panel-collapse collapse">
					<div class="panel-body">
						Door op 'opslaan te klikken wordt de tekst bewaard'.
					</div>
				</div>
			</div>

		</div>
	</div>
	<div class="tab-pane" id="extra"><br>

		<!-- Extra opties -->

		<p class="bewerken handleidingintro"><i class="fa fa-plus"></i> Extra opties</p>

		<p class="handleidingintro">De extra opties bevatten extra modules die van toepassing zijn op de site.</p><br>

		<div class="panel-group" id="accordion2">

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse-e2">
							(sub)pagina toevoegen
						</a>
					</h4>
				</div>
				<div id="collapse-e2" class="panel-collapse collapse">
					<div class="panel-body">
						Hier kunnen (sub)pagina's toegevoegd worden aan het menu. De positie van de pagina kan bepaald worden door in de pulldown een pagina te selecteren waarna de nieuwe pagina dient te komen.
					</div>
				</div>
			</div>
			<!--
		  <div class="panel panel-default">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse-e3">
		          Concert beheer
		        </a>
		      </h4>
		    </div>
		    <div id="collapse-e3" class="panel-collapse collapse">
		      <div class="panel-body">
		U kunt hier de concert informatie aanpassen. Door het item in het overzicht te verslepen kunt u de volgorde op de site bepalen. Het bovenste item wordt ook als bovenste item in de site getoond.
		      </div>
		    </div>
		  </div>
-->
			<!--
		  <div class="panel panel-default">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse-e4">
		          Talenbeheer
		        </a>
		      </h4>
		    </div>
		    <div id="collapse-e4" class="panel-collapse collapse">
		      <div class="panel-body">
		Binnen het talenbeheer kunnen aanvullende talen worden toegevoegd aan de site.
		Doormiddel van het 'stoplicht' kunnen talen geactiveerd en gedeactiveerd worden.<br>
		Met het prullenbak symbool kan de taal in zijn geheel worden verwijdert. Let er hier bij op dat de teksten van de betreffende taal dan ook verwijdert worden.
		      </div>
		    </div>
		  </div>
-->

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse-e5">
							Logout
						</a>
					</h4>
				</div>
				<div id="collapse-e5" class="panel-collapse collapse">
					<div class="panel-body">
						Hiermee wordt de sessie beëindigd en zal de gebruiker opnieuw dienen in te loggen om de cockpit te kunnen benaderen.
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="tab-pane" id="editor"><br>

		<!-- Editor functiess -->

		<p class="bewerken handleidingintro"><i class="fa fa-pencil"></i> Editor opties</p>
		<p class="handleidingintro">Met de tekst editor kan je eenvoudig links en afbeeldingen plaatsen.</p><br>

		<div class="panel-group" id="accordion3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapse-o1">
							Link invoegen naar externe pagina of email
						</a>
					</h4>
				</div>
				<div id="collapse-o1" class="panel-collapse collapse">
					<div class="panel-body">
						Link naar pagina toevoegen in een tekst:
						Als je een link naar bijvoorbeeld een andere pagina in een tekst wil toevoegen kan je dit heel gemakkelijk doen door een tekst of een url te selecteren en vervolgens klik je op het ‘ketting’ symbool.
						<img class="img-responsive" style="padding-top:10px; padding-bottom:10px;" src="/cockpit/handleiding/images/link.jpg" />
						Je kan vervolgens de link naar de betreffende site invullen. Als je wil dat de site in een nieuw venster (tab-blad) geopend wordt kan je bij doelvenster in de pull-down klikken op ‘nieuw venster’. Hiermee blijft je eigen pagina actief en opent de site van de link in een nieuw venster / tab-blad. Wanneer je bij protocol email invult kan je ook een link naar een email-adres maken.
						<img class="img-responsive" style="padding-top:10px; padding-bottom:10px;" src="/cockpit/handleiding/images/linktype.png" />
						- Onder 'linktype' kan je een URL (andere website of pagina) toewijzen of een email adres koppelen aan een link.<Br>
						- Bij doelvenster wordt de link standaard geopend in dezelfde pagina. Je kan ook 'nieuwe pagina' kiezen, waarmee de site geopend blijft en de link in een ander tabblad of venster wordt geopend.
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapse-o2">
							Afbeelding toevoegen / bewerken
						</a>
					</h4>
				</div>
				<div id="collapse-o2" class="panel-collapse collapse">
					<div class="panel-body">
						Met de knop 'afbeelding kan je ind de tekst afbeeldingen plaatsen.
						<img class="img-responsive" style="padding-top:10px; padding-bottom:10px;" src="/cockpit/handleiding/images/afbeelding.jpg" />
						Met de button 'bladeren op server' kan je bestaande afbeeldingen selecteren en nieuwe afbeeldingen uploaden.
						<img class="img-responsive" style="padding-top:10px; padding-bottom:10px;" src="/cockpit/handleiding/images/bladeren.jpg" />
						Met de button 'uploaden' kan vervolgens een afbeelding geselecteerd worden van de computer en vervolgens kan met de button 'upload geselteerde bestand' het bestand naar de website geupload worden.
						<img class="img-responsive" style="padding-top:10px; padding-bottom:10px;" src="/cockpit/handleiding/images/uploadknop.jpg" />
						Wanneer er nu een afbeelding geselecteerd wordt (met dubbele klik) dan wordt deze vervolgens in de tekst geplaatst.
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapse-o3">
							Plakken van teksten uit Word of gekopieerde teksten uit een andere website
						</a>
					</h4>
				</div>
				<div id="collapse-o3" class="panel-collapse collapse">
					<div class="panel-body">
						Wanneer je tekst uit bijvoorbeeld een Word document of een website plakt in de website dan wordt de opmaak uit deze documenten over genomen. Om dit te voorkomen raden wij aan om de tekst te plakken als platte tekst.
						Dit zorgt ervoor dat de de opmaak uit de tekst wordt gehaald bij het plakken en het binnen de opmaak van de site past.
						<img class="img-responsive" style="padding-top:10px; padding-bottom:10px;" src="/cockpit/handleiding/images/plattetekst.jpg" />
					</div>
				</div>
			</div>

		</div>

	</div><!-- einde collapse -->


</div><!-- einde tabs -->