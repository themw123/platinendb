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
  $aktion = mysqli_real_escape_string($link2, $_POST["aktion"]);
}
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $link, $link2);
$bestanden = $sicherheit->ergebnis();


if($bestanden == true) {
  
      $auftraggeber = mysqli_real_escape_string($link2, $_POST['Text']);
      $auftraggeber2query = "SELECT user_id FROM users WHERE user_name='$auftraggeber'"; 
      $auftraggeberid =  mysqli_query($link2, $auftraggeber2query);
      $Auftraggeber = mysqli_fetch_assoc($auftraggeberid ); 
      $AuftraggeberId = $Auftraggeber['user_id'];



      $del = "DELETE FROM users WHERE user_id=$AuftraggeberId";


      mysqli_query($link2, $del);


      $sicherheit->checkQuery($link2);

      
      mysqli_close($link2);


}



?>