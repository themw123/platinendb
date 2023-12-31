
<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../utils/util.php");
require_once("../classes/Sicherheit.php");

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

     $output = '';

     $stmt = $platinendb_connection->prepare(
          "SELECT * FROM platinenaufnutzen2 WHERE ID = ?"
     );
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $result = $stmt->get_result();

     $zustand = $sicherheit->checkQuery2($platinendb_connection);












     $zudstandNeu = zustandNeu($platinendb_connection, $id);
     if (!$zudstandNeu) {
          echo '
                         <div class="container-fluid">
                              <div class="alert alert-warning container-fluid">
                                   Nutzen nicht im Zustand neu. Deshalb kann bezüglich Platinen nichts auf diesem Nutzen geändert werden.
                              </div>
                              <script>$(".hinzufuegen").hide();</script>
                         </div>
                         ';
     } else {
          echo '<script>$(".hinzufuegen").show();</script>';
     }


     if ($zustand == "erfolgreich") {



          if ($result->num_rows > 0) {

               $output .= '  
                                   
                              
                    
                              <div class="container-fluid nutzenplatinen" id=' . $id . '>
                              <div class="table-responsive scrollabledetail1">
                    
                              <table id="tabelle2" class="table text-center table-hover border">
                    
                              <thead class="thead-light">
                              ';

               if ($zudstandNeu) {
                    $output .= '  
                                   <th>Aktion</th>
                                   ';
               }

               $output .= '
                              <th>Name</th>        
                              <th>Auftraggeber</th>
                              <th>Anzahl auf Nutzen</th>
                              <th>alle verteilt</th>
                              </thead>
                                   
                              <tbody>';



               while ($row = $result->fetch_assoc()) {
                    $output .= '  
                                   <tr>  
                                   ';

                    if ($zudstandNeu) {
                         $output .= '  
                                        <td>
                                        <a id= ' . $row["nuplid"] . '></i>
                                        <i class="fas fa-minus-circle iconx" id="iconklasse6"></i>
                                        </td>
                                        ';
                    }
                    $output .= '  
                                   <td> ' . $row["Name"] . '</td>
                                   <td> ' . $row["user_name"] . '</td>
                                   ';

                    if ($zudstandNeu) {
                         $output .= '  
                                        <td> 
                                        <a id= ' . $row["nuplid"] . '></i>   
                                        <div class="input-group anzahldiv3">
                                        <input value="' . $row["platinenaufnutzen"] . '" type="number" min="1" class="form-control" id="anzahl3" name="Anzahl">
                                        <div class="input-group-append">
                                        <button id="saveanzahl" type="button" class="btn btn-primary saveanzahl"><i class="fas fa-save"></i></button>
                                        </div>
                                        </div>
                                        </td> 
                                        ';
                    } else {
                         $output .= '  
                                        <td> ' . $row["platinenaufnutzen"] . '</td>
                                        ';
                    }

                    if ($row['zustand'] == "1") {
                         $output .= '  
                                        <td> <span class="fas fa-check check"></span></td>
                                        ';
                    } else {
                         $output .= ' 
                                        <td> <span class="fas fa-times error"></span></td>
                                        ';
                    }

                    $output .= '  
                                   </tr>  
                                   ';
               }
          } else {
               echo '
          
               <div class="container-fluid">
                    <div class="alert alert-warning">  
                         Momentan befinden sich keine Platinen auf diesem Nutzen.
                    </div>
               </div>
               ';
          }



          $output .= "</table>
                    
                         

          </div>
          </div>";
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
     </div>";
     </div>
     ';
}

mysqli_close($platinendb_connection);
mysqli_close($login_connection);

?>
