
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    
    
<link href="plugins/fontawesome-free-5.15.1-web/css/all.css" rel="stylesheet">
<link href="plugins/gijgo1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css">


<link rel="stylesheet" type="text/css" href="styles/modal.css">

<!-- script für hinzufügen bzw bearbeiten -->
<script src="javascript/einfuegen!bearbeiten.js"></script>


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

if($aktion == "modaleinfuegen") {
  echo'
  <script src="javascript/auftraggeber!bearbeiter.js"></script>
  ';
}

$von = "nutzen";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();
 

if($bestanden == true && ($aktion == "modaleinfuegen" || $aktion == "modalbearbeiten")) {


          /*
          Bearbeiter vorbereitung
          */

          $bearbeiter = "SELECT user_name FROM users WHERE admin = '1' ORDER BY user_name asc"; 
          $bearbeiterabfrage = mysqli_query($login_connection, $bearbeiter);  

          $option = '';

          while($row2 = mysqli_fetch_assoc($bearbeiterabfrage))
          {
            $option .= '<option value = "'.$row2['user_name'].'">'.$row2['user_name'].'</option>';
          }



          /*
          Material vorbereiten
          */

          $material = 'SELECT Name FROM material';

          $abfragematerial = mysqli_query($platinendb_connection, $material);

          $option2 = '';

          while($row2 = mysqli_fetch_assoc($abfragematerial))
          {
            $option2 .= '<option value = "'.$row2['Name'].'">'.$row2['Name'].'</option>';
          }




          /*
          größte Nutzen Nummer vorbereiten
          */
          $maxnr = 'SELECT Max(Nr) as Nr From platinendb.nutzen';
          $abfragemaxnr =  mysqli_query($platinendb_connection, $maxnr);
          $abfragemaxnr2 = mysqli_fetch_assoc($abfragemaxnr);
          $nrmax = $abfragemaxnr2['Nr'];
          $nr = $nrmax +1;


          //gucken ob eingefügt oder bearbeitet werden soll. Wenn kein POST übergeben wurde, dann einfügen
          if ($aktion == "modaleinfuegen") {


          /*
          eingabefelder
          */
          $output = ''; 

          $output .= "


          <form method='post' id='edit'>
          <div class='container-fluid'>

          <div class='divhidden'>
          <label for='usr'Status:</label>
          <input type='hidden' class='form-control' id='status' name='Status' value='neu' required>
          </div>

          <div class='divhidden'>
          <label for='usr'>erstellt:</label>
          <input type='hidden' id='datepicker' class='form-control' name='Erstellt' onkeydown='return false'/>
          </div>


          <div class='form-group'>
          <label for='usr'>Nr:</label>
          <input type='text' class='form-control' id='nr' name='Nr' value='$nr' required>
          </div>










          <label for='usr'>Bearbeiter:</label>
          <div class='input-group ipg1'>

          <select class='form-control' id='bearbeiter' name='Bearbeiter' required>
          <!-- <option value='' disabled selected>Option wählen</option> -->
          </select>

          
          </div>




          <!--
          <div class='form-group'>
          <label for='usr'>Status:</label>
          <select class='form-control' id='status' name='Status' required>
          <option>neu</option>
          <option>Fertigung</option>
          <option>abgeschlossen</option>
          </select>
          </div>
          -->


          <div class='form-group'>
          <label id ='matlbl' for='usr'>Material:</label>
          <select class='form-control' id='material' name='Material' required>
          <option value='' disabled selected>Option wählen</option>
          '$option2'
          </select>
          </div>


          <div class='form-group'>
          <label for='usr'>Endkupfer:</label>
          <select class='form-control' id='endkupfer' name='Endkupfer' required>
          <option>35µ</option>
          <option>50µ</option>
          <option>70µ</option>
          <option>spezial</option>
          </select>
          </div>


          <div class='form-group'>
          <label for='usr'>Stärke(mm):</label>
          <select class='form-control' id='staerke' name='Staerke' required>
          <option value='' disabled selected>Option wählen</option>
          <option>1,55</option>
          <option>1,0</option>
          <option>0,5</option>
          <option>0,2</option>
          <option>spezial</option>
          </select>
          </div>



          <div class='form-group'>
          <label for='usr'>Lagen:</label>
          <select class='form-control' id='lagen' name='Lagen' required>
          <option value='' disabled selected>Option wählen</option>
          <option>1</option>
          <option>2</option>
          <option>4</option>
          <option>6</option>
          </select>
          </div>



          <div class='form-group'>
          <label for='usr'>Größe:</label>
          <select class='form-control' id='groesse' name='Groesse' required>
          <option value='' disabled selected>Option wählen</option>
          <option>mittel</option>
          <option>groß</option>
          <option>spezial</option>
          <option>individuell</option>
          </select>
          </div>


          <div class='form-group'>
          <label for='usr'>int/ext:</label>
          <select class='form-control' id='int' name='Int' required>
          <option>int</option>
          <option selected=selected>ext</option>
          </select>
          </div>



          <div class='custom-control custom-checkbox form-group'>
          <input name='Testdaten' type='checkbox' class='custom-control-input' id='checkbox-2'>
          <label class='custom-control-label' for='checkbox-2' style='margin-top: 10px;margin-bottom: 10px;'>Testdaten</label>
          </div>




          <div class='form-group'>
          <label for='exampleFormControlTextarea1'>Kommentar</label>
          <textarea class='form-control' id='kommentar' rows='1' name='Kommentar'></textarea>
          </div>

          </form>


          </div>";


          echo $output;

          }




          elseif($aktion == "modalbearbeiten") {


            
          $ziel = mysqli_real_escape_string($platinendb_connection, $_POST['ziel']);




          $check = '';
          if (mysqli_real_escape_string($platinendb_connection, $_POST['Testdaten']) == 1){
            $check = "checked=''";
          }


          $plaufnu = "SELECT Finanzstelle_ID, Finanzstelle_Name, Finanzstelle_Nummer FROM platinenaufnutzen3 WHERE Nutzen_ID = $_POST[Id]";

          $plaufnu = mysqli_query($platinendb_connection, $plaufnu);

          $option3 = '';

          while($row3 = mysqli_fetch_assoc($plaufnu))
          {
            $option3 .= '<option value = "'.$row3['Finanzstelle_ID'].'">'.$row3['Finanzstelle_Name'].'_'.$row3['Finanzstelle_Nummer'].'</option>';
          }


            
          /*
          eingabefelder
          */
          $output = ''; 

          $output .= "



          <form method='post' id='edit'>
          <div class='container-fluid'>


          <div class='divid'>
          <label for='usr'ID:</label>
          <input type='hidden' class='form-control' id='id' name='Id' value='$_POST[Id]' required>
          </div>

          <div class='divid'>
          <label for='usr'ziel:</label>
          <input type='hidden' class='form-control' id='ziel' name='ziel' value='$ziel' required>
          </div>


          <div class='form-group'>
          <label for='usr'>Nr:</label>
          <input type='text' class='form-control' id='nr' name='Nr' value='$_POST[Nr]' required>
          </div>


          <div class='form-group'>
          <label for='usr'>Bearbeiter:</label>
          <select class='form-control' id='bearbeiter' name='Bearbeiter' required>
          <option style='display: none;' >$_POST[Bearbeiter]</option>
          '$option'
          </select>
          </div>









    

          <div class='form-group'>
            <label id='statuslabel' for='usr'>Status: </label>
            <div class='statusdiv statusAus'>

              <select class='form-control' id='status' name='Status' required>
              <option style='display: none;' >$_POST[Status]</option>
              <option>neu</option>
              <option>Fertigung</option>
              <option>abgeschlossen</option>
              </select>
              <div class='alert alert-warning collapse' id='warnungStatus' ></div>



              
              <div class='collapse' id='collapse3' name='File'> 
                 <div class='kupferdiv'>
                    <label class='btn btn-primary' id='uploadlabel'>
                    <input id='uploadfeld' type='file' style='opacity:0'>
                    <p id='uploadtext'>Kupferflächen(.txt)</p>
                    </label>
                    <span class='label label-info' id='upload-info' style='opacity:0'>
                    </span>
                    <i class='far fa-file-alt collapse' id='inputbild'></i>
                    <i class='fa fa-trash-alt collapse' id='delfile'></i>
                    <div class='alert alert-warning collapse' id='fehleraddlagen'></div>
                  </div>
             </div>

             <div class='collapse' id='collapse6'> 
                  <div class='finanzdiv'>
                    <label id='finanzlabel' for='usr'>Finanzstelle: </label>
                    <select class='form-control' id='finanz' name='Finanz'>
                    <option value='' disabled='' selected=''>Option wählen</option>
                    '$option3'
                    </select>
                  </div>
              </div>
              <div class='alert alert-warning collapse' id='warnungStatus' ></div>


  
              


            </div>
          </div>
         


          
          <div class='form-group'>
          <label for='usr'>erstellt:</label>
          <input id='datepicker' class='form-control' name='Erstellt' value='$_POST[Erstellt]'>
          </div>


          <div class='form-group'>
          <label for='usr'>Fertigung:</label>
          <input id='datepicker2' class='form-control' name='Fertigung' value='$_POST[Fertigung]'/>
          </div>


          <div class='form-group'>
          <button class='btn btn-secondary' id='reset-date2' onclick='return false'>
          <i class='fas fa-calendar-times' id='kalender'></i> 
          </button>
          </div>



          <div class='form-group'>
          <label for='usr'>abgeschlossen:</label>
          <input id='datepicker3' class='form-control' name='Abgeschlossen' value='$_POST[Abgeschlossen]'/>
          </div>

          <div class='form-group'>
          <button class='btn btn-secondary' id='reset-date3' onclick='return false'>
          <i class='fas fa-calendar-times' id='kalender'></i> 
          </button>
          </div>




          <div class='form-group'>
          <label for='usr'>Material:</label>
          <select class='form-control' id='material' name='Material' required>
          <option style='display: none;' >$_POST[Material]</option>
          '$option2'
          </select>
          </div>



          <div class='form-group'>
          <label for='usr'>Endkupfer:</label>
          <select class='form-control' id='endkupfer' name='Endkupfer' required>
          <option style='display: none;' >$_POST[Endkupfer]</option>
          <option>35µ</option>
          <option>50µ</option>
          <option>70µ</option>
          <option>spezial</option>
          </select>
          </div>




          <div class='form-group'>
          <label for='usr'>Stärke(mm):</label>
          <select class='form-control' id='staerke' name='Staerke' required>
          <option style='display: none;' >$_POST[Staerke]</option>
          <option>1,55</option>
          <option>1,0</option>
          <option>0,5</option>
          <option>0,2</option>
          <option>spezial</option>
          </select>
          </div>



          <div class='form-group'>
          <label id='lagenid' class='iconaus' for='usr'>Lagen: </label>
          <select class='form-control' id='lagen' name='Lagen' required>
          <option style='display: none;' >$_POST[Lagen]</option>
          <option>1</option>
          <option>2</option>
          <option>4</option>
          <option>6</option>
          </select>
          </div>
          



          <div class='form-group'>
          <label for='usr'>Größe:</label>
          <select class='form-control' id='groesse' name='Groesse' required>
          <option style='display: none;' >$_POST[Groesse]</option>
          <option>mittel</option>
          <option>groß</option>
          <option>spezial</option>
          <option>individuell</option>
          </select>
          </div>


          <div class='form-group'>
          <label id='intid' for='usr'>int/ext: </label>
          <select class='form-control' id='int' name='Int' required>
          <option style='display: none;' >$_POST[Int]</option>
          <option>int</option>
          <option>ext</option>
          </select>
          </div>




          <div class='custom-control custom-checkbox form-group'>
          <input name='Testdaten' type='checkbox' class='custom-control-input' id='checkbox-2' $check>
          <label class='custom-control-label' for='checkbox-2' style='margin-top: 10px;margin-bottom: 10px;'>Testdaten</label>
          </div>




          <div class='form-group'>
          <label for='exampleFormControlTextarea1'>Kommentar</label>
          <textarea class='form-control' id='kommentar' rows='1' name='Kommentar'>$_POST[Kommentar]</textarea>
          </div>


          </form>


          </div>

          ";


          echo $output;



          }

          mysqli_close($login_connection); 
          mysqli_close($platinendb_connection); 
}
else {
  echo'<div class="container-fluid">';
     
  echo"
  <div class='alert alert-danger'> Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.
  </div>";

  echo'</div>';  
}



?>
