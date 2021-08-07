<?php
require_once("../config/db2.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();
$link = OpenCon();
$link2 = OpenCon2();


//$aktion = "einfuegen";
//sicherheit checks
if(!(isset($_POST['aktion']))) {
  $aktion = "";
}
else {
  $aktion = mysqli_real_escape_string($link, $_POST["aktion"]);
}
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $link, $link2);
$bestanden = $sicherheit->ergebnis();



if($bestanden == true) {


          /*
          Inputs auslesen Name
          */
          $Name = mysqli_real_escape_string($link, $_POST["Name"]);




          /*
          Inputs auslesen Auftraggeber
          */ 
          if(isUserEst($link) == true) {
            $Auftraggeber2 = mysqli_real_escape_string($link2, $_POST["Auftraggeber"]);

          }
          else {
            $Auftraggeber2 = mysqli_real_escape_string($link, $_SESSION['user_name']);
          }
          
          $Auftraggeber2query = "SELECT user_id FROM users WHERE user_name='$Auftraggeber2'"; 
          $Auftraggeber2id =  mysqli_query($link2, $Auftraggeber2query);
          $row = mysqli_fetch_assoc($Auftraggeber2id);

          /*
          Inputs auslesen Anzahl
          */
          $Anzahl = mysqli_real_escape_string($link, $_POST["Anzahl"]);


          /*
          Inputs auslesen material
          */
          $material2 = mysqli_real_escape_string($link, $_POST["Material"]);
          $material2query = "SELECT ID FROM material WHERE Name='$material2'"; 
          $material2id =  mysqli_query($link, $material2query);
          $row2 = mysqli_fetch_assoc($material2id);   



          /*
          Inputs auslesen Endkupfer
          */
          $Endkupfer = mysqli_real_escape_string($link, $_POST["Endkupfer"]);



          /*
          Inputs auslesen Stärke
          */
          $Staerke = mysqli_real_escape_string($link, $_POST["Staerke"]);



          /*
          Inputs auslesen Lagen
          */
          $Lagen = mysqli_real_escape_string($link, $_POST["Lagen"]);


          /*
          Inputs auslesen Größe
          */
          $Groeße = mysqli_real_escape_string($link, $_POST["Groesse"]);



          /*
          Inputs auslesen Oberfläche
          */
          $Oberflaeche = mysqli_real_escape_string($link, $_POST["Oberflaeche"]);


          /*
          Inputs auslesen Loetstopp
          */
          $Loetstopp = mysqli_real_escape_string($link, $_POST["Loetstopp"]);


          /*
          automatisch datum einfügen
          */
          $erstelltam = date('Y-m-d  H:i:s ', time());




          /*
          Inputs auslesen wunschDatum
          */

          if (empty($_POST["Wunschdatum"])) {
            $Wunschdatum = NULL;
          }
          else {
            $datumzumformatieren = strtotime(mysqli_real_escape_string($link, $_POST["Wunschdatum"]));
            $Wunschdatum = date('Y-m-d', $datumzumformatieren);
          }

        


          /*
          Inputs auslesen Kommentar
          */
          $Kommentar = mysqli_real_escape_string($link, $_POST["Kommentar"]);


          /*
          Ergebnis>( inserten )
          */
          

       
          $eintrag = "INSERT INTO platinen (Name, Auftraggeber_ID, Anzahl, Material_ID, Endkupfer, Staerke, Lagen, Groesse, Oberflaeche, Loetstopp, erstelltam, wunschDatum, Kommentar) VALUES ('$Name', '$row[user_id]', '$Anzahl', '$row2[ID]', '$Endkupfer', '$Staerke', '$Lagen', '$Groeße', '$Oberflaeche', '$Loetstopp', '$erstelltam', '$Wunschdatum', '$Kommentar')";
          


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