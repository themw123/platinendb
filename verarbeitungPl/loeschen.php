<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

$login_connection= $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//$aktion = "bearbeiten";
//sicherheit checks
if(!(isset($_POST['aktion']))) {
	$aktion = "";
}
else {
	$aktion = mysqli_real_escape_string($platinendb_connection, $_POST["aktion"]);
}
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if($bestanden == true) {


		$id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);

		$download_id = "SELECT Downloads_ID FROM platinen WHERE ID = '$id'";
		$download_id = mysqli_query($platinendb_connection,$download_id);
		$download_id = mysqli_fetch_array($download_id);
		$download_id = $download_id['Downloads_ID']; 


		$loeschen1 = "DELETE FROM platinen WHERE id=$id";

		mysqli_query($platinendb_connection, $loeschen1);

		if($download_id != null) {
			$loeschen2 = "DELETE FROM downloads WHERE id=$download_id";
			mysqli_query($platinendb_connection, $loeschen2);
		}

		$sicherheit->checkQuery($platinendb_connection); 

		mysqli_close($platinendb_connection); 
		
		mysqli_close($login_connection); 

		

}

else {
	echo json_encode(array('data'=> "fehlerhaft"));
}


?>