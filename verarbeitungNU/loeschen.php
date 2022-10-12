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
$von = "nutzen";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if($bestanden == true && $aktion == "loeschen") {

	
        $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);

        $lagen_ID = "SELECT Lagen_ID FROM nutzen WHERE ID = '$id'";
        $lagen_ID = mysqli_query($platinendb_connection,$lagen_ID);
        $lagen_ID = mysqli_fetch_row($lagen_ID);
        $lagen_ID = $lagen_ID[0]; 


        $loeschen = "DELETE FROM nutzen WHERE id=$id";


        mysqli_query($platinendb_connection, $loeschen);

        if($lagen_ID != null) {
          $loeschen2 = "DELETE FROM lagen WHERE id=$lagen_ID";
          mysqli_query($platinendb_connection, $loeschen2);
        }

        $sicherheit->checkQuery($platinendb_connection);

        
        mysqli_close($platinendb_connection); 
        
        mysqli_close($login_connection);  



}

else {
  header('Content-Type: application/json');
  echo json_encode(array('data'=> "fehlerhaft"));
}
    



?>