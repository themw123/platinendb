<?php
require_once("/documents/config/db.php");
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



if($bestanden == true && $aktion == "einfuegen") {


          /*
          Inputs auslesen Name
          */
          $Name = mysqli_real_escape_string($platinendb_connection, $_POST["Name"]);




          /*
          Inputs auslesen Auftraggeber
          */ 
          if(isUserAdmin($platinendb_connection) == true) {
            $Auftraggeber2 = mysqli_real_escape_string($login_connection, $_POST["Auftraggeber"]);
          }
          else {
            $Auftraggeber2 = mysqli_real_escape_string($platinendb_connection, $_SESSION['user_name']);
          }
          
          $Auftraggeber2query = "SELECT user_id FROM users WHERE user_name='$Auftraggeber2'"; 
          $Auftraggeber2id =  mysqli_query($login_connection, $Auftraggeber2query);
          $row = mysqli_fetch_assoc($Auftraggeber2id);



          /*
          Inputs auslesen Finanzstelle
          */ 
          $finanz = mysqli_real_escape_string($platinendb_connection, $_POST["Finanz"]);
          
          $finanz = "SELECT id FROM finanzstelle WHERE name='$finanz'"; 
          $finanz =  mysqli_query($platinendb_connection, $finanz);
          $finanz = mysqli_fetch_assoc($finanz);
          $finanz = $finanz['id'];

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
          
          if(!empty($_FILES)) {
            uploadSecurity("archive");
            $fileName = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $type = $_FILES['file']['type'];
            $file = $_FILES['file']['tmp_name'];
            $blob = addslashes(fread(fopen($file, "r"), filesize($file)));
           
            $maxid = "select max(ID)+1 as ID from downloads"; 
            $maxid = mysqli_query($platinendb_connection,$maxid);
            $maxid = mysqli_fetch_array($maxid);
            $maxid = $maxid['ID']; 

            if($maxid == null) {
              $maxid = 1;
            }

            $downloads = "INSERT INTO downloads (id, download, name, size, type) VALUES ('$maxid', '$blob', '$fileName', '$size', '$type')";

            $platinen = "INSERT INTO platinen (Name, Auftraggeber_ID, Finanzstelle_ID, Anzahl, Material_ID, Endkupfer, Staerke, Lagen, Groesse, Oberflaeche, Loetstopp, erstelltam, wunschDatum, Kommentar, Downloads_ID, ignorieren) VALUES ('$Name', '$row[user_id]', '$finanz', '$Anzahl', '$row2[ID]', '$Endkupfer', '$Staerke', '$Lagen', '$Groeße', '$Oberflaeche', '$Loetstopp', '$erstelltam', $Wunschdatum, '$Kommentar', '$maxid', '0')";

            mysqli_query($platinendb_connection, $downloads);

          }
          else {
            $platinen = "INSERT INTO platinen (Name, Auftraggeber_ID, Finanzstelle_ID, Anzahl, Material_ID, Endkupfer, Staerke, Lagen, Groesse, Oberflaeche, Loetstopp, erstelltam, wunschDatum, Kommentar, Downloads_ID, ignorieren) VALUES ('$Name', '$row[user_id]', '$finanz', '$Anzahl', '$row2[ID]', '$Endkupfer', '$Staerke', '$Lagen', '$Groeße', '$Oberflaeche', '$Loetstopp', '$erstelltam', $Wunschdatum, '$Kommentar', null, '0')";
          }
          

          mysqli_query($platinendb_connection, $platinen);

          $sicherheit->checkQuery($platinendb_connection);
          
          if(!isUserAdmin($platinendb_connection) && !mysqli_error($platinendb_connection)) {
            $art = "newPlatineNotification";
            $user_name = mysqli_real_escape_string($login_connection, $_SESSION['user_name']);
            $user_email = ACCOUNT_VALIDATE_TO;
            sendMail($art, $user_name, $user_email, "", "");
          }

          mysqli_close($platinendb_connection); 
          
          mysqli_close($login_connection); 


}


else {
  header('Content-Type: application/json');
  echo json_encode(array('data'=> "fehlerhaft"));
}


?>