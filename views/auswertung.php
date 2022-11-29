<?php


// modal Benutzerinformationen zur verfügung stellen
modal1($login_connection);

?>

<div id="alertcontainer" class="container-fluid sticky-top">
	<div class="sticky-top" id="result">
		<div class="alert alert-success alertm" style="visibility:hidden">Platzhalter</div>
	</div>
</div>

<div class="container">
	<h3>Platinenaufträge</h3>
</div>

<div class="container" id="options" style="visibility:hidden">

	<div id="downloadDiv">
		<label id="downloadlabel" for="usr">Download :
			<i class='fas fa-info-circle' id='infoicon' data-toggle='popover' title='Hinweis' data-content='Falls Tabelle der generierten PDF zu klein ist, Tabelle durch zoom vergrößern, anschließend Seite neu laden und erneut downloaden.'></i>
		</label>
		<button id="downloadpdf" class="btn btn-primary">PDF</button>
	</div>

	<div class="filter">
		<div class="set" id="zeitintervalDiv">
			<label for="usr">Zeitinterval:</label>

			<select class="form-control" id="zeitinterval" name="Zeitinterval" required="">
				<option>Monate</option>
				<option>Jahre</option>
			</select>

		</div>

		<div class="set" id="jahroderletztenDiv">
			<label id="jahrlabel" for="usr">Jahr:</label>

			<select class="form-control" id="jahroderletzten" name="jahroderletzten" required="">

			</select>
		</div>

		<div class="set" id="auftraggeberDiv">
			<label id="auftraggeberlabel" for="usr">Auftraggeber:</label>
			<select title="alle" class="form-control" data-live-search="true" id="auftraggeber" name="Auftraggeber">
			</select>
		</div>
	</div>

</div>

</div>


<div class="container" id="chartdiv">
	<canvas class="chart" id="chart1"></canvas>
</div>