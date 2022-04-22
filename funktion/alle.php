<?php

//gucken ob eingeloggter benutzer est ist
function isUserAdmin ($login_connection) {

	//eingeloggter user holen
	//$user = mysqli_real_escape_string($login_connection, $_SESSION['user_name']);

	$userPriv = mysqli_real_escape_string($login_connection, $_SESSION['admin']);
	
	if ($userPriv == 1) {
		return true;
	}

	else {
		return false;
	}

}

function isThisUserAdmin ($login_connection, $username) {

	//eingeloggter user holen
	//$user = mysqli_real_escape_string($login_connection, $_SESSION['user_name']);

	$admin = 
	"SELECT
	users.admin
	FROM login.users
	WHERE user_name = '$username'";



	$admin =  mysqli_query($login_connection, $admin);
	$admin = mysqli_fetch_assoc($admin);
	$admin = $admin["admin"];
	

	if ($admin == "1") {
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
	
	

	if ($VariableAuftraggeber == $_SESSION['user_name'] || "1" == $_SESSION['admin'] ) {

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
		

		if(isUserAdmin($platinendb_connection) == true) {

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


function uploadSecurity($toCheck){
	//check type
	$fileInfo = finfo_open(FILEINFO_MIME_TYPE);// return mime-type extension
	$filePath = $_FILES['file']['tmp_name'];
	$fileSize = filesize($filePath);
	$fileType = finfo_file($fileInfo, $filePath);
	finfo_close($fileInfo);

	if($toCheck == "text") {
		if($fileType != "text/plain") {
			die();
		}
		
		//check file size(ungefähr > 1,5kb)
		if ($fileSize > 1500) {
			die();
		}	
	}

	else if($toCheck == "archive") {
		if($fileType != "application/zip" && $fileType != "application/x-rar") {
			die();
		}
		
		//check file size(ungefähr > 2mb)
		if ($fileSize > 2000000) {
			die();
		}	
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


function deleteDownload($PlatinenID, $platinendb_connection) {
	//wenn platine im zustand abgeschlossenPost = 1 ist, dann lösche Download_ID und den download
	$abgeschlossenFertigung = "select abgeschlossenFertigung from platinenviewest where id = '$PlatinenID'";
	$abgeschlossenFertigung = mysqli_query($platinendb_connection,$abgeschlossenFertigung);
	$abgeschlossenFertigung = mysqli_fetch_array($abgeschlossenFertigung);
	$abgeschlossenFertigung = $abgeschlossenFertigung['abgeschlossenFertigung']; 
		  
		
	if($abgeschlossenFertigung == 1) {
		$deleteDownload_IDInPlatinen = "update platinen set Downloads_ID = null where ID = '$PlatinenID'";
	
		$download_id = "SELECT Downloads_ID FROM platinen WHERE ID = '$PlatinenID'";
		$download_id = mysqli_query($platinendb_connection,$download_id);
		$download_id = mysqli_fetch_array($download_id);
		$download_id = $download_id['Downloads_ID']; 
		$deleteDownload = "delete from downloads where ID = '$download_id'";
		mysqli_query($platinendb_connection, $deleteDownload_IDInPlatinen);
		mysqli_query($platinendb_connection, $deleteDownload);
	}
}



function isInFertigung($id, $platinendb_connection) {
	$abgeschlossenPost = "select abgeschlossenPost from platinenviewest where ID = $id";
	$abgeschlossenPost = mysqli_query($platinendb_connection,$abgeschlossenPost);
	$abgeschlossenPost = mysqli_fetch_array($abgeschlossenPost);
	$abgeschlossenPost = $abgeschlossenPost['abgeschlossenPost']; 

	if($abgeschlossenPost == 1) {
		return true;
	}
	else {
		return false;
	}

}

function isOnNutzen($id, $platinendb_connection) {
	$anzahl = "select Anzahl from platinenviewest where ID = $id";
	$anzahl = mysqli_query($platinendb_connection,$anzahl);
	$anzahl = mysqli_fetch_array($anzahl);
	$anzahl = $anzahl['Anzahl']; 

	$ausstehend = "select ausstehend from platinenviewest where ID = $id";
	$ausstehend = mysqli_query($platinendb_connection,$ausstehend);
	$ausstehend = mysqli_fetch_array($ausstehend);
	$ausstehend = $ausstehend['ausstehend']; 

	if($anzahl == $ausstehend) {
		return false;
	}
	else {
		return true;
	}
}


function ueberfuehren($id, $Anzahl, $Bearbeiter, $Material_ID, $Endkupfer, $Staerke, $Lagen, $platinendb_connection) {
	//Neuen Nutzen anlegen
	
	$nr = "select max(Nr)+1 as Nr from nutzen";
	$nr = mysqli_query($platinendb_connection,$nr);
	$nr = mysqli_fetch_array($nr);
	$nr = $nr['Nr']; 
	if($nr == null) {
		$nr = 1;
	}


	$bearbeiterId = "select user_id from login.users where user_name = '$Bearbeiter'";
	$bearbeiterId = mysqli_query($platinendb_connection,$bearbeiterId);
	$bearbeiterId = mysqli_fetch_array($bearbeiterId);
	$bearbeiterId = $bearbeiterId['user_id'];

	//Datum
	$Erstellt = date('Y-m-d H:i:s', time());

	//Nutzen anlegen
	$nutzen = "INSERT INTO nutzen (Nr, Bearbeiter_ID, Material_ID, Endkupfer, Staerke, Lagen, Groesse, Datum, intoderext, Status1, Testdaten, Datum1, Kommentar) VALUES ('$nr', '$bearbeiterId', '$Material_ID', '$Endkupfer', '$Staerke', '$Lagen', 'individuell', '$Erstellt', 'ext', 'Fertigung', '0', '$Erstellt', '')";
	mysqli_query($platinendb_connection, $nutzen);



	//Platine auf Nutzen packen

	//Id von nutzen
	$nutzenId = "select ID from nutzen where Nr = $nr";
	$nutzenId = mysqli_query($platinendb_connection,$nutzenId);
	$nutzenId = mysqli_fetch_array($nutzenId);
	$nutzenId = $nutzenId['ID'];

	$PlaufNutzen = "INSERT INTO nutzenplatinen (Platinen_ID, Nutzen_ID, platinenaufnutzen) VALUES ($id, $nutzenId, $Anzahl)";
	mysqli_query($platinendb_connection, $PlaufNutzen);
}


function zustandNeu($platinendb_connection, $NutzenID) {
	$getZustand = "SELECT Status1 FROM nutzen WHERE ID=$NutzenID";
	$getZustand = mysqli_query($platinendb_connection, $getZustand);
	$getZustand = mysqli_fetch_array($getZustand);
	$getZustand = $getZustand['Status1'];

	if($getZustand == "neu") {
		return true;
	}
	else {
		return false;
	}
}

function sendMail($art, $user_name, $user_email, $user_password_hash, $user_standort) {
	if($art == "newPlatineNotification") {
		require_once('../libraries/PHPMailer.php');
	}
	
	$mail = new PHPMailer;

	//damit Umlaute richtig angezeigt werden
	$mail->CharSet = 'utf-8'; 

	// please look into the config/config.php for much more info on how to use this!
	// use SMTP or use mail()
	if (EMAIL_USE_SMTP) {
		// Set mailer to use SMTP
		$mail->IsSMTP();
		//useful for debugging, shows full SMTP errors
		//$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		// Enable SMTP authentication
		$mail->SMTPAuth = EMAIL_SMTP_AUTH;
		// Enable encryption, usually SSL/TLS
		if (defined(EMAIL_SMTP_ENCRYPTION)) {
			$mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;
		}
		// Specify host server
		$mail->Host = EMAIL_SMTP_HOST;
		$mail->Username = EMAIL_SMTP_USERNAME;
		$mail->Password = EMAIL_SMTP_PASSWORD;
		$mail->Port = EMAIL_SMTP_PORT;
	} else {
		$mail->IsMail();
	}

	$mail->From = EMAIL_PASSWORDRESET_FROM;
	$mail->FromName = EMAIL_PASSWORDRESET_FROM_NAME;


	if($art == "validation") {
		$mail->Subject = ACCOUNT_VALIDATE_SUBJECT;
		$mail->AddAddress(ACCOUNT_VALIDATE_TO);

		$accountinfo = "Benutzername: " . $user_name . "\n" . "E-Mail-Adresse: " . $user_email . "\n" . "Standort: " . $user_standort;

		//$link = ACCOUNT_VALIDATE_URL;
		$link = ACCOUNT_VALIDATE_URL.'&user_name='.urlencode($user_name).'&user_email='.urlencode($user_email).'&user_password='.urlencode($user_password_hash).'&user_standort='.urlencode($user_standort) . "\n \n \n" ;
	
		$mail->Body = ACCOUNT_VALIDATE_CONTENT . '' . $link . '' . $accountinfo;
	}
	else if($art == "userNotification")  {
		$mail->Subject = NOTIFICATION_SUBJECT;
		$mail->AddAddress($user_email);
		
		$benutzername = "\n\n Benutzername: " . $user_name;

		$mail->Body = NOTIFICATION_CONTENT . '' . $benutzername;
	}

	else if($art == "newPlatineNotification")  {
		$mail->Subject = NEWPLATINE_SUBJECT;
		$mail->AddAddress($user_email);
		
		$benutzername = "\n\n Benutzername: " . $user_name;

		$mail->Body = NEWPLATINE_CONTENT . '' . $benutzername;
	}
	

	if(!$mail->Send()) {
		if($art != "newPlatineNotification") {
			//$this->errors[] = MESSAGE_PASSWORD_RESET_MAIL_FAILED . $mail->ErrorInfo;
		}
		return false;
	} else {
		return true;
	}
}



function modal4($currentpage) {

	echo'	
		<div id="dataModal3" style=" padding-right:0!important" class="modal fade">  
		<div class="modal-dialog" role="document">
	

			 <div class="modal-content">  
				  <div class="modal-header">   
					   <h4 class="modal-title">Legende</h4>  
				  </div>  
				  <div class="modal-body" id="modalbody3">  
				 	';

					 if ($currentpage == "platinenindex") {
						echo'
						<p style="text-align:center;font-size:20px;font-weight:600;">Zeilenfarbe:</p> 
						<p><span style="color:#005ea9">Blau</span> = mindestens eine Platine im Nutzen-Zustand neu/post </p> 
						<p><span style="color:#e89b02">Orange</span> = mindestens eine Platine im Nutzen-Zustand Fertigung </p> 
						<p><span style="color:green">Grün</span> = alle Platinen sind abgeschlossen </p>
						<p style="text-align:center;font-size:20px;font-weight:600;">Warnfarbe:</p> 
						
						<p><i class="fas fa-exclamation-triangle red"></i> = Platine länger als 15 Tage im Zustand Neu</p>
						<p><i class="fas fa-exclamation-triangle orange"></i> = Platine länger als 10 Tage im Zustand Neu</p>
						';
					 }
					 else {
						echo'
						<p style="text-align:center;font-size:20px;font-weight:600;">Warnfarbe:</p> 
						<p><i class="fas fa-exclamation-triangle red"></i> = Nutzen länger als 5 Tage im Zustand Fertigung</p>
						';	
					 }

				  echo' 
				  </div>  
				  <div class="modal-footer">  
				  <button id="button5" type="button" class="btn btn-primary" data-dismiss="modal">schließen</button>  
				  </div>  
			 </div>  
		</div>  
	</div> 
	
	';
	
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

	  <table class='tableInfo' style='width:100%'>
	  <tr>
		<th>Name:</th>
		<td> $_SESSION[user_name] </td>
	  </tr>
	  <tr>
		<th>E-Mail:</th>
		<td> $_SESSION[user_email] </td>
	  </tr>
	  <tr>
	  	<th>Berechtigung:</th>
	  	<td>
		"; 
		if (isUserAdmin ($login_connection) == true) { 
				echo 'Admin';
			}
			else { 
			  echo 'Standardbenutzer';
		}
		echo "
		</td>
	  </tr>
	  </table>
	  ";

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

