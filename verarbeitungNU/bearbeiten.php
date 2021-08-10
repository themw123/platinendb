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



          $id = mysqli_real_escape_string($link, $_POST['Id']);



          /*
          Inputs auslesen Nr
          */

          $Nr = mysqli_real_escape_string($link, $_POST["Nr"]);



          /*
          Inputs auslesen Bearbeiter
          */
          $bearbeiter2 = mysqli_real_escape_string($link, $_POST["Bearbeiter"]);
          $bearbeiter2query = "SELECT ID FROM bearbeiter WHERE BearbeiterName='$bearbeiter2'"; 
          $bearbeiterid =  mysqli_query($link, $bearbeiter2query);
          $Bearbeiter = mysqli_fetch_assoc($bearbeiterid);   


          /*
          Inputs auslesen Status
          */
          $Status = mysqli_real_escape_string($link, $_POST["Status"]);
          



          // Datum input von erstellt, Fertigung und abgeschlossen auslesen
          $Erstellt = null;

          //Datum aus db holen
          $datumquery = "SELECT Datum FROM nutzen WHERE ID ='$id'"; 
          $datumresult =  mysqli_query($link, $datumquery);
          $datumAlt = mysqli_fetch_assoc($datumresult );  
          
          $datumAltString = $datumAlt['Datum'];
          $createDate = new DateTime($datumAltString);
          $datumAltohnezeit = $createDate->format('Y-m-d');
                  
          
          
          //neues Datum aus input holen
          $datumzumformatieren = strtotime(mysqli_real_escape_string($link, $_POST["Erstellt"]));
          $datumNeu = date('Y-m-d', $datumzumformatieren);
          
                    
          if($datumAltohnezeit == $datumNeu) {
            $Erstellt = $datumAlt['Datum'];
          }
          
          else {
            $Erstellt = $datumNeu;
          }
          
        
          
          if (empty($_POST["Fertigung"])) {
            $Fertigung = NULL;
          }
          else {
            $datumzumformatieren = strtotime(mysqli_real_escape_string($link, $_POST["Fertigung"]));
            $Fertigung = date('Y-m-d', $datumzumformatieren);
          }  
          
          
            if (empty($_POST["Abgeschlossen"])) {
            $Abgeschlossen = NULL;
          }
          else {
            $datumzumformatieren = strtotime(mysqli_real_escape_string($link, $_POST["Abgeschlossen"]));
            $Abgeschlossen = date('Y-m-d', $datumzumformatieren);
          }  

          

          
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
          Inputs auslesen Status
          */
          $Staerke = mysqli_real_escape_string($link, $_POST["Staerke"]);
          

          /*
          Inputs auslesen Lagen
          */
          $Lagen = mysqli_real_escape_string($link, $_POST["Lagen"]);
        


          /*
          Inputs auslesen Größe
          */
          $Groesse = mysqli_real_escape_string($link, $_POST["Groesse"]);


          /*
          Inputs auslesen int/ext
          */
          $Int = mysqli_real_escape_string($link, $_POST["Int"]);


          
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
          $Kommentar = mysqli_real_escape_string($link, $_POST["Kommentar"]);
        


          //Bearbeiten und Layer Daten hinzufügen
          if(!empty($_FILES)) {
            uploadSecurity();
            $a = readfiledata();
            $lagenHinzufuegen = lagenBefehl($a);
            $bearbeiten= "UPDATE nutzen SET Nr = '$Nr',Bearbeiter_ID = $Bearbeiter[ID],Material_ID = $row2[ID],Endkupfer = '$Endkupfer',Staerke = '$Staerke',Lagen = '$Lagen',$lagenHinzufuegen,Groesse = '$Groesse',Datum = '$Erstellt',intoderext = '$Int',Status1 = '$Status',Testdaten = '$Testdaten',Datum1 = '$Fertigung',Datum2 = '$Abgeschlossen',Kommentar = '$Kommentar' WHERE ID = $id";
          }
          else {
            //Bearbeiten und Layer Daten löschen
            if(isset($_POST['layerLoeschen'])) {
              if($_POST['layerLoeschen'] == "true") {
                $lagenLoeschen = "Top = '0', L2 = '0', L3 = '0', L4 = '0', L5 = '0', Bottom = '0', LagenSumme = '0'";
                $bearbeiten= "UPDATE nutzen SET Nr = '$Nr',Bearbeiter_ID = $Bearbeiter[ID],Material_ID = $row2[ID],Endkupfer = '$Endkupfer',Staerke = '$Staerke',Lagen = '$Lagen',$lagenLoeschen,Groesse = '$Groesse',Datum = '$Erstellt',intoderext = '$Int',Status1 = '$Status',Testdaten = '$Testdaten',Datum1 = '$Fertigung',Datum2 = '$Abgeschlossen',Kommentar = '$Kommentar' WHERE ID = $id";
              }
            }
            //Nur bearbeiten
            else {
              $bearbeiten= "UPDATE nutzen SET Nr = '$Nr',Bearbeiter_ID = $Bearbeiter[ID],Material_ID = $row2[ID],Endkupfer = '$Endkupfer',Staerke = '$Staerke',Lagen = '$Lagen',Groesse = '$Groesse',Datum = '$Erstellt',intoderext = '$Int',Status1 = '$Status',Testdaten = '$Testdaten',Datum1 = '$Fertigung',Datum2 = '$Abgeschlossen',Kommentar = '$Kommentar' WHERE ID = $id";
            }

          }


          mysqli_query($link, $bearbeiten);


          $sicherheit->checkQuery($link);

          
          mysqli_close($link);
      

  
    
    
}
    
else {
  header('Content-Type: application/json');
  echo json_encode(array('data'=> "fehlerhaft"));
  die();
}

?>