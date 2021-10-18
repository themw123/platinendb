
<?php

// modal (Benutzerinformationen) zur verfügung stellen
modal1($login_connection);


// modal (detail) zur verfügung stellen
modal2();


// modal (bearbeitung) zur verfügung stellen
modal3();



echo'<div class="container-fluid">';
echo'<center><div id="result"></div></center>';
echo'</div>';

echo'<div class="container-fluid">';
echo'<center><div id="leer"></div></center>';
echo'</div>';

echo'<div id="containerleiste" class="container-fluid" style="visibility: hidden;">';	
echo'<div id="leiste" class=" my-3 bg-light">';
echo'<div class="hinzu1"><a class="btn" id="button1" role="button" ><i class="fa fa-plus-square" id="icons"></i></a></div>';
if (isUserEst($login_connection) == true) {
	echo'<div class="hinzu2"><a class="btn" id="buttondefault" role="button" >
	<span class="fa-stack fa-lg">
	<i class="fas fa-filter fa-stack-1x"></i>
	<i class="fas fa-ban fa-stack-2x"></i>
	</span>
	</i></a>
	</div>';
}
echo'
<div class="hinzu3">
<a class="btn btn-primary" style="visibility: hidden;" id="button3" href="#" role="button" data-toggle="collapse" data-target="#spCont" aria-expanded="false" aria-controls="collapseExample">
<i class="fas fa-filter" id="icon2"></i></a>
</div>
';
echo'</div>';
echo'</div>';

echo'<div class="container-fluid" id="tabellex">';
echo'<div class="table-responsive" id="tabellecontainer">';
	

echo'<table id="tabelle1"  style="width:100%" class="table text-center table-hover border ">';

	

		echo '<thead class="thead-light">';
	
		

		echo'<th class="no-sort":>&nbsp; &nbsp; Aktion</th>';
		echo'<th>Leiterkartenname</th>';
		echo'<th>Auftraggeber</th>';
		echo'<th>Ausstehend</th>';
		echo'<th>Anzahl</th>';
		echo'<th>Material</th>';
		echo'<th>Endkupfer(µ)</th>';
		echo'<th>Stärke(mm)</th>';
		echo'<th>Lagen</th>';
		echo'<th>Größe(mm)</th>';
		echo'<th>Oberfläche</th>';
		echo'<th>Lötstopp</th>';
		echo'<th>erstellt</th>';
		echo'<th>Wunschdatum</th>';
		echo'<th>Kommentar</th>';
		
		echo'<th>Status</th>';
		echo'<th>ignorieren</th>';
		echo'<th>abgeschlossenPost</th>';
		echo'<th>10Tage</th>';
		echo'<th>14Tage</th>';
		echo'<th>dringlichkeitPost</th>';
		echo'<th>abgeschlossenFertigung</th>';

		echo'</thead>';
		echo'</table>';	

	echo'</div>';
	echo'</div>';


	if (isUserEst($login_connection) == true) { 
		echo'
		<script>est = "ja";</script>
		';
	}
	else {
		echo'
		<script>est = "nein";</script>
		';
	}


?>	



