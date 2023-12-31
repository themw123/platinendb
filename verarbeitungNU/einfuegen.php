<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../utils/util.php");
require_once("../classes/Sicherheit.php");

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


if ($bestanden == true && $aktion == "einfuegen") {


  /*
            Inputs auslesen Name
            */
  $Nr = mysqli_real_escape_string($platinendb_connection, $_POST["Nr"]);


  /*
            Bearbeiter
            */
  $bearbeiter = mysqli_real_escape_string($platinendb_connection, $_POST["Bearbeiter"]);
  $stmt = $login_connection->prepare(
    "SELECT user_id FROM users WHERE user_name=?"
  );
  $stmt->bind_param("s", $bearbeiter);
  $stmt->execute();
  $result = $stmt->get_result();
  $Bearbeiterx = mysqli_fetch_assoc($result);
  $Bearbeiter = $Bearbeiterx['user_id'];



  /*
            Inputs auslesen Status
            */
  $Status = mysqli_real_escape_string($platinendb_connection, $_POST["Status"]);


  /*
            Inputs auslesen material
            */
  $material2 = mysqli_real_escape_string($platinendb_connection, $_POST["Material"]);
  $stmt = $platinendb_connection->prepare(
    "SELECT ID FROM material WHERE Name=?"
  );
  $stmt->bind_param("i", $material2);
  $stmt->execute();
  $result = $stmt->get_result();
  $Materialx = mysqli_fetch_assoc($result);
  $Material =  $Materialx['ID'];



  /*
            Inputs auslesen Endkupfer
            */
  $Endkupfer = mysqli_real_escape_string($platinendb_connection, $_POST["Endkupfer"]);


  /*
            Inputs auslesen Staerke
            */
  $Staerke = mysqli_real_escape_string($platinendb_connection, $_POST["Staerke"]);



  /*
            Inputs auslesen Lagen
            */
  $Lagen = mysqli_real_escape_string($platinendb_connection, $_POST["Lagen"]);



  /*
            Inputs auslesen erstellt
            */

  /*
            if (empty($_POST["Erstellt"])) {
              $Erstellt = NULL;
            }
            else {
              $datumzumformatieren = strtotime(mysqli_real_escape_string($link, $_POST["Erstellt"]));
              $Erstellt = date('Y-m-d H:i:s', $datumzumformatieren);
            }
            */

  date_default_timezone_set('Europe/Berlin');
  $Erstellt = date('Y-m-d H:i:s', time());


  /*
            Inputs auslesen Größe
            */
  $Groesse = mysqli_real_escape_string($platinendb_connection, $_POST["Groesse"]);


  /*
            Inputs auslesen int/ext
            */
  $Int = mysqli_real_escape_string($platinendb_connection, $_POST["Int"]);


  /*
            Testdaten auslesen
            */

  if (isset($_POST['Testdaten'])) {
    $Testdaten = 1;
  } else {
    $Testdaten = 0;
  }



  /*
            Inputs auslesen Kommentar
            */
  $Kommentar = mysqli_real_escape_string($platinendb_connection, $_POST["Kommentar"]);
  $Kommentar = str_replace("\\\\", "\\", $Kommentar);


  /*
            Ergebnis(inserten)
            */

  $stmt = $platinendb_connection->prepare(
    "INSERT INTO nutzen (Nr, Bearbeiter_ID, Material_ID, Endkupfer, Staerke, Lagen, Groesse, Datum, intoderext, Status1, Testdaten, Kommentar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
  );
  $stmt->bind_param("iiississssis", $Nr, $Bearbeiter, $Material, $Endkupfer, $Staerke, $Lagen, $Groesse, $Erstellt, $Int, $Status, $Testdaten, $Kommentar);
  $stmt->execute();
  $result = $stmt->get_result();



  $sicherheit->checkQuery($platinendb_connection);


  mysqli_close($platinendb_connection);

  mysqli_close($login_connection);
} else {
  header('Content-Type: application/json');
  echo json_encode(array('data' => "fehlerhaft"));
}
