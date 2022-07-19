<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

$login_connection= $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();



//$aktion = "bearbeiten";
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



if($bestanden == true && $aktion == "bearbeiten") {



          $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);


          /*
          Inputs auslesen Name
          */

          $Name = mysqli_real_escape_string($platinendb_connection, $_POST["Name"]);


          if(isUserAdmin($platinendb_connection) == true) {
            /*
            Inputs auslesen Auftraggeber
            */
            $auftraggeber = mysqli_real_escape_string($platinendb_connection, $_POST["Auftraggeber"]);
            $auftraggeberquery = "SELECT user_id FROM users WHERE user_name='$auftraggeber'"; 
            $auftraggeberid =  mysqli_query($login_connection, $auftraggeberquery);
            $Auftraggeber = mysqli_fetch_assoc($auftraggeberid);   
          }



          /*
          Inputs auslesen Finanzstelle
          */
          $finanz = mysqli_real_escape_string($platinendb_connection, $_POST["Finanz"]);
          

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
          $Groeße = mysqli_real_escape_string($platinendb_connection, $_POST["Groeße"]);


          /*
          Inputs auslesen Oberfläche
          */
          $Oberflaeche = mysqli_real_escape_string($platinendb_connection, $_POST["Oberflaeche"]);


          /*
          Inputs auslesen Loetstopp
          */
          $Loetstopp = mysqli_real_escape_string($platinendb_connection, $_POST["Loetstopp"]);


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
          Input auslesen Testdaten
          */
          if (isset($_POST['Ignorieren'])) {
            $Ignorieren = 1;
          }
          else{
            $Ignorieren = 0;
          }

          /*
          Input auslesen Fertigung
          */
          if (isset($_POST['Fertigung'])) {
            $Fertigung = 1;
          }
          else{
            $Fertigung = 0;
          }

          if (isset($_POST['Bearbeiter'])) {
            $Bearbeiter = mysqli_real_escape_string($platinendb_connection, $_POST["Bearbeiter"]);
          }

          
          //bearbeitung durchführen
          if(isUserAdmin($platinendb_connection)) {
            $bearbeiten= "UPDATE platinen SET Name = '$Name',Anzahl = $Anzahl, Auftraggeber_ID = $Auftraggeber[user_id], Finanzstelle_ID = $finanz, Material_ID = $row2[ID],Endkupfer = '$Endkupfer',Staerke = '$Staerke',Lagen = $Lagen,Groesse = '$Groeße',Oberflaeche = '$Oberflaeche',Loetstopp = '$Loetstopp',wunschDatum = $Wunschdatum,Kommentar = '$Kommentar', ignorieren = '$Ignorieren' WHERE ID = $id";
            $user = mysqli_real_escape_string($login_connection, $_SESSION['user_name']);
            

            if($Fertigung == 1 && !isInFertigung($id, $platinendb_connection) && !isOnNutzen($id, $platinendb_connection)) {
              //In Fertigung überführen. Erst Nutzen erstellen und Platine da drauf packen und diesen in Fertigung versetzten.
              ueberfuehren($id, $Anzahl, $user, $finanz, $row2['ID'], $Endkupfer, $Staerke, $Lagen, $platinendb_connection);
            }

          }
          else {
            $bearbeiten= "UPDATE platinen SET Name = '$Name',Anzahl = $Anzahl, Finanzstelle_ID = $finanz,  Material_ID = $row2[ID],Endkupfer = '$Endkupfer',Staerke = '$Staerke',Lagen = $Lagen,Groesse = '$Groeße',Oberflaeche = '$Oberflaeche',Loetstopp = '$Loetstopp',wunschDatum = $Wunschdatum,Kommentar = '$Kommentar' WHERE ID = $id";
          }
        

          mysqli_query($platinendb_connection, $bearbeiten);
          
          $sicherheit->checkQuery($platinendb_connection);

          mysqli_close($platinendb_connection); 
          mysqli_close($login_connection);  

}

    
  else {
    header('Content-Type: application/json');
    echo json_encode(array('data'=> "fehlerhaft"));
}

  
    

?>