<?php

require_once("../config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

//Verbindung zur Platinendb Datenbank aufbauen
$login->mysqlplatinendb();

//Verbindung zur login Datenbank aufbauen
$login->mysqllogin();

$login_connection= $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//sicherheit checks
$aktion = "platinen";
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if($bestanden == true) {

			
			//für where anweisung in abfrage
			$auftraggeber1 = mysqli_real_escape_string($platinendb_connection, $_SESSION['user_name']);



			/*
			Abfrage von platinen Tabelle
			*/

			
			if (isUserEst($platinendb_connection) == true) {
			$sql = "SELECT ID, Name as Leiterkartenname, Auftraggeber, ausstehend, Anzahl, Material, Endkupfer, Staerke as Stärke, Lagen, Groesse as Größe, Oberflaeche as Oberfläche, Loetstopp as Lötstopp, erstelltam as erstellt, wunschDatum as Wunschdatum, Kommentar, Status, ignorieren, abgeschlossen, 10Tage, 14Tage, dringlichkeit FROM platinenviewest";
			}

			else {
			$sql = "SELECT ID, Name as Leiterkartenname, Auftraggeber, ausstehend, Anzahl, Material, Endkupfer, Staerke as Stärke, Lagen, Groesse as Größe, Oberflaeche as Oberfläche, Loetstopp as Lötstopp, erstelltam as erstellt, wunschDatum as Wunschdatum, Kommentar FROM platinenview WHERE platinenview.Auftraggeber = '$auftraggeber1' order by erstelltam desc";
			}

			$result = $platinendb_connection->query($sql);

			if (mysqli_error($platinendb_connection))
			{
				$datax[1] = "dberror";
				header('Content-Type: application/json');
				echo json_encode(array('data'=> $datax));
				die();
			}

			if ($result->num_rows > 0) {
					
					while($row = $result->fetch_array()) {
					/*
					datum richtig formatieren
					*/
					$creation_time = date('d-m-Y', strtotime($row['erstellt']));
					
					if (isset($row['Wunschdatum'])) {
					$creation_time2 = date('d-m-Y', strtotime($row['Wunschdatum']));
					}
					if ($row['Wunschdatum'] == null ) {
					$creation_time2 = "";
					}

					/*
					datum richtig formatieren bis hier
					*/



					/*
					clickable rows und bearbeitungszeichen
					*/

					
				
					
					$nestedData=array();


					
					$nestedData[] = $row["ID"];


					
					

					$nestedData[] = $row["Leiterkartenname"];
					$nestedData[] = $row["Auftraggeber"];
					$nestedData[] = $row["ausstehend"];
					$nestedData[] = $row["Anzahl"];
					$nestedData[] = $row["Material"];
					$nestedData[] = $row["Endkupfer"];
					$nestedData[] = $row["Stärke"];
					$nestedData[] = $row["Lagen"];
					$nestedData[] = $row["Größe"];
					$nestedData[] = $row["Oberfläche"];
					$nestedData[] = $row["Lötstopp"];
					$nestedData[] = $creation_time;
					$nestedData[] = $creation_time2;
					$nestedData[] = $row["Kommentar"];
					if (isUserEst($platinendb_connection) == true) {
						$nestedData[] = $row["Status"];
						$nestedData[] = $row["ignorieren"];
						$nestedData[] = $row["abgeschlossen"];
						$nestedData[] = $row["10Tage"];
						$nestedData[] = $row["14Tage"];
						$nestedData[] = $row["dringlichkeit"];
					}
					else {
						$nestedData[] = "";
						$nestedData[] = "";
						$nestedData[] = "";
						$nestedData[] = "";
						$nestedData[] = "";
						$nestedData[] = "";
					}
					
					
					
					$data[] = $nestedData;

				}

				
				$json_data = array("data" => $data);


				echo json_encode($json_data);  // send data as json format
			}
			else {
				$datax[1] = "leer";
				header('Content-Type: application/json');
				echo json_encode(array('data'=> $datax));
				die();
			}

}
else {
	$datax[1] = "fehlerhaft";
	header('Content-Type: application/json');
	echo json_encode(array('data'=> $datax));
	die();
}



		
	
	
?>