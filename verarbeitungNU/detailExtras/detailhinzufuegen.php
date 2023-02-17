<?php
require_once("/documents/config/db.php");
require_once("../../classes/Login.php");
require_once("../../funktion/alle.php");
require_once("../../classes/Sicherheit.php");

$login = new Login();

$login_connection = $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//sicherheit checks
if (!(isset($_POST['aktion']))) {
     $aktion = "";
} else {
     $aktion = mysqli_real_escape_string($platinendb_connection, $_POST["aktion"]);
}
$von = "nutzen";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();



if ($bestanden == true && $aktion == "detail") {



     $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);

     //Status Namen holen
     $stmt = $platinendb_connection->prepare(
          "SELECT Status1 FROM nutzen WHERE ID = ?"
     );
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $result = $stmt->get_result();
     $row = $result->fetch_assoc();
     $status = $row['Status1'];

     if ($status != "neu") {
          die();
     }


     //Material Namen holen
     $stmt = $platinendb_connection->prepare(
          "SELECT Name FROM material WHERE ID = (SELECT Material_ID FROM nutzen WHERE ID = ?)"
     );
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $result = $stmt->get_result();
     $row = $result->fetch_assoc();
     $MaterialName = $row['Name'];


     //Endkupfer Bezeichnung holen
     $stmt = $platinendb_connection->prepare(
          "SELECT Endkupfer FROM nutzen WHERE ID = ?"
     );
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $result = $stmt->get_result();
     $row = $result->fetch_assoc();
     $EndkupferName = $row['Endkupfer'];


     //Stärke holen
     $stmt = $platinendb_connection->prepare(
          "SELECT Staerke FROM nutzen WHERE ID = ?"
     );
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $result = $stmt->get_result();
     $row = $result->fetch_assoc();
     $StaerkeZahl = $row['Staerke'];

     //Lagen holen
     $stmt = $platinendb_connection->prepare(
          "SELECT Lagen FROM nutzen WHERE ID = ?"
     );
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $result = $stmt->get_result();
     $row = $result->fetch_assoc();
     $LagenAnzahl = $row['Lagen'];



     $output = '';
     $stmt = $platinendb_connection->prepare(
          "SELECT ID, Name, user_name, erstelltam, ausstehend FROM detailplatineadd WHERE MaterialName = ? AND Endkupfer = ? AND Staerke = ? AND Lagen = ? AND (ausstehend <0 OR ausstehend >0) AND ignorieren = 0"
     );
     $stmt->bind_param("sssi", $MaterialName, $EndkupferName, $StaerkeZahl, $LagenAnzahl);
     $stmt->execute();
     $add = $stmt->get_result();


     $stmt = $platinendb_connection->prepare(
          "SELECT Platinen_ID FROM nutzenplatinen WHERE Nutzen_ID = ?"
     );
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $platinenaufnutzen = $stmt->get_result();



     $counter = 0;
     $arrayAdd = array();
     $rowArray = array();
     if ($add->num_rows > 0) {
          while ($row = $add->fetch_assoc()) {
               $rowArray[0] = $row["ID"];
               $rowArray[1] = $row["Name"];
               $rowArray[2] = $row["user_name"];
               $rowArray[3] = $row["erstelltam"];
               $rowArray[4] = $row["ausstehend"];

               $arrayAdd[$counter] = $rowArray;
               $counter = $counter + 1;
          }
     }


     $counter = 0;
     $arrayAufNutzen = array();
     if ($platinenaufnutzen->num_rows > 0) {
          while ($row = $platinenaufnutzen->fetch_assoc()) {
               $arrayAufNutzen[$counter] = $row["Platinen_ID"];
               $counter = $counter + 1;
          }
     }


     //nur platinen wählen die noch nicht auf diesen nutzen drauf sind(es geht ja um die platinen die hinzugefügt werden sollen)
     $counter = 0;
     $counter2 = 0;
     $counter3 = 0;
     $hinzufuegen = true;
     $platinenzumhinzufuegen = array();
     while ($counter < count($arrayAdd)) {
          $hinzufuegen = true;
          while ($counter2 < count($arrayAufNutzen)) {
               if ($arrayAdd[$counter][0] == $arrayAufNutzen[$counter2]) {
                    $hinzufuegen = false;
                    break;
               }
               $counter2 = $counter2 + 1;
          }

          $counter2 = 0;

          if ($hinzufuegen == true) {
               $platinenzumhinzufuegen[$counter3] = $arrayAdd[$counter];
               $counter3 = $counter3 + 1;
          }


          $counter = $counter + 1;
     }



     $zustand = $sicherheit->checkQuery2($platinendb_connection);
     mysqli_close($platinendb_connection);
     mysqli_close($login_connection);


     if (count($platinenzumhinzufuegen) > 0) {

          if ($zustand == "erfolgreich") {


               $output .= '  

                         
                         <div class="table-responsive scrollabledetail2">
               
                         <table id="tabelle3" class="table text-center table-hover border">

                         <thead class="thead-light">
                         <th>Aktion</th>
                         <th>Name</th>
                         <th>Auftraggeber</th>
                         <th>erstellt</th>
                         <th>Ausstehend</th>
                         </thead>
                              
                         <tbody>
                         ';


               $counter = count($platinenzumhinzufuegen);
               for ($i = 0; $i < $counter; $i++) {

                    $creation_time = date('d-m-Y', strtotime($platinenzumhinzufuegen[$i][3]));

                    $output .= '  

                              <tr>  

                              <td>
                              <a id= ' . $platinenzumhinzufuegen[$i][0] . '></i>
                              <i class="fas fa-plus-circle iconx" id="iconklasse5"></i>
                              <i class="fas fa-exclamation-triangle" id="iconklasse3"></i>
                              </td>

                              <td> ' . $platinenzumhinzufuegen[$i][1] . '</td>
                              <td> ' . $platinenzumhinzufuegen[$i][2] . '</td>
                              <td> ' . $creation_time . '</td> 
                              <td>' . $platinenzumhinzufuegen[$i][4] . '</td>
                              </tr>  
                              ';
               }

               $output .= "</table>

                         </div>
                         <script>
                         $('.anzahldiv').show();
                         </script>
                         ";
               echo $output;
          } else {
               echo "
                         </div>
                         <script>
                              $('.anzahldiv').hide();
                         </script>
                         <div>             
                              <div 
                                   class='alert alert-warning'> Datenbankfehler: $zustand 
                              </div>
                         </div>
                         ";
          }
     } else {
          $output .= ' 

          <div class="container-fluid warnung">
               <div class="alert alert-warning"> 
                    Es gibt keine Platinen zum hinzufügen. Bedingungen: Platinen und Nutzen mit den selben folgenden Eigenschaften: Material, Endkupfer, Stärke und Lagen. Außerdem nur Platinen die ausstehend größer oder kleiner null sind, nicht ignoriert werden sollen und noch nicht auf diesem Nutzen sind.     
               </div>
          <div>
          <script>
               $(".anzahldiv").hide();
          </script>
          
          ';
          echo $output;
     }
}
