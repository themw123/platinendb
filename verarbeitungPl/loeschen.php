<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../utils/util.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

$login_connection = $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//$aktion = "bearbeiten";
//sicherheit checks
if (!(isset($_POST['aktion']))) {
	$aktion = "";
} else {
	$aktion = mysqli_real_escape_string($platinendb_connection, $_POST["aktion"]);
}
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if ($bestanden == true && $aktion == "loeschen") {


	$id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);

	$stmt = $platinendb_connection->prepare(
		"SELECT Downloads_ID FROM platinen WHERE ID = ?"
	);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$queryresult = $stmt->get_result();
	$queryresult = mysqli_fetch_array($queryresult);
	$download_id = $queryresult['Downloads_ID'];

	$stmt = $platinendb_connection->prepare(
		"DELETE FROM platinen WHERE id=?"
	);
	$stmt->bind_param("i", $id);
	$stmt->execute();

	deleteDownload(0, $id, $download_id, $platinendb_connection);



	$sicherheit->checkQuery($platinendb_connection);

	mysqli_close($platinendb_connection);

	mysqli_close($login_connection);
} else {
	header('Content-Type: application/json');
	echo json_encode(array('data' => "fehlerhaft"));
}
