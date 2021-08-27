
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    
    
<link href="plugins/fontawesome-free-5.15.1-web/css/all.css" rel="stylesheet">
<link href="plugins/gijgo1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css">


<link rel="stylesheet" type="text/css" href="styles/modal.css">

<!-- script für hinzufügen bzw bearbeiten -->
<script src="javascript/einfuegen!bearbeiten.js"></script>


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


if($aktion == "modaleinfuegen") {
  echo'
  <script src="javascript/auftraggeber!bearbeiter.js"></script>
  ';
}

$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $link, $link2);
$bestanden = $sicherheit->ergebnis();

if($bestanden == true) {

        /*
        Auftraggeber vorbereitung
        */
        if(isUserEst($link) == true) {

          $auftraggeber = 'SELECT user_name FROM users';
          
          $auftraggeberabfrage = mysqli_query($link2, $auftraggeber);

          $option = '';

          while($row2 = mysqli_fetch_assoc($auftraggeberabfrage))
          {
            $option .= '<option value = "'.$row2['user_name'].'">'.$row2['user_name'].'</option>';
          }

        }


        /*
        Material vorbereitung
        */

        $material = 'SELECT Name FROM material';

        $abfragematerial = mysqli_query($link, $material);

        $option2 = '';

        while($row2 = mysqli_fetch_assoc($abfragematerial))
        {
          $option2 .= '<option value = "'.$row2['Name'].'">'.$row2['Name'].'</option>';
        }



        /*
        auftraggeber vorbereitung
        */
        $auftraggeber = $_SESSION['user_name'];




        //gucken ob eingefügt oder bearbeitet werden soll. Wenn kein POST übergeben wurde, dann einfügen
        if ($aktion == "modaleinfuegen") {


        /*
        eingabefelder
        */
        $output = ''; 

        $output .= "


        <form method='post' id='edit'>
        <div class='container-fluid'>


        <!--
        <div class='divhidden'>
        <label for='usr'>Auftraggeber:</label>
        <input type='hidden' class='form-control' id='auftraggeber' name='Auftraggeber' value='$auftraggeber' required>
        </div>
        -->



        <div class='form-group'>
        <label for='usr'>Name:</label>
        <input type='text' class='form-control' id='name' name='Name' required>
        </div>
        ";

        if(isUserEst($link) == true) {
          $output .= "
          <label for='usr'>Auftraggeber:</label>
          <div class='input-group ipg1'>

          <select class='form-control' id='auftraggeber' name='Auftraggeber' required>
          <!-- <option value='' disabled selected>Option wählen</option> -->

          </select>
          <div class='input-group-append'>
          <button data-toggle='collapse' data-target='#collapse3' class='btn btn-primary bearbeiterbutton' type='button'><i id='bearbeiterbutton' class='far fa-caret-square-up'></i></button>
          </div>

          </div>

          <div class='collapse' id='collapse3'>  
          <div class='auftraggeberdiv'>
          <form>
            <div class='form-group test'>
            <input type='email' class='form-control' id='addBenutzer' aria-describedby='BenutzerHelp' placeholder='Auftraggebername'> 
            <button class='btn btn-primary' id='add' type='button'>hinzufügen</button>
            <button class='btn btn-primary' id='rem' type='button'>Auswahl löschen</button>
            <div class='alert alert-warning collapse' id='fehleraddbenutzer'></div>
            </div>
          </form>
          </div>
          </div>
          ";
          echo '<script> $("#anz").css({"margin-top":"8px"});</script>';
        }





        $output .= "

        <div class='form-group'>
        <label id= 'anz' for='usr'>Anzahl:</label>
        <input type='number' min='1' class='form-control' id='anzahl' name='Anzahl' required>
        </div>


        <div class='form-group'>
        <label for='usr'>Material:</label>
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
        
        <!-- <option value='' disabled selected>Option wählen</option> -->
        <option>1</option>
        <option selected>2</option>
        <option>4</option>
        <option>6</option>
        </select>
        </div>




        <div class='form-group'>
        <label for='usr'>Größe(mm x mm):</label>
        <input type='text' value='-' class='form-control' id='groeße' name='Groesse' placeholder='z.B. 20x20' required>
        </div>


        <div class='form-group'>
        <label for='usr'>Oberfläche:</label>
        <select class='form-control' id='oberflaeche' name='Oberflaeche' required>
        <option>Zinn</option>
        <option>Gold</option>
        <option>Kupfer(anti-ox)</option>
        <option>egal</option>
        <option>keine</option>
        </select>
        </div>

        <div class='form-group'>
        <label for='usr'>Lötstopp:</label>
        <select class='form-control' id='loetstopp' name='Loetstopp' required>
        <option value='' disabled selected>Option wählen</option>
        <option>ja</option>
        <option>nein</option>
        <option>nur oben</option>
        <option>nur unten</option>
        </select>
        </div>

        <div class='wunschdatumdiv'>
        <label for='usr'>Wunschdatum:</label>
        <input id='datepicker' class='form-control' name='Wunschdatum' onkeydown='return false'/>
        </div>

        <div class='form-group'>
        <button class='btn btn-secondary' id='reset-date' onclick='return false;'>
        <i class='fas fa-calendar-times' id='kalender'></i> 
        </button>
        </div>


        <div class='form-group'>
        <label for='exampleFormControlTextarea1'>Kommentar</label>
        <textarea class='form-control' id='kommentar' rows='1' name='Kommentar'></textarea>
        </div>



        <div class='buttonklasse'>
        <button type='submit' class='btn btn-primary' id='button8' name='fertig'>Fertig</button>
        </div>
        </form>


        </div>";


        echo $output;

        }





        elseif($aktion == "modalbearbeiten") {




            $ziel = $_POST['ziel'];



            $check = '';
            if ($_POST['Ignorieren'] == 1){
              $check = "checked=''";
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
            <label for='usr'>Name:</label>
            <input type='text' class='form-control' id='name' name='Name' value='$_POST[Leiterkartenname]' required>
            </div>

            ";


            if(isUserEst($link) == true) {
              $output .= "
              <div class='form-group'>
              <label for='usr'>Auftraggeber:</label>
              <select class='form-control' id='auftraggeber' name='Auftraggeber' required>
              <option style='display: none;' >$_POST[Auftraggeber]</option>
              '$option'
              </select>
              </div>
              ";
            }


            $output .= "
            <div class='form-group'>
            <label for='usr'>Anzahl:</label>
            <input type='number' min='1' class='form-control' id='anzahl' name='Anzahl' value='$_POST[Anzahl]' required>
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
            <label for='usr'>Lagen:</label>
            <select class='form-control' id='lagen' name='Lagen' required>
            <option style='display: none;' >$_POST[Lagen]</option>
            <option>1</option>
            <option>2</option>
            <option>4</option>
            <option>6</option>
            </select>
            </div>


            
            <div class='form-group'>
            <label for='usr'>Größe(mm x mm):</label>
            <input type='text' class='form-control' id='groeße' name='Groeße' placeholder='z.B. 20x20' value='$_POST[Groesse]' required>
            </div>


            <div class='form-group'>
            <label for='usr'>Oberfläche:</label>
            <select class='form-control' id='oberflaeche' name='Oberflaeche' required>
            <option style='display: none;' >$_POST[Oberflaeche]</option>
            <option>Zinn</option>
            <option>Gold</option>
            <option>Kupfer(anti-ox)</option>
            <option>egal</option>
            <option>keine</option>
            </select>
            </div>


            <div class='form-group'>
            <label for='usr'>Lötstopp:</label>
            <select class='form-control' id='loetstopp' name='Loetstopp' required>
            <option style='display: none;' >$_POST[Loetstopp]</option>
            <option>ja</option>
            <option>nein</option>
            <option>nur oben</option>
            <option>nur unten</option>
            </select>
            </div>



            <div class='wunschdatumdiv'>
            <label for='usr'>Wunschdatum:</label>
            <input id='datepicker' class='form-control' name='Wunschdatum' value='$_POST[Wunschdatum]' onkeydown='return false'/>
            </div>

            <div class='form-group'>
            <button class='btn btn-secondary' id='reset-date' onclick='return false;'>
            <i class='fas fa-calendar-times' id='kalender'></i> 
            </button>
            </div>

            <div class='form-group'>
            <label for='exampleFormControlTextarea1'>Kommentar</label>
            <textarea class='form-control' id='kommentar' rows='1' name='Kommentar'>$_POST[Kommentar]</textarea>
            </div>

            ";
            
            
            if(isUserEst($link)) {
              $output .= "
              <div class='custom-control custom-checkbox form-group'>
              <input name='Ignorieren' type='checkbox' class='custom-control-input' id='checkbox-2' $check>
              <label class='custom-control-label' for='checkbox-2' style='margin-top: 10px;margin-bottom: 10px;'>ignorieren</label>
              </div>
              ";
            }
            

            $output .= "
            <div class='buttonklasse'>
            <button type='submit' class='btn btn-primary' id='button8' name='insert' value='Insert'>Fertig</button>
            </div>
            </form>


            </div>

            ";


            echo $output;




            }

}

else {
     echo'<div class="container-fluid">';
           
     echo"
     <div class='alert alert-danger'> Es ist ein Fehler im Zusammenhang mit der Sicherheit aufgetreten.
     </div>";
      
     echo'</div>';  
}
      





?>

