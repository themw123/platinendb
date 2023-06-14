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


if ($bestanden == true && $aktion == "bearbeiten") {



  $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);



  /*
          Inputs auslesen Nr
          */

  $Nr = mysqli_real_escape_string($platinendb_connection, $_POST["Nr"]);



  /*
          Inputs auslesen Bearbeiter
          */
  $bearbeiter = mysqli_real_escape_string($platinendb_connection, $_POST["Bearbeiter"]);
  $stmt = $login_connection->prepare(
    "SELECT user_id FROM users WHERE user_name=?"
  );
  $stmt->bind_param("s", $bearbeiter);
  $stmt->execute();
  $result = $stmt->get_result();
  $Bearbeiter = mysqli_fetch_assoc($result);


  /*
          Inputs auslesen Finanzstelle
          */

  $finanz = null;
  if (isset($_POST["Finanz"]) && $_POST["Finanz"] != "") {
    $finanz = mysqli_real_escape_string($platinendb_connection, $_POST["Finanz"]);
  }




  /*
   Inputs auslesen Status
  */
  if (isset($_POST["Status"])) {
    $Status = mysqli_real_escape_string($platinendb_connection, $_POST["Status"]);
  }

  //wenn keine platine auf nutzen dann status nicht verändern
  if (!platineAufNutzen($_POST['Id'], $platinendb_connection)) {
    $Status = "neu";
  }


  // Datum input von erstellt, Fertigung und abgeschlossen auslesen
  $Erstellt = null;

  //Datum aus db holen
  $stmt = $platinendb_connection->prepare(
    "SELECT Datum FROM nutzen WHERE ID =?"
  );
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $datumAlt = mysqli_fetch_assoc($result);


  $datumAltString = $datumAlt['Datum'];
  $createDate = new DateTime($datumAltString);
  $datumAltohnezeit = $createDate->format('Y-m-d');



  //neues Datum aus input holen
  $datumzumformatieren = strtotime(mysqli_real_escape_string($platinendb_connection, $_POST["Erstellt"]));
  $datumNeu = date('Y-m-d', $datumzumformatieren);


  if ($datumAltohnezeit == $datumNeu) {
    $Erstellt = $datumAlt['Datum'];
  } else {
    $Erstellt = $datumNeu;
  }



  if (empty($_POST["Fertigung"])) {
    $Fertigung = null;
  } else {
    $datumzumformatieren = strtotime(mysqli_real_escape_string($platinendb_connection, $_POST["Fertigung"]));
    $Fertigung = date('Y-m-d', $datumzumformatieren);
  }


  if (empty($_POST["Abgeschlossen"])) {
    $Abgeschlossen = null;
  } else {
    $datumzumformatieren = strtotime(mysqli_real_escape_string($platinendb_connection, $_POST["Abgeschlossen"]));
    $Abgeschlossen = date('Y-m-d', $datumzumformatieren);
  }




  /*
          Inputs auslesen material
          */
  $material2 = mysqli_real_escape_string($platinendb_connection, $_POST["Material"]);
  $stmt = $platinendb_connection->prepare(
    "SELECT ID FROM material WHERE Name=?"
  );
  $stmt->bind_param("s", $material2);
  $stmt->execute();
  $result = $stmt->get_result();
  $row2 = mysqli_fetch_assoc($result);

  /*
          Inputs auslesen Endkupfer
          */
  $Endkupfer = mysqli_real_escape_string($platinendb_connection, $_POST["Endkupfer"]);


  /*
          Inputs auslesen Status
          */
  $Staerke = mysqli_real_escape_string($platinendb_connection, $_POST["Staerke"]);


  /*
          Inputs auslesen Lagen
          */
  $Lagen = mysqli_real_escape_string($platinendb_connection, $_POST["Lagen"]);



  /*
          Inputs auslesen Größe
          */
  $Groesse = mysqli_real_escape_string($platinendb_connection, $_POST["Groesse"]);


  /*
          Inputs auslesen int/ext
          */

  $Int = mysqli_real_escape_string($platinendb_connection, $_POST["Int"]);
  $intoderext = "intoderext = ";
  $intoderext .= "'";
  $intoderext .= $Int;
  $intoderext .= "',";



  $stmt = $platinendb_connection->prepare(
    "SELECT Status1 FROM nutzen WHERE ID=?"
  );
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $statusur = mysqli_fetch_row($result)[0];


  if ($statusur != "neu") {
    $intoderext = "";
  }



  /*
          Input auslesen Testdaten
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



  $lagen_ID = null;

  //Bearbeiten und Layer Daten hinzufügen
  if (!empty($_FILES)) {
    uploadSecurity("text");
    $a = readfiledata();
    if ($a != null) {
      $Lagen_ID = lagenAnlegen($a, $platinendb_connection);

      $stmt = $platinendb_connection->prepare(
        "UPDATE nutzen SET Nr = ?,Bearbeiter_ID = ?,Material_ID = ?, Finanzstelle_ID = ?, Endkupfer = ?, Staerke = ?,Lagen = ?, Lagen_ID = ?, Groesse = ?,Datum = ?, $intoderext Status1 = ?,Testdaten = ?,Datum1 = ?,Datum2 = ?,Kommentar = ? WHERE ID = ?"
      );
      $stmt->bind_param("iiiissiisssisssi", $Nr, $Bearbeiter['user_id'], $row2['ID'], $finanz, $Endkupfer, $Staerke, $Lagen, $Lagen_ID, $Groesse, $Erstellt, $Status, $Testdaten, $Fertigung, $Abgeschlossen, $Kommentar, $id);
    }
  } else {
    //Bearbeiten, kupferdaten und finanzstelle löschen
    if (isset($_POST['layerLoeschen'])) {
      if (mysqli_real_escape_string($platinendb_connection, $_POST['layerLoeschen']) == "true") {
        $stmt = $platinendb_connection->prepare(
          "SELECT Lagen_ID FROM nutzen WHERE ID = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $queryresult = $stmt->get_result();
        $lagen_ID = mysqli_fetch_array($queryresult)[0];


        $Lagen_ID = null;
        $finanz = null;
        $stmt = $platinendb_connection->prepare(
          "UPDATE nutzen SET Nr = ?,Bearbeiter_ID = ?,Material_ID = ?, Finanzstelle_ID = ?, Endkupfer = ?, Staerke = ?,Lagen = ?, Lagen_ID = ?, Groesse = ?,Datum = ?, $intoderext Status1 = ?,Testdaten = ?,Datum1 = ?,Datum2 = ?,Kommentar = ? WHERE ID = ?"
        );
        $stmt->bind_param("iiiissiisssisssi", $Nr, $Bearbeiter['user_id'], $row2['ID'], $finanz, $Endkupfer, $Staerke, $Lagen, $Lagen_ID, $Groesse, $Erstellt, $Status, $Testdaten, $Fertigung, $Abgeschlossen, $Kommentar, $id);
      }
    }
    //Nur bearbeiten
    else {
      $stmt = $platinendb_connection->prepare(
        "UPDATE nutzen SET Nr = ?,Bearbeiter_ID = ?,Material_ID = ?, Finanzstelle_ID = ?, Endkupfer = ?, Staerke = ?,Lagen = ?, Groesse = ?,Datum = ?, $intoderext Status1 = ?,Testdaten = ?,Datum1 = ?,Datum2 = ?,Kommentar = ? WHERE ID = ?"
      );
      $stmt->bind_param("iiiississsisssi", $Nr, $Bearbeiter['user_id'], $row2['ID'], $finanz, $Endkupfer, $Staerke, $Lagen, $Groesse, $Erstellt, $Status, $Testdaten, $Fertigung, $Abgeschlossen, $Kommentar, $id);
    }
  }

  $stmtTemp = $platinendb_connection->prepare(
    "SELECT Status1 FROM nutzen WHERE ID=?"
  );
  $stmtTemp->bind_param("i", $id);
  $stmtTemp->execute();
  $queryresult = $stmtTemp->get_result();
  $ursprungStatus = mysqli_fetch_array($queryresult);
  $ursprungStatus = $ursprungStatus['Status1'];

  $stmtTemp = $platinendb_connection->prepare(
    "SELECT Platinen_ID FROM nutzenplatinen WHERE Nutzen_ID =?"
  );
  $stmtTemp->bind_param("i", $id);
  $stmtTemp->execute();
  $allePlaufNutzen = $stmtTemp->get_result();



  //Nutzen nur in Fertigung überführt wenn Platinen drauf sind
  if ($ursprungStatus == "neu" && $Status != "neu") {
    if ($allePlaufNutzen->num_rows <= 0) {
      header('Content-Type: application/json');
      //nicht mehr nötig da javascript es nicht zulässt
      //echo json_encode(array('data'=> 'keineplatineaufnutzen')); 
      die();
    }
  }


  $stmt->execute();
  $queryresult = $stmt->get_result();


  //Lagen_ID falls vorhanden löschen nachdem Nutzen auf neu gesetzt wurde
  if ($lagen_ID != null) {
    $stmt = $platinendb_connection->prepare(
      "DELETE FROM nutzenlagen WHERE id = ?"
    );
    $stmt->bind_param("i", $lagen_ID);
    $stmt->execute();
    $queryresult = $stmt->get_result();
  }


  //Wenn Nutzen in abgeschlossen überführt wurde
  if ($Status == "abgeschlossen") {
    foreach ($allePlaufNutzen as $row) {
      $pl = $row['Platinen_ID'];
      deleteDownload(1, $pl, null, $platinendb_connection);
    }
  }

  $sicherheit->checkQuery($platinendb_connection);


  mysqli_close($platinendb_connection);

  mysqli_close($login_connection);
} else {
  header('Content-Type: application/json');
  echo json_encode(array('data' => "fehlerhaft"));
}
