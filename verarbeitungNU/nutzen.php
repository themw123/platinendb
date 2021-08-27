<?php
require_once("../config/db2.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();
$link = OpenCon();
$link2 = OpenCon2();


//sicherheit checks
$aktion = "nutzen";
$von = "nutzen";
$sicherheit = new Sicherheit($aktion, $von, $login, $link, $link2);
$bestanden = $sicherheit->ergebnis();


if($bestanden == true) {

		/*
		Abfrage von nutzen Tabelle
		*/
		$sql = "SELECT * FROM nutzenview order by Nr desc";
	
		

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
				$creation_time1 = date('d-m-Y', strtotime($row['erstellt']));
				
				if (isset($row['Fertigung'])) {
				$creation_time2 = date('d-m-Y', strtotime($row['Fertigung']));
				}
				if ($row['Fertigung'] == "0000-00-00 00:00:00" ) {
				$creation_time2 = "";
				}

				if (isset($row['abgeschlossen'])) {
				$creation_time3 = date('d-m-Y', strtotime($row['abgeschlossen']));
				}
				if ($row['abgeschlossen'] == "0000-00-00 00:00:00" ) {
				$creation_time3 = "";
				}

				/*
				datum richtig formatieren bis hier
				*/



				/*
				clickable rows und bearbeitungszeichen
				*/

				
			
				
				$nestedData=array();


				
				$nestedData[] = $row["ID"];


				
				

				$nestedData[] = $row["Nr"];
				$nestedData[] = $row["Bearbeiter"];
				$nestedData[] = $row["Status"];
				$nestedData[] = $creation_time1;
				$nestedData[] = $creation_time2;
				$nestedData[] = $creation_time3;
				$nestedData[] = $row["Material"];
				$nestedData[] = $row["Endkupfer"];
				$nestedData[] = $row["Staerke"];
				$nestedData[] = $row["Lagen"];
				$nestedData[] = $row["Groesse"];
				$nestedData[] = $row["intoderext"];
				$nestedData[] = $row["Testdaten"];
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