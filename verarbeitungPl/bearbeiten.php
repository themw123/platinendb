<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
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



if ($bestanden == true && $aktion == "bearbeiten") {



  $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);


  /*
          Inputs auslesen Name
          */

  $Name = mysqli_real_escape_string($platinendb_connection, $_POST["Name"]);


  if (isUserAdmin($platinendb_connection) == true) {
    /*
            Inputs auslesen Auftraggeber
            */
    $auftraggeber = mysqli_real_escape_string($platinendb_connection, $_POST["Auftraggeber"]);

    $stmt = $login_connection->prepare(
      "SELECT user_id FROM users WHERE user_name=?"
    );
    $stmt->bind_param("s", $auftraggeber);
    $stmt->execute();
    $queryresult = $stmt->get_result();
    $queryresult = mysqli_fetch_assoc($queryresult);
    $user_id = $queryresult['user_id'];
  }



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
  $Groeße = mysqli_real_escape_string($platinendb_connection, $_POST["Groeße"]);


  /*
          Inputs auslesen Oberfläche
          */
  $Oberflaeche = mysqli_real_escape_string($platinendb_connection, $_POST["Oberflaeche"]);


  /*
          Inputs auslesen Loetstopp
          */
  $Loetstopp = mysqli_real_escape_string($platinendb_connection, $_POST["Loetstopp"]);


  if (isset($Bestueckungsdruck)) {
    $Bestueckungsdruck = mysqli_real_escape_string($platinendb_connection, $_POST["Bestueckungsdruck"]);
  }

  /*
          Inputs auslesen wunschDatum
          */

  if (empty($_POST["Wunschdatum"])) {
    $Wunschdatum = null;
  } else {
    $datumzumformatieren = strtotime(mysqli_real_escape_string($platinendb_connection, $_POST["Wunschdatum"]));
    $Wunschdatum .= date('Y.m.d', $datumzumformatieren);
  }




  /*
          Inputs auslesen Kommentar
          */
  $Kommentar = mysqli_real_escape_string($platinendb_connection, $_POST["Kommentar"]);


  /*
          Input auslesen Bestückungsdruck
          */
  if (isset($_POST['Bestueckungsdruck'])) {
    $Bestueckungsdruck = 1;
  } else {
    $Bestueckungsdruck = 0;
  }

  /*
          Input auslesen Testdaten
          */
  if (isset($_POST['Ignorieren'])) {
    $Ignorieren = 1;
  } else {
    $Ignorieren = 0;
  }

  /*
          Input auslesen Fertigung
          */
  if (isset($_POST['Fertigung'])) {
    $Fertigung = 1;
  } else {
    $Fertigung = 0;
  }

  if (isset($_POST['Bearbeiter'])) {
    $Bearbeiter = mysqli_real_escape_string($platinendb_connection, $_POST["Bearbeiter"]);
  }









  //bearbeitung für download durchführen
  if (!empty($_FILES)) {

    deleteDownload(2, $id, null, $platinendb_connection);

    $maxid = uploadFile($platinendb_connection);

    $stmt = $platinendb_connection->prepare(
      "UPDATE platinen SET Downloads_ID = ? WHERE ID = ?"
    );
    $stmt->bind_param("ii", $maxid, $id);
    $stmt->execute();
  }





  //bearbeitung durchführen
  if (isUserAdmin($platinendb_connection)) {
    $user = mysqli_real_escape_string($login_connection, $_SESSION['user_name']);

    $stmt = $platinendb_connection->prepare(
      "UPDATE platinen SET Name = ?,Anzahl = ?, Auftraggeber_ID = ?, Finanzstelle_ID = ?, Material_ID = ?,Endkupfer = ?,Staerke = ?,Lagen = ?,Groesse = ?,Oberflaeche = ?,Loetstopp = ?, Bestueckungsdruck = ?, wunschDatum = ?,Kommentar = ?, ignorieren = ? WHERE ID = ?"
    );
    $stmt->bind_param("siiiississsissii", $Name, $Anzahl, $user_id, $finanz, $material_id, $Endkupfer, $Staerke, $Lagen, $Groeße, $Oberflaeche, $Loetstopp, $Bestueckungsdruck, $Wunschdatum, $Kommentar, $Ignorieren, $id);



    if ($Fertigung == 1 && !isInFertigung($id, $platinendb_connection) && !isOnNutzen($id, $platinendb_connection)) {
      //In Fertigung überführen. Erst Nutzen erstellen und Platine da drauf packen und diesen in Fertigung versetzten.
      ueberfuehren($id, $Anzahl, $user, $finanz, $material_id, $Endkupfer, $Staerke, $Lagen, $platinendb_connection);
    }
  } else {
    $stmt = $platinendb_connection->prepare(
      "UPDATE platinen SET Name = ?,Anzahl = ?, Finanzstelle_ID = ?, Material_ID = ?,Endkupfer = ?,Staerke = ?,Lagen = ?,Groesse = ?,Oberflaeche = ?,Loetstopp = ?, Bestueckungsdruck = ?, wunschDatum = ?,Kommentar = ? WHERE ID = ?"
    );
    $stmt->bind_param("siiississsissi", $Name, $Anzahl, $finanz, $material_id, $Endkupfer, $Staerke, $Lagen, $Groeße, $Oberflaeche, $Loetstopp, $Bestueckungsdruck, $Wunschdatum, $Kommentar, $id);
  }


  $stmt->execute();



  $sicherheit->checkQuery($platinendb_connection);

  mysqli_close($platinendb_connection);
  mysqli_close($login_connection);
} else {
  header('Content-Type: application/json');
  echo json_encode(array('data' => "fehlerhaft"));
}
