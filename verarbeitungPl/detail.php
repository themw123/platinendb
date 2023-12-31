


<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../utils/util.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

$login_connection = $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//$aktion = "bearbeiten";
//sicherheit checks
if (!(isset($_POST['aktion']))) {
     $aktion = "";
} else {
     $aktion = mysqli_real_escape_string($platinendb_connection, $_POST["aktion"]);
}
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if ($bestanden == true && $aktion == "detail") {


     $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);

     $output = '';
     $stmt = $platinendb_connection->prepare(
          "SELECT * FROM platinenaufnutzen1 WHERE Platinen_ID = ?"
     );
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $result = $stmt->get_result();


     $zustand = $sicherheit->checkQuery2($platinendb_connection);
     mysqli_close($platinendb_connection);
     mysqli_close($login_connection);


     if ($zustand == "erfolgreich") {



          if ($result->num_rows > 0) {

               $output .= '  
                         
                    
          
                    <div class="container-fluid>
                    <div class="table-responsive">
          
                    <table id="tabelle2" class="table text-center table-hover border">
          
                    <thead class="thead-light">
                    <th>Nutzen</th>
                    <th>Status</th>
                    <th>Anzahl auf Nutzen</th>
                    </thead>
                         
                    <tbody>';


               while ($row = $result->fetch_assoc()) {


                    if ($row['Status1'] == "Fertigung") {


                         $output .= '  
                         
                         
                              <tr>  
                              <td> ' . $row["Nr"] . '</td>
                              <td style=color:orange;> ' . $row["Status1"] . '</td>
                              <td> ' . $row["platinenaufnutzen"] . '   </td> 
                              </tr>  
                              ';
                    } else if ($row['Status1'] == "neu") {


                         $output .= '  
                    
                    
                              <tr>  
                              <td> ' . $row["Nr"] . '</td>
                              <td style=color:#005EA9;> ' . $row["Status1"] . '</td>
                              <td> ' . $row["platinenaufnutzen"] . '   </td> 
                              </tr>  
                              ';
                    } else if ($row['Status1'] == "abgeschlossen") {

                         $output .= '  
                    
                    
                              <tr>  
                              <td> ' . $row["Nr"] . '</td>
                              <td style=color:green;> ' . $row["Status1"] . '</td>
                              <td> ' . $row["platinenaufnutzen"] . '   </td> 
                              </tr>  
                              ';
                    }
               }
          } else {
               echo '
                         <div class="container-fluid">
                              <div class="alert alert-warning">  
                                   Momentan befindet sich die Platine auf keinem Nutzen. Dementsprechend wurden die Fertigungen der Platinen noch nicht gestartet.
                              </div>
                         </div>
                         ';
          }




          $output .= "</table></div></div>";
          echo $output;
     } else {
          echo '
          <div class="container-fluid">
               <div class="alert alert-warning"> Datenbankfehler: ' . $zustand . '
          </div>
          </div>
          ';
     }
} else {
     echo '
     <div class="container-fluid">
          <div class="alert alert-danger"> 
               Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.
          </div>
     </div>
     ';
}

?>
