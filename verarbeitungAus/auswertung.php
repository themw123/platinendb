<?php

require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

$login_connection= $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//sicherheit checks
if(!(isset($_POST['aktion']))) {
  $aktion = "";
}
else {
  $aktion = mysqli_real_escape_string($platinendb_connection, $_POST["aktion"]);
}
$von = "auswertung";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();



if($bestanden == true && $aktion == "auswertung") {


  $zeitraum = mysqli_real_escape_string($platinendb_connection, $_POST["zeitraum"]);
	

  if($zeitraum == "monate") {
    //für where anweisung in abfrage
    $jahr = mysqli_real_escape_string($platinendb_connection, $_POST['jahr']);


    $sql = "
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
      Year(platinendb.platinen.erstelltam) = '$jahr'
    Group By
      Month(platinendb.platinen.erstelltam)
    Order By
      Month(platinendb.platinen.erstelltam)
    ";
  
  }

  else if ($zeitraum == "jahre") {

    //für where anweisung in abfrage
    $letzten = mysqli_real_escape_string($platinendb_connection, $_POST['letzten']);
    
    if ($letzten == 0) {
      $limit = "";
    }
    else {
      $limit = "LIMIT $letzten";
    }

    $sql = "
    Select
      Year(platinendb.platinen.erstelltam) as jahr,
      Count(platinendb.platinen.ID) As summe,
      Sum(platinendb.lehrstuhl.kuerzel = 'est') As intern,
      Sum(platinendb.lehrstuhl.kuerzel != 'est') As extern
     From
        platinendb.platinen Inner Join
        login.users On platinendb.platinen.Auftraggeber_ID = login.users.user_id Inner Join
        platinendb.lehrstuhl On login.users.lehrstuhl = platinendb.lehrstuhl.id
     Group By
        Year(platinendb.platinen.erstelltam)
     Order By
        Year(platinendb.platinen.erstelltam) desc
     $limit; 
     ";
  }



  $result = $platinendb_connection->query($sql);
  
  $sicherheit->checkQuery3($platinendb_connection);


  if ($result->num_rows > 0) {
      
      while($row = $result->fetch_array()) {

      
      
      $nestedData=array();

      if($zeitraum == "monate") {
        $nestedData[] = $row["monat"];
      }
      else {
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
  }

  else {
    $data[1] = 'leer';
    header('Content-Type: application/json');
    echo json_encode(array('data'=> $data));
    die();
  }

  mysqli_close($platinendb_connection); 
  mysqli_close($login_connection);  

}
else {
  $data[1] = 'fehlerhaft';
  header('Content-Type: application/json');
  echo json_encode(array('data'=>  $data));
  die();
}

?>