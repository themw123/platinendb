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

  $PlatinenID = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);
  $NutzenID = mysqli_real_escape_string($platinendb_connection, $_POST['NutzenId']);
  $Anzahl = mysqli_real_escape_string($platinendb_connection, $_POST['anzahl']);

  if ($Anzahl > 0) {
    //nur Platine hinzufügen wenn Nutzen im Zustand neu ist. Ansonnsten abbruch ab hier.
    if (zustandNeu($platinendb_connection, $NutzenID) == false) {
      mysqli_close($platinendb_connection);
      mysqli_close($login_connection);
      header('Content-Type: application/json');
      echo json_encode(array('data' => 'nichterlaubt'));
      die();
    }

    $stmt = $platinendb_connection->prepare(
      "INSERT INTO nutzenplatinen (Platinen_ID, Nutzen_ID, platinenaufnutzen) VALUES (?,?,?)"
    );
    $stmt->bind_param("iii", $PlatinenID, $NutzenID, $Anzahl);
    $stmt->execute();

    //nicht nötig, man kann keine platine hinzufügen wenn nutzten zustand != neu. und wenn nutzten zustand neu ist, dann sollen keine downdloads gelöscht werden
    //deleteDownload("IFfertigungabgeschlossen", $PlatinenID, $platinendb_connection);

    $sicherheit->checkQuery($platinendb_connection);

    mysqli_close($platinendb_connection);

    mysqli_close($login_connection);
  } else {
    header('Content-Type: application/json');
    echo json_encode(array('data' => "fehlerhaft"));
  }
}
