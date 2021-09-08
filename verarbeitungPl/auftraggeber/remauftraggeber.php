<?php
require_once("/documents/config/db.php");
require_once("../../classes/Login.php");
require_once("../../funktion/alle.php");
require_once("../../classes/Sicherheit.php");

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
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if($bestanden == true) {
  
      $auftraggeber = mysqli_real_escape_string($login_connection, $_POST['Text']);
      $auftraggeber2query = "SELECT user_id FROM users WHERE user_name='$auftraggeber'"; 
      $auftraggeberid =  mysqli_query($login_connection, $auftraggeber2query);
      $Auftraggeber = mysqli_fetch_assoc($auftraggeberid ); 
      $AuftraggeberId = $Auftraggeber['user_id'];



      $del = "DELETE FROM users WHERE user_id=$AuftraggeberId";


      mysqli_query($login_connection, $del);


      $sicherheit->checkQuery($login_connection);

      
      mysqli_close($platinendb_connection);
       
			mysqli_close($login_connection); 


}



?>