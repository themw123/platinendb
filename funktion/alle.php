<?php

//gucken ob eingeloggter benutzer est ist
function isUserEst ($login_connection) {


//eingeloggter user
$user = mysqli_real_escape_string($login_connection, $_SESSION['user_name']);

//gucken ob es est ist
if ($user == "est") {
return true;
}

else {
return false;
}

}



// gucken ob zu bearbeitende oder detail anschauende Platine  dem eingeloggten Benutzer gehört

function legitimierung ($login_connection) {


	$id = mysqli_real_escape_string($login_connection, $_POST['Id']);
	$ziel =  mysqli_real_escape_string($login_connection, $_POST['ziel']);


	
	$auftraggeberquery = 
	"SELECT
	users.user_name as Nameee,
	platinen.ID
	FROM platinendb.platinen
	INNER JOIN login.users
	  ON platinen.Auftraggeber_ID = users.user_id
	WHERE platinen.ID = '$id'";



	$auftraggeberid =  mysqli_query($login_connection, $auftraggeberquery);
	$rowauftraggeber = mysqli_fetch_assoc($auftraggeberid);
	$VariableAuftraggeber = $rowauftraggeber["Nameee"];
	
	

	if ($VariableAuftraggeber == $_SESSION['user_name'] || "est" == $_SESSION['user_name'] ) {

		return true;
	}
	else {
		return false;
	}

	

	

}

function veraenderbarNutzen($platinendb_connection) {

	$id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);

	$query = 
	"SELECT
    	nutzenplatinen.Nutzen_ID
	FROM
  	  nutzenplatinen
	WHERE Nutzen_ID = '$id'";

	$queryresult1 =  mysqli_query($platinendb_connection, $query);
	$queryresult2 = mysqli_fetch_assoc($queryresult1);

	//Überprüfung nur wenn Platinen auf Nutzen drauf sind
	if($queryresult2 !== null) {

			$eigenschaftenNeu[1] = mysqli_real_escape_string($platinendb_connection, $_POST['Material']);
			$eigenschaftenNeu[2] = mysqli_real_escape_string($platinendb_connection, $_POST['Endkupfer']);
			$eigenschaftenNeu[3] = mysqli_real_escape_string($platinendb_connection, $_POST['Staerke']);
			$eigenschaftenNeu[4] = mysqli_real_escape_string($platinendb_connection, $_POST['Lagen']);


			$query = 
			"SELECT
			nutzen.ID,
			material.Name as Material,
			nutzen.Endkupfer,
			nutzen.Staerke,
			nutzen.Lagen
			FROM
				nutzen Inner Join
				material On nutzen.Material_ID = material.ID
			WHERE
				nutzen.ID = '$id'";


			$queryresult1 =  mysqli_query($platinendb_connection, $query);
			$queryresult2 = mysqli_fetch_assoc($queryresult1);

			$eigenschaftenAlt[1] = $queryresult2["Material"];
			$eigenschaftenAlt[2] = $queryresult2["Endkupfer"];
			$eigenschaftenAlt[3] = $queryresult2["Staerke"];
			$eigenschaftenAlt[4] = $queryresult2["Lagen"];


			//vergleichen
			$counter = 1;
			$veraenderbar = 0;
			while($counter <= count($eigenschaftenAlt)) {
				if($eigenschaftenAlt[$counter] == $eigenschaftenNeu[$counter]){
					$veraenderbar = $veraenderbar + 1;
				}
				$counter = $counter + 1;
			}
			if($veraenderbar == 4) {
				return true;
			}
			else{
				return false;
			}

	}	
	else {
		return true;
	}

}



function veraenderbarPlatine ($platinendb_connection) {

	$id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);

	$anzahlaufnutzen = 
	"SELECT
	platinendb.nutzenplatinen.Platinen_ID
	FROM
	platinendb.nutzenplatinen
	WHERE
	platinendb.nutzenplatinen.Platinen_ID = '$id'";

	$anzahlaufnutzen2=  mysqli_query($platinendb_connection, $anzahlaufnutzen);
	
	$anzahlaufnutzen3 = mysqli_fetch_assoc($anzahlaufnutzen2);	




	if ($anzahlaufnutzen3 !== null) {
		

		if(isUserEst($platinendb_connection) == true) {

			$aktion = mysqli_real_escape_string($platinendb_connection, $_POST["aktion"]);
			if($aktion != "loeschen") { 
	
					$eigenschaftenNeu[1] = mysqli_real_escape_string($platinendb_connection, $_POST['Material']);
					$eigenschaftenNeu[2] = mysqli_real_escape_string($platinendb_connection, $_POST['Endkupfer']);
					$eigenschaftenNeu[3] = mysqli_real_escape_string($platinendb_connection, $_POST['Staerke']);
					$eigenschaftenNeu[4] = mysqli_real_escape_string($platinendb_connection, $_POST['Lagen']);

					$query = 
					"SELECT
						platinen.ID,
						material.Name as Material,
						platinen.Endkupfer,
						platinen.Staerke,
						platinen.Lagen
					FROM
						platinen Inner Join
						material On platinen.Material_ID = material.ID
					WHERE platinen.ID = '$id'";


					$queryresult1 =  mysqli_query($platinendb_connection, $query);
					$queryresult2 = mysqli_fetch_assoc($queryresult1);

					$eigenschaftenAlt[1] = $queryresult2["Material"];
					$eigenschaftenAlt[2] = $queryresult2["Endkupfer"];
					$eigenschaftenAlt[3] = $queryresult2["Staerke"];
					$eigenschaftenAlt[4] = $queryresult2["Lagen"];


					//vergleichen
					$counter = 1;
					$veraenderbar = 0;
					while($counter <= count($eigenschaftenAlt)) {
						if($eigenschaftenAlt[$counter] == $eigenschaftenNeu[$counter]){
							$veraenderbar = $veraenderbar + 1;
						}
						$counter = $counter + 1;
					}
					if($veraenderbar == 4) {
						$array[0] = true;
						$array[1] = "xxx";
						return $array;
					}
					else{
						$array[0] = false;
						$array[1] = "nichtveraenderbar";
						return $array;
					}
			}
			else {
				$array[0] = false;
				$array[1] = "nichtveraenderbar";
				return $array;
			}	

		}
		else {
			$array[0] = false;
			$array[1] = "nichtest";
			return $array;
		}
			

}

else {
	$array[0] = true;
	$array[1] = "xxx";
	return $array;
}

	
}



//existens der paramater prüfen und gucken ob überhaupt übergeben wurde

function existens ($connection) {


if (isset($_POST["Id"]) && isset($_POST["ziel"])) {


$ziel = mysqli_real_escape_string($connection, $_POST['ziel']);
$url_id = mysqli_real_escape_string($connection, $_POST['Id']);

if($ziel == "platinen") {
$sqlx = "SELECT ID FROM platinenview WHERE ID='$url_id'";
}

elseif ($ziel == "nutzen") {
$sqlx = "SELECT ID FROM nutzenview WHERE ID='$url_id'";
}

elseif($ziel == "nutzenplatinen") {
$sqlx = "SELECT ID FROM platinenaufnutzen2 WHERE nuplid='$url_id'";
}

$resultx = mysqli_query($connection, $sqlx);


//gucken ob Platinen id in tabelle existiert 
if(mysqli_num_rows($resultx) > 0 ) {
	return true;
}
else {
	return false;
}


}

else {
echo'<div class="container-fluid">';
		 
echo"
<div class='alert alert-danger'> Es wurden nicht die benötigten parameter übergeben(Id und ziel).
</div>";

echo'</div>';  
}
	




}


function uploadSecurity(){
	//check type
	$finfo = finfo_open(FILEINFO_MIME_TYPE);// return mime-type extension
	$filePath = $_FILES['file']['tmp_name'];
	$type = finfo_file($finfo, $filePath);
	finfo_close($finfo);
	if($type != "text/plain") {
		die();
	}
	
	//check file size
	if ($_FILES['file']['size'] > 1500) {
		die();
	}	

}

function readfiledata() {
	$contents = file_get_contents($_FILES['file']['tmp_name']);
	$anfang = strpos($contents, ":Top")-8;
	$ende = strpos($contents, ":Bottom")+40;
	$contentsNeu = substr($contents, $anfang, $ende-$anfang);

	$contentsNeu = trim($contentsNeu);
	$contentsNeu = preg_replace("/[[:blank:]]+/","=",$contentsNeu);
	$anzahlLines = substr_count($contentsNeu, "\n" )+1;
	
	$row = $anzahlLines;


	$counter = 0;
	while($counter < $anzahlLines){
		$ende =  strpos($contentsNeu, "=");
		if($counter == 0) {
			$a[$counter][0] = "Top";
		}
		elseif($row == 1) {
			$a[$counter][0] = "Bottom";
		}
		else{
			$a[$counter][0] = "L".($counter+1);
		}

			
		$contentsNeu = substr($contentsNeu, strpos($contentsNeu, "=")+1, strlen($contentsNeu));
		if($row != 1) {
			$a[$counter][1] = substr($contentsNeu, 0, strpos($contentsNeu, "="));
		}
		else {
			$a[$counter][1] = substr($contentsNeu, 0, strlen($contentsNeu));
		}
		$contentsNeu = substr($contentsNeu, strpos($contentsNeu, "=")+1, strlen($contentsNeu));
		$counter = $counter+1;
		$row = $row -1;
	}
	$summe = 0;
	foreach($a as $value) {
		$summe = $summe + $value[1];
	}
	$a[$counter][0] = "LagenSumme";
	$a[$counter][1] = $summe;
	return $a;
}	
	


function lagenBefehl($a) {
	$befehl = "";

	foreach ($a as $wert) {
		$befehl .= $wert[0] . " = " . "'" . $wert[1] . "'" . "," ;
	}
	$befehl = substr($befehl, 0, strlen($befehl)-1);
	return $befehl;

}



//modal für einfügen und bearbeiten
//der modal-title wird durch javascript eingefügt
function modal3() {

	echo'	
		<div id="dataModal2" style=" padding-right:0!important" class="modal fade">  
		<div id=modalbearbeiten class="modal-dialog modal-dialog-centered modal-xl">  
			 <div class="modal-content">  
				  <div class="modal-header">   
					   <h4 class="modal-title"></h4>  
				  </div>  
				  <div class="modal-body" id="modalbody2">  
				  </div>  
				  <div class="modal-footer">  
				  <button id="button5" type="button" class="btn btn-primary" data-dismiss="modal">abbrechen</button>  
				  </div>  
			 </div>  
		</div>  
	</div> 
	
	';
	
}


//modal für detail

function modal2() {

echo'	
	<div id="dataModal1" class="modal fade">  
	<div class="modal-dialog modal-dialog-centered modal-lg">  
		 <div class="modal-content">  
			  <div class="modal-header">   
				   <h4 class="modal-title">Platinen auf Nutzen</h4>  
			  </div>  
			  <div class="modal-body" id="modalbody1">
			  
			  <div class="dynamischetabelle">

			  </div>

			  
			  <div>
			  <div class="container-fluid resultanzahl">
			  <center><div id="resultanzahl"></div></center>
			  </div>
			  </div>


			  <div class="hinzufuegen">
			  		

			  <div class="hinzufuegen">
			  <div class="container-fluid nutzenplatinen">
			  <a class="btn" id="button9" data-toggle="collapse" data-target="#collapse1" role="button">
			  <i class="far fa-caret-square-down button9" id="icons">
			  </i>
			  </a>
			  <div class="collapse" id="collapse1">  
			  <p><span style="font-size:18pt">Platinen hinzufügen</span></p>

			  <div class="zusammen d-flex">

			  <div class="form-group anzahldiv">
			  <label for="usr">Anzahl:</label>
			  <input type="number" min="1" class="form-control" id="anzahl2" name="Anzahl">
			  </div>
			
			  </div>


			  <div class="container-fluid platinenadd">
			  </div>
			  </div>
			  </div>  
			  </div> 

			  </div>

			  </div>  
			  <div class="modal-footer">  
			  <button id="button5" type="button" class="btn btn-primary" data-dismiss="modal">schließen</button>  
			  </div>  
		 </div>  
	</div>  
</div> 

';

}


//modal für benutzerinformationen

function modal1($login_connection) {


	
echo "
<!-- Modal -->
<div class='modal fade' id='exampleModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
  <div class='modal-dialog' role='document'>
	<div class='modal-content'>
	  <div class='modal-header'>
		<h5 class='modal-title' id='exampleModalLabel'>Benutzerinformationen</h5>
	  </div>
	  <div class='modal-body'>
	  eingeloggt:  $_SESSION[user_name] <br> <br>
	  Berechtigung: 
	  ";
	  if (isUserEst ($login_connection) == true) { 
	  echo 'Admin';
	  }

	  else { 
		echo 'Standardbenutzer';
	  }
echo"
	  </div>
	  <div class='modal-footer'>
		<button id='button2' type='button' class='btn btn-primary' data-dismiss='modal'>schließen</button>
	  </div>
	</div>
  </div>
</div>		
";
}



