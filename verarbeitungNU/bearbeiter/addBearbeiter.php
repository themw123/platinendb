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

      $bearbeiter = mysqli_real_escape_string($link, $_POST['bearOderAuftr']);


      $add = "INSERT INTO bearbeiter(BearbeiterName) VALUE('$bearbeiter')";


      mysqli_query($link, $add);


      $sicherheit->checkQuery($link);

      
      mysqli_close($link);

  }



?>