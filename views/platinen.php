
<?php

// modal Benutzerinformationen zur verfügung stellen
modal1($login_connection);


// modal detail zur verfügung stellen
modal2();


// modal einfügen/bearbeiten zur verfügung stellen
modal3();

// modal Legende zur verfügung stellen
modal4($currentpage);

echo'

	<div id="alertcontainer" class="container-fluid sticky-top">
	<center><div class="sticky-top" id="result">
	<div class="alert alert-success alertm" style="visibility:hidden">Platzhalter</div>
	</div></center>
	</div>


	<div class="container-fluid">
	<center><div id="leer"></div></center>
	</div>

	<div id="containerleiste" class="container-fluid" style="visibility: hidden;">	
	<div id="leiste" class=" my-3 bg-light">
	<div class="hinzu1">
	<a class="btn" id="button1" role="button">
	<i class="fa fa-plus-square" id="icons"></i>
	</a>
	<a class="btn" style="visibility: hidden;" id="buttonLegend" role="button" ><i class="fas fa-info-circle" id="icons"></i></a>
	</div>

	<div class="hinzu2"><a class="btn" style="visibility: hidden;" id="buttondefault" role="button" >
	<span class="fa-stack fa-lg">
	<i class="fas fa-filter fa-stack-1x"></i>
	<i class="fas fa-ban fa-stack-2x"></i>
	</span>
	</i></a>
	</div>


	<div class="hinzu3">
	<a class="btn btn-primary" style="visibility: hidden;" id="button3" href="#" role="button" data-toggle="collapse" data-target="#spCont" aria-expanded="false" aria-controls="collapseExample">
	<i class="fas fa-filter" id="icon2"></i></a>
	</div>

	</div>
	</div>

	<div class="container-fluid" id="tabellex">
	<div class="table-responsive" id="tabellecontainer">
		

	<table id="tabelle1"  style="width:100%" class="table text-center table-hover border ">

		

			<thead class="thead-light">
		
			

			<th class="no-sort":>&nbsp; &nbsp; Aktion</th>
			<th>Leiterkartenname</th>
			<th>Auftraggeber</th>
			<th>Lehrstuhl</th>
			<th>Ausstehend</th>
			<th>Anzahl</th>
			<th>Material</th>
			<th>Endkupfer(µ)</th>
			<th>Stärke(mm)</th>
			<th>Lagen</th>
			<th>Größe(mm)</th>
			<th>Oberfläche</th>
			<th>Lötstopp</th>
			<th>erstellt</th>
			<th>Wunschdatum</th>
			<th>Kommentar</th>
			
			<th>Status</th>
			<th>ignorieren</th>
			<th>abgeschlossenPost</th>
			

			
			<th>abgeschlossenFertigung</th>
			<th>downloads1or0</th>

			</thead>
			</table>

		</div>
		</div>
';

	if (isUserAdmin($login_connection) == true) { 
		echo'
		<script>adminn = "ja";</script>
		';
	}
	else {
		echo'
		<script>adminn = "nein";</script>
		';
	}


?>	



