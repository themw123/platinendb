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

			
  //für where anweisung in abfrage
  $jahr = mysqli_real_escape_string($platinendb_connection, $_POST['jahr']);



  /*
  Abfrage von platinen Tabelle
  */


  $sql = "
  Select
    xxxmonthname(erstelltam) as monat,count(platinendb.platinen.ID) as summe
  From
      platinendb.platinen
      
  GROUP by
      month(erstelltam) 
  Order by
      month(erstelltam) desc;
  ";
  


  $result = $platinendb_connection->query($sql);
  $sicherheit->checkQueryx($platinendb_connection);



  if ($result->num_rows > 0) {
      
      while($row = $result->fetch_array()) {

      
      
      $nestedData=array();

      
      $nestedData[] = $row["monat"];
      $nestedData[] = $row["summe"];
      
      $data[] = $nestedData;

    }

    
    $json_data = array("data" => $data);

    echo json_encode($json_data);  // send data as json format
  }

  else {
    $datax[1] = "leer";
    header('Content-Type: application/json');
    echo json_encode(array('data'=> $datax));
    die();
  }

  mysqli_close($platinendb_connection); 
  mysqli_close($login_connection);  

}
else {
  $datax[1] = "fehlerhaft";
  header('Content-Type: application/json');
  echo json_encode(array('data'=> $datax));
  die();
}

?>