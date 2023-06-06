<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../utils/util.php");
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



if ($bestanden == true && $aktion == "einfuegen") {


  /*
          Inputs auslesen Name
          */
  $Name = mysqli_real_escape_string($platinendb_connection, $_POST["Name"]);




  /*
          Inputs auslesen Auftraggeber
          */
  if (isUserAdmin($platinendb_connection) == true) {
    $Auftraggeber2 = mysqli_real_escape_string($login_connection, $_POST["Auftraggeber"]);
  } else {
    $Auftraggeber2 = mysqli_real_escape_string($platinendb_connection, $_SESSION['user_name']);
  }

  $stmt = $login_connection->prepare(
    "SELECT user_id FROM users WHERE user_name=?"
  );
  $stmt->bind_param("s", $Auftraggeber2);
  $stmt->execute();
  $queryresult = $stmt->get_result();
  $queryresult = mysqli_fetch_assoc($queryresult);
  $auftraggeber_id = $queryresult['user_id'];

  /*
          Inputs auslesen Finanzstelle
          */
  $finanz = mysqli_real_escape_string($platinendb_connection, $_POST["Finanz"]);


  /*
          Inputs auslesen Anzahl
          */
  $Anzahl = mysqli_real_escape_string($platinendb_connection, $_POST["Anzahl"]);


  /*
          Inputs auslesen material
          */
  $material2 = mysqli_real_escape_string($platinendb_connection, $_POST["Material"]);

  $stmt = $platinendb_connection->prepare(
    "SELECT ID FROM material WHERE Name=?"
  );
  $stmt->bind_param("s", $material2);
  $stmt->execute();
  $queryresult = $stmt->get_result();
  $queryresult = mysqli_fetch_assoc($queryresult);
  $material_id = $queryresult['ID'];




  /*
          Inputs auslesen Endkupfer
          */
  $Endkupfer = mysqli_real_escape_string($platinendb_connection, $_POST["Endkupfer"]);



  /*
          Inputs auslesen Stärke
          */
  $Staerke = mysqli_real_escape_string($platinendb_connection, $_POST["Staerke"]);



  /*
          Inputs auslesen Lagen
          */
  $Lagen = mysqli_real_escape_string($platinendb_connection, $_POST["Lagen"]);


  /*
          Inputs auslesen Größe
          */
  $Groeße = mysqli_real_escape_string($platinendb_connection, $_POST["Groesse"]);



  /*
          Inputs auslesen Oberfläche
          */
  $Oberflaeche = mysqli_real_escape_string($platinendb_connection, $_POST["Oberflaeche"]);


  /*
          Inputs auslesen Loetstopp
          */
  $Loetstopp = mysqli_real_escape_string($platinendb_connection, $_POST["Loetstopp"]);


  if (isset($_POST['Bestueckungsdruck'])) {
    $Bestueckungsdruck = 1;
  } else {
    $Bestueckungsdruck = 0;
  }


  /*
          automatisch datum einfügen
          */
  $erstelltam = date('Y-m-d  H:i:s ', time());



  /*
          Inputs auslesen wunschDatum
          */

  if (empty($_POST["Wunschdatum"])) {
    $Wunschdatum = null;
  } else {
    $datumzumformatieren = strtotime(mysqli_real_escape_string($platinendb_connection, $_POST["Wunschdatum"]));
    $Wunschdatum = date('Y-m-d', $datumzumformatieren);
  }




  /*
          Inputs auslesen Kommentar
          */
  $Kommentar = mysqli_real_escape_string($platinendb_connection, $_POST["Kommentar"]);





  /*
          Ergebnis>( inserten )
          */

  if (!empty($_FILES)) {

    $maxid = uploadFile($platinendb_connection);

    $stmt = $platinendb_connection->prepare(
      "INSERT INTO platinen (Name, Auftraggeber_ID, Finanzstelle_ID, Anzahl, Material_ID, Endkupfer, Staerke, Lagen, Groesse, Oberflaeche, Loetstopp, Bestueckungsdruck, erstelltam, wunschDatum, Kommentar, Downloads_ID, ignorieren) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '0')"
    );
    $stmt->bind_param("siiiississsisssi", $Name, $auftraggeber_id, $finanz, $Anzahl, $material_id, $Endkupfer, $Staerke, $Lagen, $Groeße, $Oberflaeche, $Loetstopp, $Bestueckungsdruck, $erstelltam, $Wunschdatum, $Kommentar, $maxid);
  } else {
    $stmt = $platinendb_connection->prepare(
      "INSERT INTO platinen (Name, Auftraggeber_ID, Finanzstelle_ID, Anzahl, Material_ID, Endkupfer, Staerke, Lagen, Groesse, Oberflaeche, Loetstopp, Bestueckungsdruck, erstelltam, wunschDatum, Kommentar, Downloads_ID, ignorieren) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, null, '0')"
    );
    $stmt->bind_param("siiiississsisss", $Name, $auftraggeber_id, $finanz, $Anzahl, $material_id, $Endkupfer, $Staerke, $Lagen, $Groeße, $Oberflaeche, $Loetstopp, $Bestueckungsdruck, $erstelltam, $Wunschdatum, $Kommentar);
  }


  $stmt->execute();
  $sicherheit->checkQuery($platinendb_connection);

  if (!isUserAdmin($platinendb_connection) && !mysqli_error($platinendb_connection)) {
    $art = "newPlatineNotification";
    $user_name = mysqli_real_escape_string($login_connection, $_SESSION['user_name']);
    $user_email = ACCOUNT_VALIDATE_TO;
    sendMail($art, $user_name, $user_email, "", "");
  }

  mysqli_close($platinendb_connection);

  mysqli_close($login_connection);
} else {
  header('Content-Type: application/json');
  echo json_encode(array('data' => "fehlerhaft"));
}
