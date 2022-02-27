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
 
  
         $query = "SELECT BearbeiterName FROM bearbeiter order by ID asc"; 
         $result = mysqli_query($platinendb_connection, $query);  
         $namen = array();
         $counter = 0;

         $estvorhanden = false;

         if ($result->num_rows > 0) {
   
            while($row = $result->fetch_assoc()){

                if($row['BearbeiterName'] == "est") {
                  $estvorhanden = true;
                }
                
                $namen[$counter] = $row['BearbeiterName'];
                $counter = $counter + 1;
            }
            if(!$estvorhanden) {
              $sql = "INSERT INTO bearbeiter (BearbeiterName) values('est')";
              mysqli_query($platinendb_connection, $sql);
            }
            
            echo json_encode($namen);
        }
       
        
        mysqli_close($platinendb_connection); 
      
        mysqli_close($login_connection); 
  }


 ?>
