<?php
require_once("/documents/config/db.php");
require_once("../../classes/Login.php");
require_once("../../utils/util.php");
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


if ($bestanden == true && $aktion == "finanz") {

  $fname = mysqli_real_escape_string($platinendb_connection, $_POST['addFinanz1']);
  $fnummer = mysqli_real_escape_string($platinendb_connection, $_POST['addFinanz2']);

  if (!is_numeric($fnummer)) {
    return;
  }

  if (strlen($fnummer) != 10) {
    return;
  }



  $stmt = $platinendb_connection->prepare(
    "INSERT INTO finanzstelle(name, nummer) VALUE(?, ?)"
  );
  $stmt->bind_param("ss", $fname, $fnummer);
  $stmt->execute();





  $sicherheit->checkQuery($platinendb_connection);
  mysqli_close($platinendb_connection);
  mysqli_close($login_connection);
}
