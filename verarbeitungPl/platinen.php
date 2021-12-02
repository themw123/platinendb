<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

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
			$sql = "SELECT ID, Name as Leiterkartenname, Auftraggeber, ausstehend, Anzahl, Material, Endkupfer, Staerke as Stärke, Lagen, Groesse as Größe, Oberflaeche as Oberfläche, Loetstopp as Lötstopp, erstelltam as erstellt, wunschDatum as Wunschdatum, Kommentar, Status, ignorieren, abgeschlossenPost, 10Tage, 14Tage, dringlichkeitPost, abgeschlossenFertigung, archive1or0 FROM platinenviewest";
			}

			else {
			$sql = "SELECT ID, Name as Leiterkartenname, Auftraggeber, ausstehend, Anzahl, Material, Endkupfer, Staerke as Stärke, Lagen, Groesse as Größe, Oberflaeche as Oberfläche, Loetstopp as Lötstopp, erstelltam as erstellt, wunschDatum as Wunschdatum, Kommentar FROM platinenview WHERE platinenview.Auftraggeber = '$auftraggeber1' order by erstelltam desc";
			}


			$result = $platinendb_connection->query($sql);
			$sicherheit->checkQuery3($platinendb_connection);
  



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
						$nestedData[] = $row["abgeschlossenPost"];
						$nestedData[] = $row["10Tage"];
						$nestedData[] = $row["14Tage"];
						$nestedData[] = $row["dringlichkeitPost"];
						$nestedData[] = $row["abgeschlossenFertigung"];
						$nestedData[] = $row["archive1or0"];
					}
					else {
						$nestedData[] = "";
						$nestedData[] = "";
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

			mysqli_close($platinendb_connection); 
			mysqli_close($login_connection);  

}
else {
	$datax[1] = "fehlerhaft";
	header('Content-Type: application/json');
	echo json_encode(array('data'=> $datax));
	die();
}



		
	
	
?>