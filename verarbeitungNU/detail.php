
<?php

require_once("../config/db.php");
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

                    $output = '';    
                    $query = "SELECT * FROM platinenaufnutzen2 WHERE ID = '$id'"; 
                    $result = mysqli_query($platinendb_connection, $query);  
               
                    if ($result->num_rows > 0) {
               
                    $output .= '  
                         
                    
          
                    <div class="container-fluid nutzenplatinen" id='.$id.'>
                    <div class="table-responsive">
          
                    <table id="tabelle2" class="table text-center table-hover border">
          
                    <thead class="thead-light">
                    <th>Aktion</th>
                    <th>Name</th>
                    <th>Auftraggeber</th>
                    <th>Anzahl auf Nutzen</th>
                    <th>alle verteilt</th>
                    </thead>
                         
                    <tbody>';
          
          
                    while($row = $result->fetch_assoc())   
                    {  
                         

                         if ($row['zustand'] == "1") {
                              $output .= '  
                         
                         
                              <tr>  
                              
                              <td>
                              <a id= '.$row["nuplid"].'></i>
                              <i class="fas fa-minus-circle iconx" id="iconklasse4"></i>
                              </td>

                              <td> '.$row["Name"].'</td>
                              <td> '.$row["user_name"].'</td>
                         
                              <td>
                              <a id= '.$row["nuplid"].'></i>  
                              <div class="input-group anzahldiv3">
                              <input value="'.$row["platinenaufnutzen"].'" type="number" min="1" class="form-control" id="anzahl3" name="Anzahl">
                              <div class="input-group-append">
                              <button id="saveanzahl" type="button" class="btn btn-primary saveanzahl"><i class="fas fa-save"></i></button>
                              </div>
                              </div>
                              </td> 

                              <td> <span class="fas fa-check check"></span></td>
                              </tr>  
                              ';     
                         
                         }
                         else {
                              $output .= '  
                         
                         
                              <tr>  

                              <td>
                              <a id= '.$row["nuplid"].'></i>
                              <i class="fas fa-minus-circle iconx" id="iconklasse4"></i>
                              </td>

                              <td> '.$row["Name"].'</td>
                              <td> '.$row["user_name"].'</td>

                              <td> 
                              <a id= '.$row["nuplid"].'></i>   
                              <div class="input-group anzahldiv3">
                              <input value="'.$row["platinenaufnutzen"].'" type="number" min="1" class="form-control" id="anzahl3" name="Anzahl">
                              <div class="input-group-append">
                              <button id="saveanzahl" type="button" class="btn btn-primary saveanzahl"><i class="fas fa-save"></i></button>
                              </div>
                              </div>
                              </td> 

                              <td> <span class="fas fa-times error"></span></td>
                              </tr>  
                              ';     
                         
                         }   
                    }

               
                    }

                    else {
                         echo'<div class="container-fluid">';
               
                         echo"
                         <div class='alert alert-warning'>  Momentan befinden sich keine Platinen auf diesem Nutzen.
                         </div>";
                    
                         echo'</div>';
                    }



               $output .= "</table>
               
                    



               </div>
               </div>";  
               echo $output;  


 }

 else {
     echo'<div class="container-fluid">';
      
     echo"
     <div class='alert alert-danger'> Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.
     </div>";
   
     echo'</div>';  
 }


 ?>
