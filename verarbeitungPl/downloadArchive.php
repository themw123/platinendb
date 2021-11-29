<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

$login_connection= $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//$aktion = "einfuegen";
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


    $size = 422444;
    $type = "application/x-zip-compressed";
    $name = "CAM_Spielfeld.zip";

    $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);
    $query = "SELECT ID, archive FROM platinen WHERE ID = '$id'";

    $result = mysqli_query($platinendb_connection,$query);
    $row = mysqli_fetch_array($result);
    $archive = $row['archive'];

    header("Content-length: $size");
    header("Content-type: $type");
    header("Content-Type: application/force-download"); 
    header("Content-Disposition: attachment; filename=$name");
    header("Content-Type: application/octet-stream;");

    echo $archive; 
}


else {
  header('Content-Type: application/json');
  echo json_encode(array('data'=> "fehlerhaft"));
  die();
}


?>