


<?php 
require_once("/documents/config/db.php");
require_once("../../classes/Login.php");
require_once("../../funktion/alle.php");
require_once("../../classes/Sicherheit.php");

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

     //Material Namen holen
     $materialname = "SELECT Name FROM material WHERE ID = (SELECT Material_ID FROM nutzen WHERE ID = '$id')";
     $resultMaterialName = mysqli_query($platinendb_connection, $materialname);  
     $row = mysqli_fetch_assoc($resultMaterialName);  
     $MaterialName = $row['Name'];


     //Endkupfer Bezeichnung holen
     $endkupfername = "SELECT Endkupfer FROM nutzen WHERE ID = '$id'";
     $resultEndkupferName = mysqli_query($platinendb_connection, $endkupfername);  
     $row = mysqli_fetch_assoc($resultEndkupferName);  
     $EndkupferName = $row['Endkupfer'];


     //Stärke holen
     $staerkezahl = "SELECT Staerke FROM nutzen WHERE ID = '$id'";
     $resultstaerke = mysqli_query($platinendb_connection, $staerkezahl);  
     $row = mysqli_fetch_assoc($resultstaerke);  
     $StaerkeZahl = $row['Staerke'];


     //Lagen holen
     $lagenanzahl = "SELECT Lagen FROM nutzen WHERE ID = '$id'";
     $resultlagen = mysqli_query($platinendb_connection, $lagenanzahl);  
     $row = mysqli_fetch_assoc($resultlagen);  
     $LagenAnzahl = $row['Lagen'];



         $output = '';   
         $query = "SELECT ID, Name, user_name, erstelltam, ausstehend, ignorieren  FROM detailplatineadd WHERE MaterialName = '$MaterialName' AND Endkupfer = '$EndkupferName' AND Staerke = '$StaerkeZahl' AND Lagen = '$LagenAnzahl' AND (ausstehend <0 OR ausstehend >0) AND ignorieren = 0"; 
         $add = mysqli_query($platinendb_connection, $query);  
         //zu groß

         $query2 = "SELECT Platinen_ID FROM nutzenplatinen WHERE Nutzen_ID = '$id'"; 
         $platinenaufnutzen = mysqli_query($platinendb_connection, $query2);  


         //$einmal = mysqli_real_escape_string($link, $_POST['einmal']);
         
         
         


         $counter = 0;
         $arrayAdd = array();
         if ($add->num_rows > 0) {
               while($row = $add->fetch_assoc())  {
                    $arrayAdd[$counter] = $row["ID"];
                    $counter = $counter+1;
               }
         }
         

         $counter = 0;
         $arrayAufNutzen = array();
         if ($platinenaufnutzen->num_rows > 0) {
               while($row = $platinenaufnutzen->fetch_assoc())  {
                    $arrayAufNutzen[$counter] = $row["Platinen_ID"];
                    $counter = $counter+1;
               }
          }
        
          
          //nur platinen wählen die noch nicht auf diesen nutzen drauf sind(es geht ja um die platinen die hinzugefügt werden sollen)
          $counter = 0;
          $counter2 = 0;
          $counter3 = 0;
          $hinzufuegen = true;
          $platinenzumhinzufuegen = array();
          while($counter < count($arrayAdd)) {
               $hinzufuegen = true;
               while($counter2 < count($arrayAufNutzen)) {
                    if($arrayAdd[$counter] == $arrayAufNutzen[$counter2]) { 
                         $hinzufuegen = false;
                         break;
                    }
                    $counter2 = $counter2+1;
               }

               $counter2 = 0;

               if($hinzufuegen == true) {
                    $platinenzumhinzufuegen[$counter3] = $arrayAdd[$counter];
                    $counter3 = $counter3+1;
               }


               $counter = $counter+1;
          }






         if (count($platinenzumhinzufuegen) > 0) {

          //Where Anweisung erstellen
          $WhereAnweisung = " WHERE ID = ";
          $counter = 0;
          $warschondrin = false;
          while ($counter < count($platinenzumhinzufuegen)) {
               $idpl = $platinenzumhinzufuegen[$counter];
               if($warschondrin == false) {
                    $WhereAnweisung .= $idpl ;
                    $warschondrin = true;
               }

               else {
                    $WhereAnweisung .= " OR ID = " ;
                    $WhereAnweisung .= $idpl;
               }

               $counter = $counter+1;

          }


          //Platinen zum hinzufügen holen
          $query3 = "SELECT ID, NAME, user_name, erstelltam, ausstehend  FROM detailplatineadd $WhereAnweisung"; 
          //SEHR langsam bei vielen Platinen 
          $finalquery = mysqli_query($platinendb_connection, $query3);  

     
          $result = mysqli_query($platinendb_connection, $finalquery);
          $zustand = $sicherheit->checkQuery2($platinendb_connection);
          mysqli_close($platinendb_connection); 
          mysqli_close($login_connection);  

          if($zustand == "erfolgreich") {


                    $output .= '  

                    
                    <div class="table-responsive">
          
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


                    while($row = $finalquery->fetch_assoc())  {






                    $creation_time = date('d-m-Y', strtotime($row['erstelltam']));

                    $output .= '  

                    <tr>  

                    <td>
                    <a id= '.$row["ID"].'></i>
                    <i class="fas fa-plus-circle iconx" id="iconklasse5"></i>
                    <i class="fas fa-exclamation-triangle" id="iconklasse3"></i>
                    </td>

                    <td> '.$row["NAME"].'</td>
                    <td> '.$row["user_name"].'</td>
                    <td> '.$creation_time.'</td> 
                    <td>' .$row["ausstehend"].'</td>
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
               

               }
               else {
                    echo "
                    </div>
                    <script>
                    $('.anzahldiv').hide();
                    </script>
                    ";

                    echo'<div>';
               
                    echo"
                    <div class='alert alert-warning'> Datenbankfehler: $zustand 
                    </div>";
               
                    echo'</div>';

                }

         }

         else {
          $output .= ' 

          <div class="container-fluid warnung">
          <div class="alert alert-warning"> Es gibt keine Platinen zum hinzufügen. Bedingungen: Platinen und Nutzen mit den selben folgenden Eigenschaften: Material, Endkupfer, Stärke und Lagen. Außerdem nur Platinen die ausstehend größer oder kleiner null sind, nicht ignoriert werden sollen und noch nicht auf diesem Nutzen sind.     
          </div>

          <script>
          $(".anzahldiv").hide();
          </script>
          ';
          echo $output; 
          
     }

}


 ?>

