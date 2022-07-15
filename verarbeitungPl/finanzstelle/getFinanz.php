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

if($bestanden == true && $aktion == "finanzGet") {
 
  
         $query = "
         SELECT 
           id, name, nummer
         FROM 
           finanzstelle
         order by 
           name asc
         "; 
         $result = mysqli_query($platinendb_connection, $query);  
         $alle = array();
         $counter = 0;
         if ($result->num_rows > 0) {
   
            while($row = $result->fetch_assoc()){
              				
                $finanzarray = array();
                $finanzstelle_id = $row['id'];
                $finanzstelle_name = $row['name'];
                $finanzstelle_nummer = $row['nummer'];
                $finanzstelle_nummer = substr($finanzstelle_nummer, -4);
                $finanzstelle = $finanzstelle_name .'_'. $finanzstelle_nummer;

                $finanzarray[0] = $finanzstelle_id;
                $finanzarray[1] = $finanzstelle;

                $alle[$counter] = $finanzarray;
                $counter = $counter + 1;
            }
            echo json_encode($alle);
        }

        mysqli_close($platinendb_connection); 
        mysqli_close($login_connection); 
  }


 ?>
