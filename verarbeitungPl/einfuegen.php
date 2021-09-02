<?php
require_once("../config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

$login_connection= $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//$aktion = "einfuegen";
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


          /*
          Inputs auslesen Name
          */
          $Name = mysqli_real_escape_string($platinendb_connection, $_POST["Name"]);




          /*
          Inputs auslesen Auftraggeber
          */ 
          if(isUserEst($platinendb_connection) == true) {
            $Auftraggeber2 = mysqli_real_escape_string($login_connection, $_POST["Auftraggeber"]);

          }
          else {
            $Auftraggeber2 = mysqli_real_escape_string($platinendb_connection, $_SESSION['user_name']);
          }
          
          $Auftraggeber2query = "SELECT user_id FROM users WHERE user_name='$Auftraggeber2'"; 
          $Auftraggeber2id =  mysqli_query($login_connection, $Auftraggeber2query);
          $row = mysqli_fetch_assoc($Auftraggeber2id);

          /*
          Inputs auslesen Anzahl
          */
          $Anzahl = mysqli_real_escape_string($platinendb_connection, $_POST["Anzahl"]);


          /*
          Inputs auslesen material
          */
          $material2 = mysqli_real_escape_string($platinendb_connection, $_POST["Material"]);
          $material2query = "SELECT ID FROM material WHERE Name='$material2'"; 
          $material2id =  mysqli_query($platinendb_connection, $material2query);
          $row2 = mysqli_fetch_assoc($material2id);   



          /*
          Inputs auslesen Endkupfer
          */
          $Endkupfer = mysqli_real_escape_string($platinendb_connection, $_POST["Endkupfer"]);



          /*
          Inputs auslesen Stärke
          */
          $Staerke = mysqli_real_escape_string($platinendb_connection, $_POST["Staerke"]);



          /*
          Inputs auslesen Lagen
          */
          $Lagen = mysqli_real_escape_string($platinendb_connection, $_POST["Lagen"]);


          /*
          Inputs auslesen Größe
          */
          $Groeße = mysqli_real_escape_string($platinendb_connection, $_POST["Groesse"]);



          /*
          Inputs auslesen Oberfläche
          */
          $Oberflaeche = mysqli_real_escape_string($platinendb_connection, $_POST["Oberflaeche"]);


          /*
          Inputs auslesen Loetstopp
          */
          $Loetstopp = mysqli_real_escape_string($platinendb_connection, $_POST["Loetstopp"]);


          /*
          automatisch datum einfügen
          */
          $erstelltam = date('Y-m-d  H:i:s ', time());




          /*
          Inputs auslesen wunschDatum
          */

          if (empty($_POST["Wunschdatum"])) {
            $Wunschdatum = "null";
          }
          else {
            $datumzumformatieren = strtotime(mysqli_real_escape_string($platinendb_connection, $_POST["Wunschdatum"]));
            $Wunschdatum = "'";
            $Wunschdatum .= date('Y-m-d', $datumzumformatieren);
            $Wunschdatum .= "'";
          }

        


          /*
          Inputs auslesen Kommentar
          */
          $Kommentar = mysqli_real_escape_string($platinendb_connection, $_POST["Kommentar"]);


          /*
          Ergebnis>( inserten )
          */
          

       
          $eintrag = "INSERT INTO platinen (Name, Auftraggeber_ID, Anzahl, Material_ID, Endkupfer, Staerke, Lagen, Groesse, Oberflaeche, Loetstopp, erstelltam, wunschDatum, Kommentar, ignorieren) VALUES ('$Name', '$row[user_id]', '$Anzahl', '$row2[ID]', '$Endkupfer', '$Staerke', '$Lagen', '$Groeße', '$Oberflaeche', '$Loetstopp', '$erstelltam', $Wunschdatum, '$Kommentar', '0')";
          


          mysqli_query($platinendb_connection, $eintrag);


          $sicherheit->checkQuery($platinendb_connection);

          
          mysqli_close($platinendb_connection);


}


else {
  header('Content-Type: application/json');
  echo json_encode(array('data'=> "fehlerhaft"));
  die();
}


?>