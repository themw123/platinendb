<?php

require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

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



          /*
          Inputs auslesen Nr
          */

          $Nr = mysqli_real_escape_string($platinendb_connection, $_POST["Nr"]);



          /*
          Inputs auslesen Bearbeiter
          */
          $bearbeiter2 = mysqli_real_escape_string($platinendb_connection, $_POST["Bearbeiter"]);
          $bearbeiter2query = "SELECT ID FROM bearbeiter WHERE BearbeiterName='$bearbeiter2'"; 
          $bearbeiterid =  mysqli_query($platinendb_connection, $bearbeiter2query);
          $Bearbeiter = mysqli_fetch_assoc($bearbeiterid);   


          /*
          Inputs auslesen Status
          */
          $Status = mysqli_real_escape_string($platinendb_connection, $_POST["Status"]);
          



          // Datum input von erstellt, Fertigung und abgeschlossen auslesen
          $Erstellt = null;

          //Datum aus db holen
          $datumquery = "SELECT Datum FROM nutzen WHERE ID ='$id'"; 
          $datumresult =  mysqli_query($platinendb_connection, $datumquery);
          $datumAlt = mysqli_fetch_assoc($datumresult );  
          
          $datumAltString = $datumAlt['Datum'];
          $createDate = new DateTime($datumAltString);
          $datumAltohnezeit = $createDate->format('Y-m-d');
                  
          
          
          //neues Datum aus input holen
          $datumzumformatieren = strtotime(mysqli_real_escape_string($platinendb_connection, $_POST["Erstellt"]));
          $datumNeu = date('Y-m-d', $datumzumformatieren);
          
                    
          if($datumAltohnezeit == $datumNeu) {
            $Erstellt = $datumAlt['Datum'];
          }
          
          else {
            $Erstellt = $datumNeu;
          }
          
        
          
          if (empty($_POST["Fertigung"])) {
            $Fertigung = "null";
          }
          else {
            $datumzumformatieren = strtotime(mysqli_real_escape_string($platinendb_connection, $_POST["Fertigung"]));
            $Fertigung = "'";
            $Fertigung .= date('Y-m-d', $datumzumformatieren);
            $Fertigung .= "'";
          }  
          
          
          if (empty($_POST["Abgeschlossen"])) {
            $Abgeschlossen = "null";
          }
          else {
            $datumzumformatieren = strtotime(mysqli_real_escape_string($platinendb_connection, $_POST["Abgeschlossen"]));
            $Abgeschlossen = "'";
            $Abgeschlossen .= date('Y-m-d', $datumzumformatieren);
            $Abgeschlossen .= "'";
          }  

          

          
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
          Inputs auslesen Status
          */
          $Staerke = mysqli_real_escape_string($platinendb_connection, $_POST["Staerke"]);
          

          /*
          Inputs auslesen Lagen
          */
          $Lagen = mysqli_real_escape_string($platinendb_connection, $_POST["Lagen"]);
        


          /*
          Inputs auslesen Größe
          */
          $Groesse = mysqli_real_escape_string($platinendb_connection, $_POST["Groesse"]);


          /*
          Inputs auslesen int/ext
          */
          $Int = mysqli_real_escape_string($platinendb_connection, $_POST["Int"]);


          
          /*
          Input auslesen Testdaten
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
          $Kommentar = mysqli_real_escape_string($platinendb_connection, $_POST["Kommentar"]);
        


          //Bearbeiten und Layer Daten hinzufügen
          if(!empty($_FILES)) {
            uploadSecurity();
            $a = readfiledata();
            $lagenHinzufuegen = lagenBefehl($a);
            $bearbeiten= "UPDATE nutzen SET Nr = '$Nr',Bearbeiter_ID = $Bearbeiter[ID],Material_ID = $row2[ID],Endkupfer = '$Endkupfer',Staerke = '$Staerke',Lagen = '$Lagen',$lagenHinzufuegen,Groesse = '$Groesse',Datum = '$Erstellt',intoderext = '$Int',Status1 = '$Status',Testdaten = '$Testdaten',Datum1 = $Fertigung,Datum2 = $Abgeschlossen,Kommentar = '$Kommentar' WHERE ID = $id";
          }
          else {
            //Bearbeiten und Layer Daten löschen
            if(isset($_POST['layerLoeschen'])) {
              if($_POST['layerLoeschen'] == "true") {
                $lagenLoeschen = "Top = null, L2 = null, L3 = null, L4 = null, L5 = null, Bottom = null, LagenSumme = null";
                $bearbeiten= "UPDATE nutzen SET Nr = '$Nr',Bearbeiter_ID = $Bearbeiter[ID],Material_ID = $row2[ID],Endkupfer = '$Endkupfer',Staerke = '$Staerke',Lagen = '$Lagen',$lagenLoeschen,Groesse = '$Groesse',Datum = '$Erstellt',intoderext = '$Int',Status1 = '$Status',Testdaten = '$Testdaten',Datum1 = $Fertigung,Datum2 = $Abgeschlossen,Kommentar = '$Kommentar' WHERE ID = $id";
              }
            }
            //Nur bearbeiten
            else {
              $bearbeiten= "UPDATE nutzen SET Nr = '$Nr',Bearbeiter_ID = $Bearbeiter[ID],Material_ID = $row2[ID],Endkupfer = '$Endkupfer',Staerke = '$Staerke',Lagen = '$Lagen',Groesse = '$Groesse',Datum = '$Erstellt',intoderext = '$Int',Status1 = '$Status',Testdaten = '$Testdaten',Datum1 = $Fertigung,Datum2 = $Abgeschlossen,Kommentar = '$Kommentar' WHERE ID = $id";
            }

          }


          mysqli_query($platinendb_connection, $bearbeiten);


          $sicherheit->checkQuery($platinendb_connection);

          
          mysqli_close($platinendb_connection); 
          
          mysqli_close($login_connection);  
      

  
    
    
}
    
else {
  header('Content-Type: application/json');
  echo json_encode(array('data'=> "fehlerhaft"));
  die();
}

?>