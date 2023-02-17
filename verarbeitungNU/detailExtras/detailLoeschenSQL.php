<?php

require_once("/documents/config/db.php");
require_once("../../classes/Login.php");
require_once("../../funktion/alle.php");
require_once("../../classes/Sicherheit.php");

$login = new Login();

$login_connection = $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//sicherheit checks
if (!(isset($_POST['aktion']))) {
  $aktion = "";
} else {
  $aktion = mysqli_real_escape_string($platinendb_connection, $_POST["aktion"]);
}
$von = "nutzen";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if ($bestanden == true && $aktion == "detail") {


  $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);



  $stmt = $platinendb_connection->prepare(
    "SELECT Nutzen_ID FROM nutzenplatinen WHERE ID = ?"
  );
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_array();
  $NutzenID = $row['Nutzen_ID'];


  //nur von Nutzen entfernen wenn Nutzen im Zustand neu ist. Ansonnsten abbruch ab hier.
  if (zustandNeu($platinendb_connection, $NutzenID) == false) {
    mysqli_close($platinendb_connection);
    mysqli_close($login_connection);
    header('Content-Type: application/json');
    echo json_encode(array('data' => 'nichterlaubt'));
    die();
  }


  $stmt = $platinendb_connection->prepare(
    "DELETE FROM nutzenplatinen WHERE id= ?"
  );
  $stmt->bind_param("i", $id);
  $stmt->execute();

  $sicherheit->checkQuery($platinendb_connection);

  mysqli_close($platinendb_connection);

  mysqli_close($login_connection);
}
