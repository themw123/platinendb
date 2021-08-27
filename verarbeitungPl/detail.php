


<?php
require_once("../config/db2.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();
$link = OpenCon();
$link2 = OpenCon2();


//$aktion = "bearbeiten";
//sicherheit checks
if(!(isset($_POST['aktion']))) {
     $aktion = "";
}
else {
     $aktion = mysqli_real_escape_string($link, $_POST["aktion"]);
}
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $link, $link2);
$bestanden = $sicherheit->ergebnis();
 

if($bestanden == true) {

          
               $id = mysqli_real_escape_string($link, $_POST['Id']);

               $output = '';    
               $query = "SELECT * FROM platinenaufnutzen1 WHERE Platinen_ID = '$id'"; 
               $result = mysqli_query($link, $query);  
     
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
     
     
               while($row = $result->fetch_assoc())   
               {  
                    

                    if ($row['Status1'] == "Fertigung") {


                         $output .= '  
                    
                    
                         <tr>  
                         <td> '.$row["Nr"].'</td>
                         <td style=color:orange;> '.$row["Status1"].'</td>
                         <td> '.$row["platinenaufnutzen"].'   </td> 
                         </tr>  
                         ';     
                    
                    }    
          
                    else if ($row['Status1'] == "neu") {
                         
                         
                         $output .= '  
               
               
                         <tr>  
                         <td> '.$row["Nr"].'</td>
                         <td style=color:#005EA9;> '.$row["Status1"].'</td>
                         <td> '.$row["platinenaufnutzen"].'   </td> 
                         </tr>  
                         ';    

                    }


                    else if ($row['Status1'] == "abgeschlossen") {
                         
                         $output .= '  
               
               
                         <tr>  
                         <td> '.$row["Nr"].'</td>
                         <td style=color:green;> '.$row["Status1"].'</td>
                         <td> '.$row["platinenaufnutzen"].'   </td> 
                         </tr>  
                         '; 

                         }
          
                       }
                    }

                    else {
                         echo'<div class="container-fluid">';
               
                         echo"
                         <div class='alert alert-warning'>  Momentan befindet sich Ihre Platine auf keinem Nutzen. Dementsprechend wurden die Fertigungen Ihrer Platinen noch nicht gestartet.
                         </div>";
                    
                         echo'</div>';
                    }


                    $output .= "</table></div></div>";  
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
