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


if($bestanden == true  && $aktion == "auftraggeber") {

      $auftraggeber = mysqli_real_escape_string($platinendb_connection, $_POST['auftr']);
      $lehrstuhl = mysqli_real_escape_string($platinendb_connection, $_POST['lehr']);

    
      $lehrstuhl = 
      "SELECT
        id
      FROM 
        lehrstuhl
      WHERE 
        kuerzel = '$lehrstuhl'";

      $lehrstuhl =  mysqli_query($platinendb_connection, $lehrstuhl);
      $lehrstuhl = mysqli_fetch_assoc($lehrstuhl);
      $lehrstuhl = $lehrstuhl["id"];


      $add = "INSERT INTO users(user_name, admin, lehrstuhl) VALUE('$auftraggeber', '0', '$lehrstuhl')";
      mysqli_query($login_connection, $add);
      $sicherheit->checkQuery($login_connection);

      
      mysqli_close($platinendb_connection); 
      
			mysqli_close($login_connection); 

  }
