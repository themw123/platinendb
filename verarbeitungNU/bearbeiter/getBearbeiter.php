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
 
  
         $query = "SELECT BearbeiterName FROM bearbeiter"; 
         $result = mysqli_query($link, $query);  
         $namen = array();
         $counter = 0;
         if ($result->num_rows > 0) {
   
            while($row = $result->fetch_assoc()){
                
                $namen[$counter] = $row['BearbeiterName'];
                $counter = $counter + 1;
            }
            echo json_encode($namen);
        }
  }


 ?>
