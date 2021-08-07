<?php
require_once("../config/db2.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();
$link = OpenCon();
$link2 = OpenCon2();


//sicherheit checks
$aktion = "platinen";
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $link, $link2);
$bestanden = $sicherheit->ergebnis();


if($bestanden == true) {

	
			//für where anweisung in abfrage
			$auftraggeber1 = mysqli_real_escape_string($link, $_SESSION['user_name']);



			/*
			Abfrage von platinen Tabelle
			*/
			if (isUserEst($link) == true) {
			$sql = "SELECT ID, Name as Leiterkartenname, Auftraggeber, ausstehend, Anzahl, Material, Endkupfer, Staerke as Stärke, Lagen, Groesse as Größe, Oberflaeche as Oberfläche, Loetstopp as Lötstopp, erstelltam as erstellt, wunschDatum as Wunschdatum, Kommentar FROM platinenview order by erstelltam desc";
			}

			else {
			$sql = "SELECT ID, Name as Leiterkartenname, Auftraggeber, ausstehend, Anzahl, Material, Endkupfer, Staerke as Stärke, Lagen, Groesse as Größe, Oberflaeche as Oberfläche, Loetstopp as Lötstopp, erstelltam as erstellt, wunschDatum as Wunschdatum, Kommentar FROM platinenview WHERE platinenview.Auftraggeber = '$auftraggeber1' order by erstelltam desc";
			}

			$result = $link->query($sql);

			if (mysqli_error($link))
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
					if ($row['Wunschdatum'] == "0000-00-00 00:00:00" ) {
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