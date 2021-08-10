<?php
require_once("../config/db2.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

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

  
            /*
            Inputs auslesen Name
            */
            $Nr = mysqli_real_escape_string($link, $_POST["Nr"]);


            /*
            Bearbeiter
            */
            $bearbeiter = mysqli_real_escape_string($link, $_POST["Bearbeiter"]);
            $bearbeiterquery = "SELECT ID FROM bearbeiter WHERE BearbeiterName='$bearbeiter'"; 
            $Bearbeiterquery =  mysqli_query($link, $bearbeiterquery);
            $Bearbeiterx = mysqli_fetch_assoc($Bearbeiterquery);
            $Bearbeiter = $Bearbeiterx['ID']; 

            /*
            Inputs auslesen Status
            */
            $Status = mysqli_real_escape_string($link, $_POST["Status"]);

            /*
            Fertigung erstellen
            */
            $Fertigung = NULL;

            /*
            abgeschlossen erstellen
            */
            $Abgeschlossen = NULL;

            /*
            Inputs auslesen material
            */
            $material2 = mysqli_real_escape_string($link, $_POST["Material"]);
            $material2query = "SELECT ID FROM material WHERE Name='$material2'"; 
            $material2id =  mysqli_query($link, $material2query);
            $Materialx = mysqli_fetch_assoc($material2id);   
            $Material = $Materialx['ID'];


            /*
            Inputs auslesen Endkupfer
            */
            $Endkupfer = mysqli_real_escape_string($link, $_POST["Endkupfer"]);


            /*
            Inputs auslesen Staerke
            */
            $Staerke = mysqli_real_escape_string($link, $_POST["Staerke"]);



            /*
            Inputs auslesen Lagen
            */
            $Lagen = mysqli_real_escape_string($link, $_POST["Lagen"]);
            


            /*
            Inputs auslesen erstellt
            */

            /*
            if (empty($_POST["Erstellt"])) {
              $Erstellt = NULL;
            }
            else {
              $datumzumformatieren = strtotime(mysqli_real_escape_string($link, $_POST["Erstellt"]));
              $Erstellt = date('Y-m-d H:i:s', $datumzumformatieren);
            }
            */

            date_default_timezone_set('Europe/Berlin');
            $Erstellt = date('Y-m-d H:i:s', time());


            /*
            Inputs auslesen Größe
            */
            $Groesse = mysqli_real_escape_string($link, $_POST["Groesse"]);


            /*
            Inputs auslesen int/ext
            */
            $Int = mysqli_real_escape_string($link, $_POST["Int"]);


            /*
            Testdaten auslesen
            */

            if (isset($_POST['Testdaten'])) {
              $Testdaten = 1;
            }
            else{
              $Testdaten = 0;
            }



            /*
            Inputs auslesen Kommentar
            */
            $Kommentar = mysqli_real_escape_string($link, $_POST["Kommentar"]);


            /*
            Ergebnis(inserten)
            */
            


            $eintrag = "INSERT INTO nutzen (Nr, Bearbeiter_ID, Material_ID, Endkupfer, Staerke, Lagen, Groesse, Datum, intoderext, Status1, Testdaten, Datum1, Datum2, Kommentar) VALUES ('$Nr', '$Bearbeiter', '$Material', '$Endkupfer', '$Staerke', '$Lagen', '$Groesse', '$Erstellt', '$Int', '$Status', '$Testdaten', '$Fertigung', '$Abgeschlossen', '$Kommentar')";


            mysqli_query($link, $eintrag);


            $sicherheit->checkQuery($link);

            
            mysqli_close($link);


}

else {
  header('Content-Type: application/json');
  echo json_encode(array('data'=> "fehlerhaft"));
  die();
}

?>