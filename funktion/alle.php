<?php

//gucken ob eingeloggter benutzer est ist
function isUserAdmin($login_connection)
{

	//eingeloggter user holen
	//$user = mysqli_real_escape_string($login_connection, $_SESSION['user_name']);

	$userPriv = mysqli_real_escape_string($login_connection, $_SESSION['admin']);

	if ($userPriv == 1) {
		return true;
	} else {
		return false;
	}
}

function isThisUserAdmin($login_connection, $username)
{

	//eingeloggter user holen
	//$user = mysqli_real_escape_string($login_connection, $_SESSION['user_name']);
	$stmt = $login_connection->prepare(
		"
		SELECT
		users.admin
		FROM login.users
		WHERE user_name = ?
		;"
	);
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$admin = $stmt->get_result();
	$admin = mysqli_fetch_assoc($admin)["admin"];


	if ($admin == "1") {
		return true;
	} else {
		return false;
	}
}

// gucken ob zu bearbeitende oder detail anschauende Platine  dem eingeloggten Benutzer gehört

function legitimierung($login_connection)
{


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


	$stmt = $login_connection->prepare(
		"SELECT
		users.user_name as Nameee,
		platinen.ID
		FROM platinendb.platinen
		INNER JOIN login.users
		  ON platinen.Auftraggeber_ID = users.user_id
		WHERE platinen.ID = ?
		;"
	);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$auftraggeberid = $stmt->get_result();
	$rowauftraggeber = mysqli_fetch_assoc($auftraggeberid);
	$VariableAuftraggeber = $rowauftraggeber["Nameee"];



	if ($VariableAuftraggeber == $_SESSION['user_name'] || "1" == $_SESSION['admin']) {

		return true;
	} else {
		return false;
	}
}

function veraenderbarNutzen($platinendb_connection)
{

	$id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);


	$stmt = $platinendb_connection->prepare(
		"SELECT
    	nutzenplatinen.Nutzen_ID
		FROM
		nutzenplatinen
		WHERE Nutzen_ID = ?;"
	);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$queryresult = $stmt->get_result();
	$queryresult = mysqli_fetch_assoc($queryresult);

	//Überprüfung nur wenn Platinen auf Nutzen drauf sind
	if ($queryresult !== null) {

		$eigenschaftenNeu[1] = mysqli_real_escape_string($platinendb_connection, $_POST['Material']);
		$eigenschaftenNeu[2] = mysqli_real_escape_string($platinendb_connection, $_POST['Endkupfer']);
		$eigenschaftenNeu[3] = mysqli_real_escape_string($platinendb_connection, $_POST['Staerke']);
		$eigenschaftenNeu[4] = mysqli_real_escape_string($platinendb_connection, $_POST['Lagen']);




		$stmt = $platinendb_connection->prepare(
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
				nutzen.ID = ?;"
		);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$queryresult = $stmt->get_result();
		$queryresult = mysqli_fetch_assoc($queryresult);

		$eigenschaftenAlt[1] = $queryresult["Material"];
		$eigenschaftenAlt[2] = $queryresult["Endkupfer"];
		$eigenschaftenAlt[3] = $queryresult["Staerke"];
		$eigenschaftenAlt[4] = $queryresult["Lagen"];


		//vergleichen
		$counter = 1;
		$veraenderbar = 0;
		while ($counter <= count($eigenschaftenAlt)) {
			if ($eigenschaftenAlt[$counter] == $eigenschaftenNeu[$counter]) {
				$veraenderbar = $veraenderbar + 1;
			}
			$counter = $counter + 1;
		}
		if ($veraenderbar == 4) {
			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}



function veraenderbarPlatine($platinendb_connection)
{

	$id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);



	$stmt = $platinendb_connection->prepare(
		"SELECT
		platinendb.nutzenplatinen.Platinen_ID
		FROM
		platinendb.nutzenplatinen
		WHERE
		platinendb.nutzenplatinen.Platinen_ID = ?"
	);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$anzahlaufnutzen = $stmt->get_result();
	$anzahlaufnutzen = mysqli_fetch_assoc($anzahlaufnutzen);




	if ($anzahlaufnutzen !== null) {


		if (isUserAdmin($platinendb_connection) == true) {

			$aktion = mysqli_real_escape_string($platinendb_connection, $_POST["aktion"]);
			if ($aktion != "loeschen") {

				$eigenschaftenNeu[1] = mysqli_real_escape_string($platinendb_connection, $_POST['Material']);
				$eigenschaftenNeu[2] = mysqli_real_escape_string($platinendb_connection, $_POST['Endkupfer']);
				$eigenschaftenNeu[3] = mysqli_real_escape_string($platinendb_connection, $_POST['Staerke']);
				$eigenschaftenNeu[4] = mysqli_real_escape_string($platinendb_connection, $_POST['Lagen']);

				$stmt = $platinendb_connection->prepare(
					"SELECT
						platinen.ID,
						material.Name as Material,
						platinen.Endkupfer,
						platinen.Staerke,
						platinen.Lagen
					FROM
						platinen Inner Join
						material On platinen.Material_ID = material.ID
					WHERE platinen.ID = ?"
				);
				$stmt->bind_param("i", $id);
				$stmt->execute();
				$queryresult = $stmt->get_result();
				$queryresult = mysqli_fetch_assoc($queryresult);

				$eigenschaftenAlt[1] = $queryresult["Material"];
				$eigenschaftenAlt[2] = $queryresult["Endkupfer"];
				$eigenschaftenAlt[3] = $queryresult["Staerke"];
				$eigenschaftenAlt[4] = $queryresult["Lagen"];


				//vergleichen
				$counter = 1;
				$veraenderbar = 0;
				while ($counter <= count($eigenschaftenAlt)) {
					if ($eigenschaftenAlt[$counter] == $eigenschaftenNeu[$counter]) {
						$veraenderbar = $veraenderbar + 1;
					}
					$counter = $counter + 1;
				}
				if ($veraenderbar == 4) {
					$array[0] = true;
					$array[1] = "xxx";
					return $array;
				} else {
					$array[0] = false;
					$array[1] = "nichtveraenderbar";
					return $array;
				}
			} else {
				$array[0] = false;
				$array[1] = "nichtveraenderbar";
				return $array;
			}
		} else {
			$array[0] = false;
			$array[1] = "nichtest";
			return $array;
		}
	} else {
		$array[0] = true;
		$array[1] = "xxx";
		return $array;
	}
}



//existens der paramater prüfen und gucken ob überhaupt übergeben wurde

function existens($connection)
{


	if (isset($_POST["Id"]) && isset($_POST["ziel"])) {


		$ziel = mysqli_real_escape_string($connection, $_POST['ziel']);
		$url_id = mysqli_real_escape_string($connection, $_POST['Id']);

		if ($ziel == "platinen") {
			$sql = "SELECT ID FROM platinenview WHERE ID=?";
		} elseif ($ziel == "nutzen") {
			$sql = "SELECT ID FROM nutzenview WHERE ID=?";
		} elseif ($ziel == "nutzenplatinen") {
			$sql = "SELECT ID FROM platinenaufnutzen2 WHERE nuplid=?";
		}


		$stmt = $connection->prepare($sql);
		$stmt->bind_param("i", $url_id);
		$stmt->execute();
		$queryresult = $stmt->get_result();

		//gucken ob Platinen id in tabelle existiert 
		if (mysqli_num_rows($queryresult) > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		echo '<div class="container-fluid">';

		echo "
<div class='alert alert-danger'> Es wurden nicht die benötigten parameter übergeben(Id und ziel).
</div>";

		echo '</div>';
	}
}


function uploadSecurity($toCheck)
{
	//check type
	$fileInfo = finfo_open(FILEINFO_MIME_TYPE); // return mime-type extension
	$filePath = $_FILES['file']['tmp_name'];
	$fileSize = filesize($filePath);
	$fileType = finfo_file($fileInfo, $filePath);
	finfo_close($fileInfo);

	if ($toCheck == "text") {
		if ($fileType != "text/plain") {
			die();
		}

		//check file size(ungefähr > 1,5kb)
		if ($fileSize > 1500) {
			die();
		}
	} else if ($toCheck == "archive") {
		if ($fileType != "application/zip" && $fileType != "application/x-rar") {
			die();
		}

		//check file size(ungefähr > 2mb)
		if ($fileSize > 2000000) {
			die();
		}
	}
}

function readfiledata()
{

	$contents = file_get_contents($_FILES['file']['tmp_name']);

	if (!(strpos($contents, ":Top") !== false) && !(strpos($contents, ":Bottom") !== false)) {
		// weder top noch bottom
		return;
	} else {
		if ((strpos($contents, ":Top") !== false) && (strpos($contents, ":Bottom") !== false)) {
			//top und bottom
			$anfang = strpos($contents, ":Top") - 8;
			$ende = strpos($contents, ":Bottom") + 40;

			$contentsNeu = substr($contents, $anfang, $ende - $anfang);

			$contentsNeu = trim($contentsNeu);
			$contentsNeu = preg_replace("/[[:blank:]]+/", "=", $contentsNeu);
			$anzahlLines = substr_count($contentsNeu, "\n") + 1;

			$row = $anzahlLines;


			$counter = 0;
			while ($counter < $anzahlLines) {
				$ende =  strpos($contentsNeu, "=");
				if ($counter == 0) {
					$a[$counter][0] = "Top";
				} elseif ($row == 1) {
					$a[$counter][0] = "Bottom";
				} else {
					$a[$counter][0] = "L" . ($counter + 1);
				}


				$contentsNeu = substr($contentsNeu, strpos($contentsNeu, "=") + 1, strlen($contentsNeu));
				if ($row != 1) {
					$a[$counter][1] = substr($contentsNeu, 0, strpos($contentsNeu, "="));
				} else {
					$a[$counter][1] = substr($contentsNeu, 0, strlen($contentsNeu));
				}
				$contentsNeu = substr($contentsNeu, strpos($contentsNeu, "=") + 1, strlen($contentsNeu));
				$counter = $counter + 1;
				$row = $row - 1;
			}
			$summe = 0;
			foreach ($a as $value) {
				$summe = $summe + $value[1];
			}
			$a[$counter][0] = "LagenSumme";
			$a[$counter][1] = $summe;
			return $a;
		} else if (strpos($contents, ":Top") !== false) {
			//nur top
			$anfang = strpos($contents, ":Top") + 5;
			$ende = strlen($contents);
			$contentsNeu = substr($contents, $anfang, $ende - $anfang);
			$contentsNeu = trim($contentsNeu);
			$contentsNeu = preg_replace("/[[:blank:]]+/", "=", $contentsNeu);
			$a[0][0] = "Top";
			$a[0][1] = substr($contentsNeu, 0, strpos($contentsNeu, "="));
			$a[1][0] = "LagenSumme";
			$a[1][1] = substr($contentsNeu, 0, strpos($contentsNeu, "="));
		} else if (strpos($contents, ":Bottom") !== false) {
			//nur bottom
			$anfang = strpos($contents, ":Bottom") + 7;
			$ende = strlen($contents);
			$contentsNeu = substr($contents, $anfang, $ende - $anfang);
			$contentsNeu = trim($contentsNeu);
			$contentsNeu = preg_replace("/[[:blank:]]+/", "=", $contentsNeu);
			$a[0][0] = "Bottom";
			$a[0][1] = substr($contentsNeu, 0, strpos($contentsNeu, "="));
			$a[1][0] = "LagenSumme";
			$a[1][1] = substr($contentsNeu, 0, strpos($contentsNeu, "="));
		}
		return $a;
	}
}



function lagenAnlegen($a, $platinendb_connection)
{

	$aNeu = array(
		array("Top", "null"),
		array("L2", "null"),
		array("L3", "null"),
		array("L4", "null"),
		array("L5", "null"),
		array("Bottom", "null"),
		array("LagenSumme", "null"),
	);

	$counter = 0;
	foreach ($aNeu as $valueNeu) {
		foreach ($a as $value) {
			if ($valueNeu[0] == $value[0]) {
				$aNeu[$counter][1] = "'" . $value[1] . "'";
				break;
			}
		}
		$counter++;
	}

	$top = $aNeu[0][1];
	$l2 = $aNeu[1][1];
	$l3 = $aNeu[2][1];
	$l4 = $aNeu[3][1];
	$l5 = $aNeu[4][1];
	$bottom = $aNeu[5][1];
	$lagenSumme = $aNeu[6][1];

	$lagen = "INSERT INTO nutzenlagen (Top, L2, L3, L4, L5, Bottom, LagenSumme) VALUES ($top, $l2, $l3, $l4, $l5, $bottom, $lagenSumme)";
	mysqli_query($platinendb_connection, $lagen);

	$Lagen_ID = mysqli_insert_id($platinendb_connection);

	return $Lagen_ID;
}


function deleteDownload($PlatinenID, $platinendb_connection)
{
	//wenn platine im zustand abgeschlossenPost = 1 ist, dann lösche Download_ID und den download
	$stmt = $platinendb_connection->prepare(
		"select abgeschlossenFertigung from platinenviewest where id = ?"
	);
	$stmt->bind_param("i", $PlatinenID);
	$stmt->execute();
	$queryresult = $stmt->get_result();
	$queryresult = mysqli_fetch_assoc($queryresult);
	$abgeschlossenFertigung = $queryresult['abgeschlossenFertigung'];



	if ($abgeschlossenFertigung == 1) {

		$download_id = "SELECT Downloads_ID FROM platinen WHERE ID = ?";
		$stmt = $platinendb_connection->prepare(
			"SELECT Downloads_ID FROM platinen WHERE ID = ?"
		);
		$stmt->bind_param("i", $PlatinenID);
		$stmt->execute();
		$queryresult = $stmt->get_result();
		$queryresult = mysqli_fetch_assoc($queryresult);
		$download_id = $queryresult['Downloads_ID'];

		$stmt = $platinendb_connection->prepare(
			"update platinen set Downloads_ID = null where ID = ?"
		);
		$stmt->bind_param("i", $PlatinenID);
		$stmt->execute();


		$stmt = $platinendb_connection->prepare(
			"delete from downloads where ID = ?"
		);
		$stmt->bind_param("i", $download_id);
		$stmt->execute();
	}
}



function isInFertigung($id, $platinendb_connection)
{
	$stmt = $platinendb_connection->prepare(
		"select abgeschlossenPost from platinenviewest where ID = ?"
	);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$queryresult = $stmt->get_result();
	$queryresult = mysqli_fetch_array($queryresult);
	$abgeschlossenPost = $queryresult['abgeschlossenPost'];

	if ($abgeschlossenPost == 1) {
		return true;
	} else {
		return false;
	}
}

function isOnNutzen($id, $platinendb_connection)
{

	$stmt = $platinendb_connection->prepare(
		"select Anzahl from platinenviewest where ID = ?"
	);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$queryresult = $stmt->get_result();
	$queryresult = mysqli_fetch_array($queryresult);
	$anzahl = $queryresult['Anzahl'];


	$stmt = $platinendb_connection->prepare(
		"select ausstehend from platinenviewest where ID = ?"
	);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$queryresult = $stmt->get_result();
	$queryresult = mysqli_fetch_array($queryresult);
	$ausstehend = $queryresult['ausstehend'];

	if ($anzahl == $ausstehend) {
		return false;
	} else {
		return true;
	}
}


function ueberfuehren($id, $Anzahl, $Bearbeiter, $finanz, $Material_ID, $Endkupfer, $Staerke, $Lagen, $platinendb_connection)
{
	//Neuen Nutzen anlegen

	$nr = "select max(Nr)+1 as Nr from nutzen";
	$nr = mysqli_query($platinendb_connection, $nr);
	$nr = mysqli_fetch_array($nr);
	$nr = $nr['Nr'];
	if ($nr == null) {
		$nr = 1;
	}



	$stmt = $platinendb_connection->prepare(
		"select user_id from login.users where user_name = ?"
	);
	$stmt->bind_param("i", $Bearbeiter);
	$stmt->execute();
	$queryresult = $stmt->get_result();
	$queryresult = mysqli_fetch_array($queryresult);
	$bearbeiterId = $queryresult['user_id'];


	//Datum
	$Erstellt = date('Y-m-d H:i:s', time());

	//Nutzen anlegen
	$stmt = $platinendb_connection->prepare(
		"INSERT INTO nutzen (Nr, Bearbeiter_ID, Finanzstelle_ID, Material_ID, Endkupfer, Staerke, Lagen, Groesse, Datum, intoderext, Status1, Testdaten, Datum1, Kommentar) VALUES (?, ?, ?, ?, ?, ?, ?, 'individuell', ?, 'ext', 'Fertigung', '0', ?, '')"
	);
	$stmt->bind_param("iiiississ", $nr, $bearbeiterId, $finanz, $Material_ID, $Endkupfer, $Staerke, $Lagen, $Erstellt, $Erstellt);
	$stmt->execute();


	//Platine auf Nutzen packen

	//Id von nutzen
	$stmt = $platinendb_connection->prepare(
		"select ID from nutzen where Nr = ?"
	);
	$stmt->bind_param("i", $nr);
	$stmt->execute();
	$queryresult = $stmt->get_result();
	$queryresult = mysqli_fetch_array($queryresult);
	$nutzenId = $queryresult['ID'];


	$stmt = $platinendb_connection->prepare(
		"INSERT INTO nutzenplatinen (Platinen_ID, Nutzen_ID, platinenaufnutzen) VALUES (?,?,?)"
	);
	$stmt->bind_param("iii", $id, $nutzenId, $Anzahl);
	$stmt->execute();
}


function zustandNeu($platinendb_connection, $NutzenID)
{

	$stmt = $platinendb_connection->prepare(
		"SELECT Status1 FROM nutzen WHERE ID=?"
	);
	$stmt->bind_param("i", $NutzenID);
	$stmt->execute();
	$queryresult = $stmt->get_result();
	$queryresult = mysqli_fetch_array($queryresult);
	$getZustand = $queryresult['Status1'];

	if ($getZustand == "neu") {
		return true;
	} else {
		return false;
	}
}

function sendMail($art, $user_name, $user_email, $user_password_hash)
{
	if ($art == "newPlatineNotification") {
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


	if ($art == "validation") {
		$mail->Subject = ACCOUNT_VALIDATE_SUBJECT;
		$mail->AddAddress(ACCOUNT_VALIDATE_TO);

		$accountinfo = "Benutzername: " . $user_name . "\n" . "E-Mail-Adresse: " . $user_email;

		//$link = ACCOUNT_VALIDATE_URL;
		$link = ACCOUNT_VALIDATE_URL . '&user_name=' . urlencode($user_name) . '&user_email=' . urlencode($user_email) . '&user_password=' . urlencode($user_password_hash) . "\n \n \n";

		$mail->Body = ACCOUNT_VALIDATE_CONTENT . '' . $link . '' . $accountinfo;
	} else if ($art == "userNotification") {
		$mail->Subject = NOTIFICATION_SUBJECT;
		$mail->AddAddress($user_email);

		$benutzername = "\n\n Benutzername: " . $user_name;

		$mail->Body = NOTIFICATION_CONTENT . '' . $benutzername;
	} else if ($art == "newPlatineNotification") {
		$mail->Subject = NEWPLATINE_SUBJECT;
		$mail->AddAddress($user_email);

		$benutzername = "\n\n Benutzername: " . $user_name;

		$mail->Body = NEWPLATINE_CONTENT . '' . $benutzername;
	}


	if (!$mail->Send()) {
		if ($art != "newPlatineNotification") {
			//$this->errors[] = MESSAGE_PASSWORD_RESET_MAIL_FAILED . $mail->ErrorInfo;
		}
		return false;
	} else {
		return true;
	}
}


function platineAufNutzen($id, $platinendb_connection)
{
	$aufnu = "select ID from nutzenplatinen where Nutzen_ID = $id";


	$stmt = $platinendb_connection->prepare(
		"select ID from nutzenplatinen where Nutzen_ID = ?"
	);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$queryresult = $stmt->get_result();
	$queryresult = mysqli_fetch_array($queryresult);
	$aufnu = $queryresult['ID'];

	if ($aufnu == null) {
		return false;
	} else {
		return true;
	}
}



//modal für benutzerinformationen
function modal4($currentpage)
{

	echo '	
		<div id="dataModal3" tabindex="-1" style=" padding-right:0!important" class="modal fade">  
		<div class="modal-dialog">
	

			 <div class="modal-content">  
				  <div class="modal-header">   
					   <h4 class="modal-title">Legende</h4>  
				  </div>  
				  <div class="modal-body" id="modalbody3">  
				 	';

	if ($currentpage == "platinenindex") {
		echo '
						<p style="text-align:center;font-size:20px;font-weight:600;">Zeilenfarbe:</p> 
						<p><span style="color:#005ea9">Blau</span> = Platine im Nutzen-Zustand neu/post </p> 
						<p><span style="color:#e89b02">Orange</span> = Platine im Nutzen-Zustand Fertigung </p> 
						<p><span style="color:green">Grün</span> = Platine im Nutzen-Zustand abgeschlossen </p>
						<p style="text-align:center;font-size:20px;font-weight:600;">Warnfarbe:</p> 
						
						<p><i class="fas fa-exclamation-triangle red"></i> = Platine länger als 15 Tage im Zustand Neu</p>
						<p><i class="fas fa-exclamation-triangle orange"></i> = Platine länger als 10 Tage im Zustand Neu</p>
						';
	} else {
		echo '
						<p style="text-align:center;font-size:20px;font-weight:600;">Warnfarbe:</p> 
						<p><i class="fas fa-exclamation-triangle red"></i> = Nutzen länger als 5 Tage im Zustand Fertigung</p>
						';
	}

	echo ' 
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
function modal3()
{
	//data-backdrop="static" data-keyboard="false" damit modal nur mit abbrechen geschlossen wird
	echo '	
		<div id="dataModal2" tabindex="-1" style=" padding-right:0!important" class="modal fade" data-backdrop="static" data-keyboard="false">  
		<div id=modalbearbeiten class="modal-dialog modal-dialog-centered modal-xl">  
			 <div class="modal-content">  
				  <div class="modal-header">   
					   <h4 class="modal-title"></h4>  
				  </div>  
				  <div class="modal-body" id="modalbody2">  
				  </div>  
				  <div class="modal-footer">  
				  <button type="submit" form="edit" class="btn btn-primary" id="button8" name="insert" value="Insert">
				  
				  </button>
				  <button id="button5" type="button" class="btn btn-primary" data-dismiss="modal">
				  	abbrechen &nbsp <i class="fas fa-times error"></i>
				  </button>  
				  </div>  
			 </div>  
		</div>  
	</div> 
	
	';
}


//modal für detail

function modal2()
{

	echo '	
	<div id="dataModal1" tabindex="-1" class="modal fade">  
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

				<div id="platinenhinzufuegen">  

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

function modal1($login_connection)
{




	echo "
<!-- Modal -->
<div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
	  </td>
	  <tr>
		 <th>Lehrstuhl:</th>
		 <td> $_SESSION[lehrstuhl] </td>
	   </tr>
	  <tr>
		<th>E-Mail:</th>
		<td> $_SESSION[user_email] </td>
	  </tr>
	  <tr>
	  	<th>Berechtigung:</th>
	  	<td>
		";
	if (isUserAdmin($login_connection) == true) {
		echo 'Admin';
	} else {
		echo 'Standardbenutzer';
	}
	echo "
	</table>
";

	echo "
	  </div>
	  <div class='modal-footer'>
		<button id='button2' type='button' class='btn btn-primary' data-dismiss='modal'>schließen</button>
	  </div>
	</div>
  </div>
</div>		
";
}
