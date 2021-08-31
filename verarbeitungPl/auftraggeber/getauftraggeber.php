<?php
require_once("../../config/db.php");
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
 
  
         $query = "SELECT user_name FROM users"; 
         $result = mysqli_query($login_connection, $query);  
         $namen = array();
         $counter = 0;
         if ($result->num_rows > 0) {
   
            while($row = $result->fetch_assoc()){
                
                $namen[$counter] = $row['user_name'];
                $counter = $counter + 1;
            }
            echo json_encode($namen);
        }
  }


 ?>
