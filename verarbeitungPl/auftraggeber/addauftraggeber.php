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
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if ($bestanden == true  && $aktion == "auftraggeber") {

  $auftraggeber = mysqli_real_escape_string($platinendb_connection, $_POST['auftr']);
  $lehrstuhl = mysqli_real_escape_string($platinendb_connection, $_POST['lehr']);

  $stmt = $platinendb_connection->prepare(
    "SELECT
        id
      FROM 
        lehrstuhl
      WHERE 
        kuerzel = ?"
  );
  $stmt->bind_param("i", $lehrstuhl);
  $stmt->execute();
  $queryresult = $stmt->get_result();
  $queryresult = mysqli_fetch_assoc($queryresult);
  $lehrstuhl = $queryresult["id"];


  //wenn platine im zustand abgeschlossenPost = 1 ist, dann lösche Download_ID und den download
  $stmt = $login_connection->prepare(
    "INSERT INTO users(user_name, admin, lehrstuhl) VALUE(?, '0', ?)"
  );
  $stmt->bind_param("si", $auftraggeber, $lehrstuhl);
  $stmt->execute();

  $sicherheit->checkQuery($login_connection);
  mysqli_close($platinendb_connection);
  mysqli_close($login_connection);
}
