<?php

require_once("../../config/db2.php");
require_once("../../classes/Login.php");
require_once("../../funktion/alle.php");
require_once("../../classes/Sicherheit.php");

$login = new Login();
$link = OpenCon();
$link2 = OpenCon2();


//sicherheit checks
if(!(isset($_POST['aktion']))) {
  $aktion = "";
}
else {
  $aktion = mysqli_real_escape_string($link, $_POST["aktion"]);
}
$von = "nutzen";
$sicherheit = new Sicherheit($aktion, $von, $login, $link, $link2);
$bestanden = $sicherheit->ergebnis();


if($bestanden == true) {

      $PlatinenID = mysqli_real_escape_string($link, $_POST['Id']);
      $NutzenID = mysqli_real_escape_string($link, $_POST['NutzenId']);
      $Anzahl = mysqli_real_escape_string($link, $_POST['anzahl']);


      if($Anzahl > 0) {


      $hinzufuegen = "INSERT INTO nutzenplatinen (Platinen_ID, Nutzen_ID, platinenaufnutzen) VALUES ('$PlatinenID', '$NutzenID', '$Anzahl')";

      
      mysqli_query($link, $hinzufuegen);


      $sicherheit->checkQuery($link);

      
      mysqli_close($link);

      }
      else {
        echo json_encode(array('data'=> "fehlerhaft"));
      }

}
    
?>