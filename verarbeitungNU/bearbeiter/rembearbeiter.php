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
  
      $bearbeiter = mysqli_real_escape_string($link, $_POST['Text']);
      $bearbeiter2query = "SELECT ID FROM bearbeiter WHERE BearbeiterName='$bearbeiter'"; 
      $bearbeiterid =  mysqli_query($link, $bearbeiter2query);
      $Bearbeiter = mysqli_fetch_assoc($bearbeiterid ); 
      $BearbeiterId = $Bearbeiter['ID'];



      $del = "DELETE FROM bearbeiter WHERE id=$BearbeiterId";


      mysqli_query($link, $del);


      $sicherheit->checkQuery($link);

      
      mysqli_close($link);


}



?>