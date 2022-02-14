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
$von = "nutzen";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if($bestanden == true) {
  
      $bearbeiter = mysqli_real_escape_string($platinendb_connection, $_POST['Text']);

      if($bearbeiter == "est") {
        header('Content-Type: application/json');
        echo json_encode(array('data'=> 'nichtest'));
        die();
      }

      $bearbeiter2query = "SELECT ID FROM bearbeiter WHERE BearbeiterName='$bearbeiter'"; 
      $bearbeiterid =  mysqli_query($platinendb_connection, $bearbeiter2query);
      $Bearbeiter = mysqli_fetch_assoc($bearbeiterid ); 
      $BearbeiterId = $Bearbeiter['ID'];


      $del = "DELETE FROM bearbeiter WHERE id=$BearbeiterId";


      mysqli_query($platinendb_connection, $del);


      $sicherheit->checkQuery($platinendb_connection);

      
      mysqli_close($platinendb_connection); 
      
			mysqli_close($login_connection); 


}



?>