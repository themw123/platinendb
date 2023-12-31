<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../utils/util.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

$login_connection = $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();

//sicherheit checks
$aktion = "platinen";
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if ($bestanden == true && $aktion == "platinen") {


	//für where anweisung in abfrage
	$auftraggeber1 = mysqli_real_escape_string($platinendb_connection, $_SESSION['user_name']);



	/*
			Abfrage von platinen Tabelle
			*/


	if (isUserAdmin($platinendb_connection) == true) {
		$sql = "SELECT ID, Name as Leiterkartenname, Auftraggeber, Finanzstelle_name, Finanzstelle_nummer, Lehrstuhl, ausstehend, Anzahl, Material, Endkupfer, Staerke as Stärke, Lagen, Groesse as Größe, Oberflaeche as Oberfläche, Loetstopp as Lötstopp, erstelltam as erstellt, wunschDatum as Wunschdatum, Kommentar, Status, ignorieren, abgeschlossenPost, abgeschlossenFertigung, downloads1or0, Bestueckungsdruck FROM platinenviewest";
		$result = $platinendb_connection->query($sql);
	} else {
		$stmt = $platinendb_connection->prepare(
			"SELECT ID, Name as Leiterkartenname, Auftraggeber, Finanzstelle_name, Finanzstelle_nummer, Lehrstuhl, ausstehend, Anzahl, Material, Endkupfer, Staerke as Stärke, Lagen, Groesse as Größe, Oberflaeche as Oberfläche, Loetstopp as Lötstopp, erstelltam as erstellt, wunschDatum as Wunschdatum, Kommentar, Status, ignorieren, abgeschlossenPost, abgeschlossenFertigung, downloads1or0, Bestueckungsdruck FROM platinenviewest WHERE platinenviewest.Auftraggeber = ? order by erstelltam desc"
		);
		$stmt->bind_param("s", $auftraggeber1);
		$stmt->execute();
		$result = $stmt->get_result();
	}


	$sicherheit->checkQuery3($platinendb_connection);




	if ($result->num_rows > 0) {

		while ($row = $result->fetch_array()) {
			/*
					datum richtig formatieren
					*/
			$creation_time = date('d.m.Y', strtotime($row['erstellt']));

			if (isset($row['Wunschdatum'])) {
				$creation_time2 = date('d.m.Y', strtotime($row['Wunschdatum']));
			}
			if ($row['Wunschdatum'] == null) {
				$creation_time2 = "";
			}

			/*
					datum richtig formatieren bis hier
					*/


			/*
					finanzstelle formatieren
					*/

			$finanzstelle_name = $row['Finanzstelle_name'];
			$finanzstelle_nummer = $row['Finanzstelle_nummer'];
			$finanzstelle_nummer = substr($finanzstelle_nummer, -4);
			$finanzstelle = $finanzstelle_name . '_' . $finanzstelle_nummer;


			/*
					clickable rows und bearbeitungszeichen
					*/




			$nestedData = array();



			$nestedData[] = $row["ID"];





			$nestedData[] = $row["Leiterkartenname"];
			$nestedData[] = $row["Auftraggeber"];
			$nestedData[] = $row["Lehrstuhl"];
			$nestedData[] = $finanzstelle;
			$nestedData[] = $row["ausstehend"];
			$nestedData[] = $row["Anzahl"];
			$nestedData[] = $row["Material"];
			$nestedData[] = $row["Endkupfer"];
			$nestedData[] = $row["Stärke"];
			$nestedData[] = $row["Lagen"];
			$nestedData[] = $row["Größe"];
			$nestedData[] = $row["Oberfläche"];
			$nestedData[] = $row["Lötstopp"];
			$nestedData[] = $row["Bestueckungsdruck"];
			$nestedData[] = $creation_time;
			$nestedData[] = $creation_time2;
			$nestedData[] = $row["Kommentar"];

			$nestedData[] = $row["Status"];
			$nestedData[] = $row["ignorieren"];
			$nestedData[] = $row["abgeschlossenPost"];
			$nestedData[] = $row["abgeschlossenFertigung"];
			$nestedData[] = $row["downloads1or0"];

			$data[] = $nestedData;
		}


		$json_data = array("data" => $data);


		echo json_encode($json_data);  // send data as json format
	} else {
		$datax[1] = "leer";
		header('Content-Type: application/json');
		echo json_encode(array('data' => $datax));
		die();
	}

	mysqli_close($platinendb_connection);
	mysqli_close($login_connection);
} else {
	$datax[1] = "fehlerhaft";
	header('Content-Type: application/json');
	echo json_encode(array('data' => $datax));
	die();
}
