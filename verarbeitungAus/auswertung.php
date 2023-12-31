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
$von = "auswertung";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if ($bestanden == true && $aktion == "auswertung") {

  $zeitraum = mysqli_real_escape_string($platinendb_connection, $_POST["zeitraum"]);
  $auftraggeber = mysqli_real_escape_string($platinendb_connection, $_POST['auftraggeber']);
  if (empty($auftraggeber)) {
    $whereAuftraggeber = "";
  } else {
    $whereAuftraggeber = "login.users.user_name = '$auftraggeber'";
    if ($zeitraum == "monate") {
      $whereAuftraggeber = "and " . $whereAuftraggeber;
    } else if ($zeitraum == "jahre") {
      $whereAuftraggeber = "Where " . $whereAuftraggeber;
    }
  }




  if ($zeitraum == "monate") {
    //für where anweisung in abfrage
    $jahr = mysqli_real_escape_string($platinendb_connection, $_POST['jahr']);


    $stmt = $platinendb_connection->prepare(
      "
      Select
        MonthName(platinendb.platinen.erstelltam) as monat,
        Count(platinendb.platinen.ID) As summe,
        Sum(platinendb.lehrstuhl.kuerzel = 'est') As intern,
        Sum(platinendb.lehrstuhl.kuerzel != 'est') As extern
      From
        platinendb.platinen Inner Join
        login.users On platinendb.platinen.Auftraggeber_ID = login.users.user_id Inner Join
        platinendb.lehrstuhl On login.users.lehrstuhl = platinendb.lehrstuhl.id
      Where
        Year(platinendb.platinen.erstelltam) = ?
        $whereAuftraggeber
      Group By
        Month(platinendb.platinen.erstelltam)
      Order By
        Month(platinendb.platinen.erstelltam)
    "
    );

    $stmt->bind_param("s", $jahr);
    $stmt->execute();
    $query = $stmt->get_result();
  } else if ($zeitraum == "jahre") {

    //für where anweisung in abfrage
    $letzten = mysqli_real_escape_string($platinendb_connection, $_POST['letzten']);

    if ($letzten == 0) {
      $limit = "";
    } else {
      $limit = "LIMIT $letzten";
    }




    $stmt = $platinendb_connection->prepare(
      "
      Select
        Year(platinendb.platinen.erstelltam) as jahr,
        Count(platinendb.platinen.ID) As summe,
        Sum(platinendb.lehrstuhl.kuerzel = 'est') As intern,
        Sum(platinendb.lehrstuhl.kuerzel != 'est') As extern
       From
          platinendb.platinen Inner Join
          login.users On platinendb.platinen.Auftraggeber_ID = login.users.user_id Inner Join
          platinendb.lehrstuhl On login.users.lehrstuhl = platinendb.lehrstuhl.id
          $whereAuftraggeber
       Group By
          Year(platinendb.platinen.erstelltam)
       Order By
          Year(platinendb.platinen.erstelltam) desc
       $limit
       "
    );

    $stmt->execute();
    $query = $stmt->get_result();
  }


  $sicherheit->checkQuery3($platinendb_connection);


  if (isset($query) && $query->num_rows > 0) {

    while ($row = $query->fetch_array()) {

      //$row = mysqli_fetch_array($query);

      $nestedData = array();

      if ($zeitraum == "monate") {
        $nestedData[] = englischZuDeutsch($row["monat"]);
      } else {
        $nestedData[] = $row["jahr"];
      }

      $nestedData[] = $row["summe"];
      $nestedData[] = $row["intern"];
      $nestedData[] = $row["extern"];

      $data[] = $nestedData;
    }


    $json_data = array("data" => $data);
    header('Content-Type: application/json');
    echo json_encode($json_data);  // send data as json format
  } else {
    $data[1] = 'leer';
    header('Content-Type: application/json');
    echo json_encode(array('data' => $data));
    die();
  }

  mysqli_close($platinendb_connection);
  mysqli_close($login_connection);
} else {
  $data[1] = 'fehlerhaft';
  header('Content-Type: application/json');
  echo json_encode(array('data' =>  $data));
  die();
}
