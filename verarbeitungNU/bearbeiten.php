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


if($bestanden == true && $aktion == "bearbeiten") {



          $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);



          /*
          Inputs auslesen Nr
          */

          $Nr = mysqli_real_escape_string($platinendb_connection, $_POST["Nr"]);



          /*
          Inputs auslesen Bearbeiter
          */
          $bearbeiter2 = mysqli_real_escape_string($platinendb_connection, $_POST["Bearbeiter"]);
          $bearbeiter2query = "SELECT user_id FROM login.users WHERE user_name='$bearbeiter2'"; 
          $bearbeiterid =  mysqli_query($login_connection, $bearbeiter2query);
          $Bearbeiter = mysqli_fetch_assoc($bearbeiterid);   


          /*
          Inputs auslesen Finanzstelle
          */
          
          $finanz = mysqli_real_escape_string($platinendb_connection, $_POST["Finanz"]);
            
          if($finanz == "") {
            $finanz = "null";
          }


          /*
          Inputs auslesen Status
          */
          $Status = mysqli_real_escape_string($platinendb_connection, $_POST["Status"]);
          
          //wenn keine platine auf nutzen dann status nicht verändern
          if(!platineAufNutzen($_POST['Id'], $platinendb_connection)) {
            $Status = "neu";
          }


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
          $intoderext = "intoderext = ";
          $intoderext .= "'";
          $intoderext .= $Int;
          $intoderext .= "',";


          if($Status == "Fertigung") {
            $intoderext = "";
          }


          
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



          $lagen_ID = null;

          //Bearbeiten und Layer Daten hinzufügen
          if(!empty($_FILES)) {
            uploadSecurity("text");
            $a = readfiledata();

            $Lagen_ID = lagenAnlegen($a, $platinendb_connection);

            $bearbeiten= "UPDATE nutzen SET Nr = '$Nr',Bearbeiter_ID = $Bearbeiter[user_id],Material_ID = $row2[ID], Finanzstelle_ID = $finanz, Endkupfer = '$Endkupfer',Staerke = '$Staerke',Lagen = '$Lagen', Lagen_ID = '$Lagen_ID', Groesse = '$Groesse',Datum = '$Erstellt', $intoderext Status1 = '$Status',Testdaten = '$Testdaten',Datum1 = $Fertigung,Datum2 = $Abgeschlossen,Kommentar = '$Kommentar' WHERE ID = $id";
          }

          else {
            //Bearbeiten und Layer Daten löschen
            if(isset($_POST['layerLoeschen'])) {
              if(mysqli_real_escape_string($platinendb_connection, $_POST['layerLoeschen']) == "true") {
                $lagen_ID = "SELECT Lagen_ID FROM nutzen WHERE ID = '$id'";
                $lagen_ID = mysqli_query($platinendb_connection,$lagen_ID);
                $lagen_ID = mysqli_fetch_row($lagen_ID);
                $lagen_ID = $lagen_ID[0]; 

                $bearbeiten= "UPDATE nutzen SET Nr = '$Nr',Bearbeiter_ID = $Bearbeiter[user_id],Material_ID = $row2[ID], Finanzstelle_ID = $finanz, Endkupfer = '$Endkupfer',Staerke = '$Staerke',Lagen = '$Lagen', Lagen_ID = null, Groesse = '$Groesse',Datum = '$Erstellt', $intoderext Status1 = '$Status',Testdaten = '$Testdaten',Datum1 = $Fertigung,Datum2 = $Abgeschlossen,Kommentar = '$Kommentar' WHERE ID = $id";
              }
            }
            //Nur bearbeiten
            else {
              $bearbeiten= "UPDATE nutzen SET Nr = '$Nr',Bearbeiter_ID = $Bearbeiter[user_id],Material_ID = $row2[ID], Finanzstelle_ID = $finanz, Endkupfer = '$Endkupfer',Staerke = '$Staerke',Lagen = '$Lagen',Groesse = '$Groesse',Datum = '$Erstellt', $intoderext Status1 = '$Status',Testdaten = '$Testdaten',Datum1 = $Fertigung,Datum2 = $Abgeschlossen,Kommentar = '$Kommentar' WHERE ID = $id";
            }

          }

          $ursprungStatus = "SELECT Status1 FROM nutzen WHERE ID='$id'";
          $ursprungStatus =  mysqli_query($platinendb_connection, $ursprungStatus);
          $ursprungStatus = mysqli_fetch_array($ursprungStatus);
          $ursprungStatus = $ursprungStatus['Status1'];

          $allePlaufNutzen = "SELECT Platinen_ID FROM nutzenplatinen WHERE Nutzen_ID ='$id'";
          $allePlaufNutzen = mysqli_query($platinendb_connection,$allePlaufNutzen);

          //Nutzen nur in Fertigung überführt wenn Platinen drauf sind
          if($ursprungStatus == "neu" && $Status != "neu") {
            if($allePlaufNutzen->num_rows <= 0) {
              header('Content-Type: application/json');
              //nicht mehr nötig da javascript es nicht zulässt
              //echo json_encode(array('data'=> 'keineplatineaufnutzen')); 
              die();
            }
          }

          
          mysqli_query($platinendb_connection, $bearbeiten);

          //Lagen_ID falls vorhanden löschen nachdem Nutzen auf neu gesetzt wurde
          if($lagen_ID != null) {
            $loeschen2 = "DELETE FROM nutzenlagen WHERE id=$lagen_ID";
            mysqli_query($platinendb_connection, $loeschen2);
          }

          //Wenn Nutzen von Fertigung in abgeschlossen überführt wurde soll
          if($ursprungStatus == "Fertigung" && $Status == "abgeschlossen") {      
            foreach ($allePlaufNutzen as $row) {
              $pl = $row['Platinen_ID'];
              deleteDownload($pl, $platinendb_connection);
            }

          }

          $sicherheit->checkQuery($platinendb_connection);

          
          mysqli_close($platinendb_connection); 
          
          mysqli_close($login_connection);  
      

  
    
    
}
    
else {
  header('Content-Type: application/json');
  echo json_encode(array('data'=> "fehlerhaft"));
}

?>