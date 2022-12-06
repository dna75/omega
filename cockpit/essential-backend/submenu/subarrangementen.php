<div role="tabpanel">
	<ul class="nav nav-tabs" id="subnav" role="tablist">
		<?
			$active = $_GET['page'];
			$sub = $_GET['sub'];
		?>
	    <li role="" class="<? if($active == 'arrangementgroepen') { echo 'active'; } else { };?>"><a href="index.php?sub=arrangementgroepen&page=arrangementgroepen">Arrangement Groepen</a></li>
	    <li role="" class="<? if($active == 'arrangementen') { echo 'active'; } else { };?>"><a href="index.php?sub=arrangementgroepen&page=arrangementen">Arrangementen</a></li>
	</ul>
</div>
