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

        $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);
        
        $nutzenID = "SELECT Nutzen_ID FROM nutzenplatinen WHERE ID=$id";
        $nutzenID = mysqli_query($platinendb_connection, $nutzenID);
        $nutzenID = mysqli_fetch_array($nutzenID);
        $nutzenID = $nutzenID['Nutzen_ID'];
  
  
        $getZustand = "SELECT Status1 FROM nutzen WHERE ID=$nutzenID";
        $getZustand = mysqli_query($platinendb_connection, $getZustand);
        $getZustand = mysqli_fetch_array($getZustand);
        $getZustand = $getZustand['Status1'];
        
        //nur Anzahl aktualisieren wenn Nutzen im Zustand neu ist. Ansonnsten abbruch ab hier.
        if($getZustand != "neu") {
          $sicherheit->checkQuery4();
        }
        
        
        $anzahl = mysqli_real_escape_string($platinendb_connection, $_POST['anzahl']);

        $anzahlupdate = "UPDATE nutzenplatinen SET platinenaufnutzen = '$anzahl'  WHERE id=$id";
      
        mysqli_query($platinendb_connection, $anzahlupdate);


        $PlatinenID = "select Platinen_ID from nutzenplatinen where ID = $id"; 
        $PlatinenID = mysqli_query($platinendb_connection,$PlatinenID);
        $PlatinenID = mysqli_fetch_array($PlatinenID);
        $PlatinenID = $PlatinenID['Platinen_ID']; 
        deleteDownload($PlatinenID, $platinendb_connection);


        $sicherheit->checkQuery($platinendb_connection);

      
        mysqli_close($platinendb_connection); 
        
        mysqli_close($login_connection);  

            

}
          

?>