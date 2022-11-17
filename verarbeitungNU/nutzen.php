<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

$login_connection= $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//sicherheit checks
$aktion = "nutzen";
$von = "nutzen";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if($bestanden == true && $aktion == "nutzen") {

		/*
		Abfrage von nutzen Tabelle
		*/
		$sql = "SELECT * FROM nutzenview";
	
		

		$result = $platinendb_connection->query($sql);
		$sicherheit->checkQuery3($platinendb_connection);

		if ($result->num_rows > 0) {
				
				while($row = $result->fetch_array()) {
				/*
				datum richtig formatieren
				*/
				$creation_time1 = date('d.m.Y', strtotime($row['erstellt']));
				
				if (isset($row['Fertigung'])) {
				$creation_time2 = date('d.m.Y', strtotime($row['Fertigung']));
				}
				if ($row['Fertigung'] == null ) {
				$creation_time2 = "";
				}

				if (isset($row['abgeschlossen'])) {
				$creation_time3 = date('d.m.Y', strtotime($row['abgeschlossen']));
				}
				if ($row['abgeschlossen'] == null ) {
				$creation_time3 = "";
				}

				/*
				datum richtig formatieren bis hier
				*/



				$finanzstelle_name = $row['Finanzstelle_name'];
				$finanzstelle_nummer = $row['Finanzstelle_nummer'];
				if($finanzstelle_name != null) {
					$finanzstelle_nummer = substr($finanzstelle_nummer, -4);
					$finanzstelle = $finanzstelle_name .'_'. $finanzstelle_nummer;
				}
				else {
					$finanzstelle = null;
				}


				/*
				clickable rows und bearbeitungszeichen
				*/

				
			
				
				$nestedData=array();


				
				$nestedData[] = $row["ID"];



				
				

				$nestedData[] = $row["Nr"];
				$nestedData[] = $row["Bearbeiter"];
				$nestedData[] = $finanzstelle;
				$nestedData[] = $row["intoderext"];
				$nestedData[] = $row["Status"];
				$nestedData[] = $creation_time1;
				$nestedData[] = $creation_time2;
				$nestedData[] = $creation_time3;
				$nestedData[] = $row["Material"];
				$nestedData[] = $row["Endkupfer"];
				$nestedData[] = $row["Staerke"];
				$nestedData[] = $row["Lagen"];
				$nestedData[] = $row["Groesse"];
				$nestedData[] = $row["Testdaten"];
				$nestedData[] = $row["Kommentar"];
				$nestedData[] = $row["dringlichkeitFertigung"];
				


				
				
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

			mysqli_close($platinendb_connection); 
			mysqli_close($login_connection); 
}
else {
	$datax[1] = "fehlerhaft";
	header('Content-Type: application/json');
	echo json_encode(array('data'=> $datax));
}
