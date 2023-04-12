<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");


$login = new Login();

$login_connection = $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//$aktion = "einfuegen";
//sicherheit checks
if (!(isset($_POST['aktion']))) {
  $aktion = "";
} else {
  $aktion = mysqli_real_escape_string($platinendb_connection, $_POST["aktion"]);
}
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();



if ($bestanden == true  && $aktion == "download") {

  $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);

  $stmt = $platinendb_connection->prepare(
    "SELECT Downloads_ID FROM platinen WHERE ID =?"
  );
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $queryresult = $stmt->get_result();
  $queryresult = mysqli_fetch_assoc($queryresult);
  $download_id = $queryresult['Downloads_ID'];



  if ($download_id != null) {

    $stmt = $platinendb_connection->prepare(
      "SELECT download,name,size,type FROM downloads WHERE id = ?"
    );
    $stmt->bind_param("i", $download_id);
    $stmt->execute();
    $queryresult = $stmt->get_result();
    $queryresult = mysqli_fetch_array($queryresult);

    $download = $queryresult['download'];
    $name = $queryresult['name'];
    $size = $queryresult['size'];
    $type = $queryresult['type'];


    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-length: $size");
    header("Content-type: $type");
    header("Content-Disposition: attachment; filename=$name");
    ob_clean();
    flush();
    echo $download;
  }
} else {
  header('Content-Type: application/json');
  echo json_encode(array('data' => "fehlerhaft"));
}
