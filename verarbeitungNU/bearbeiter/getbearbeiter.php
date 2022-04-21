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
 
         $bearbeiter = mysqli_real_escape_string($login_connection, $_SESSION['user_name']);


         $query = "SELECT user_name FROM users WHERE admin = '1' ORDER BY user_name asc"; 
         $result = mysqli_query($login_connection, $query);  
         $namen = array();
         $counter = 0;
         
         if ($result->num_rows > 0) {
   
          while($row = $result->fetch_assoc()){
              
              $namen[$counter][0] = $row['user_name'];
              
              if($row['user_name'] == $bearbeiter) {
                $namen[$counter][1] = 1;
              }
              else {
                $namen[$counter][1] = 0;
              }

              $counter = $counter + 1;
          }
          echo json_encode($namen);
      }
       
        
        mysqli_close($platinendb_connection); 
      
        mysqli_close($login_connection); 
  }


 ?>
