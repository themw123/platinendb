
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


//if($aktion == "modaleinfuegen") {
  echo'
  <script src="javascript/auftraggeber!bearbeiter.js"></script>
  <script src="javascript/lehrstuhl.js"></script>
  <script src="javascript/finanzstelle.js"></script>

  ';
//}

$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();

if($bestanden == true && ($aktion == "modaleinfuegen" || $aktion == "modalbearbeiten")) {

        $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);

        /*
        Auftraggeber vorbereitung
        */



        /*
        Material vorbereitung
        */

        $material = 'SELECT Name FROM material';

        $abfragematerial = mysqli_query($platinendb_connection, $material);

        $option2 = '';

        while($row2 = mysqli_fetch_assoc($abfragematerial))
        {
          $option2 .= '<option value = "'.$row2['Name'].'">'.$row2['Name'].'</option>';
        }


        /*
        auftraggeber vorbereitung
        */
        $auftraggeber = $_SESSION['user_name'];

        if($aktion == "modaleinfuegen") {
          $auftraggeberDeufault = $_SESSION['user_name'];
        }
        else {
          $auftraggeberDeufault = $_POST['Auftraggeber'];
        }


        $auftraggeberForm .= "
        <div class='form-group'>
          <label for='usr'>Auftraggeber:</label>

          <div class='auftraggeberdiv'>

            <div class='input-group ipg1'>

            <select class='form-control $auftraggeberDeufault' data-live-search='true' id='auftraggeber' name='Auftraggeber' required>     
            </select>

            
            <div class='input-group-append'>
            <button data-toggle='collapse' data-target='#collapse3' class='btn btn-primary bearbeiterbutton' type='button'><i id='bearbeiterbutton' class='far fa-caret-square-up'></i></button>
            </div>

            </div>




            <div class='collapse' id='collapse3'>
                <button class='btn btn-primary' id='rem1' type='button'>Auftraggeber löschen</button>
                <form>
                  <div class='form-group test'>
                    <label for='usr'>Neuer Auftraggeber:</label>
                    <input type='text' class='form-control' id='addBenutzer' aria-describedby='BenutzerHelp' placeholder='Auftraggebername'> 
                    
                              <label for='usr'>zugehöriger Lehrstuhl:</label>
                              <div class='lehrstuhldiv'>
                                <div class='input-group ipg2'>
                                  <select class='form-control' id='lehrstuhl' name='user_lehrstuhl'>
                                      <option value='' selected disabled hidden>Option wählen</option>
                                  </select>   
                                  <div class='input-group-append'>
                                    <button data-toggle='collapse' data-target='#collapse4' class='btn btn-primary lehrstuhlbutton' type='button'><i id='lehrstuhlbutton' class='far fa-caret-square-up'></i></button>
                                  </div>       
                                </div>
                  
                                <div class='collapse' id='collapse4'>                             
                                    <button class='btn btn-primary' id='rem2' type='button'>Lehrstuhl löschen</button>
                                    <form>
                                      <div class='form-group test'>
                                        <label for='usr'>Neuer Lehrstuhl:</label>
                                        <input type='text' class='form-control' id='addLehrstuhl' aria-describedby='BenutzerHelp' placeholder='Lehrstuhlkürzel'> 
                                        <button class='btn btn-primary' id='add2' type='button'>Lehrstuhl hinzufügen</button>
                                      </div>
                                    </form>
                                    <div class='alert alert-warning collapse' id='fehleraddlehrstuhl'></div>
                                </div>
                            </div>                  
                    <button class='btn btn-primary' id='add1' type='button'>Auftraggeber hinzufügen</button>
                  </div>

                </form>
                <div class='alert alert-warning collapse' id='fehleraddbenutzer'></div>
            </div>


          </div>
        </div>




        ";



        
        /*
        Finanzstelle vorbereitung
        */
        $auftraggeber = $_SESSION['user_name'];

        if($aktion == "modaleinfuegen") {
          $finanzDeufault = "no";
        }
        else {
          $finanzDeufault = $_POST['Finanzstelle'];
        }


        $finanzForm .= "
        <div class='form-group'>
          <label for='usr'>Finanzstelle:</label>

          <div class='finanzdiv'>

            <div class='input-group ipg1'>

            <select class='form-control $finanzDeufault' data-live-search='true' id='finanz' name='Finanz' required>     
            </select>

            
            <div class='input-group-append'>
            <button data-toggle='collapse' data-target='#collapse5' class='btn btn-primary finanzbutton' type='button'><i id='finanzbutton' class='far fa-caret-square-up'></i></button>
            </div>

            </div>




            <div class='collapse' id='collapse5'>
                <button class='btn btn-primary' id='rem3' type='button'>Finanzstelle löschen</button>
                <form>
                  <div class='form-group test'>
                    
                    <label for='usr'>Neue Finanzstelle:</label>
                    <input type='text' class='form-control' id='addFinanz1' aria-describedby='FinanzHelp' placeholder='Name'>                   

                    <input type='text' maxlength='10'  minlength='10' class='form-control' id='addFinanz2' aria-describedby='FinanzHelp' placeholder='Nummer(10-Stellig)'>                   
                    <button class='btn btn-primary' id='add3' type='button'>Finanzstelle hinzufügen</button>

                  </div>

                </form>
                <div class='alert alert-warning collapse' id='fehleraddfinanz'></div>
            </div>


          </div>
        </div>

        ";

        echo '<script> $("#fin").css({"margin-top":"8px"});</script>';


        //gucken ob eingefügt oder bearbeitet werden soll. Wenn kein POST übergeben wurde, dann einfügen
        if ($aktion == "modaleinfuegen") {


        /*
        eingabefelder
        */
        $output = ''; 

        $output .= "


        <form method='post' enctype='multipart/form-data' id='edit'>
        <div class='container-fluid'>

        ";

        //if(!isUserAdmin($platinendb_connection)) {
          $output .= "
          <div class='form-group'>
          <label for='usr'>Anleitung:</label>
          <a target='_blank' href='https://homepage.ruhr-uni-bochum.de/tobias.solowjew/Share/Plakat.pdf' class='link-primary'>Designregeln</a>
          </div>
          ";
        //}

        $output .= "
        <div class='form-group'>
        <label for='usr'>Name:</label>
        <input type='text' class='form-control' id='name' name='Name' required>
        </div>
        ";
        

        if(isUserAdmin($platinendb_connection) == true) {

          $output .= $auftraggeberForm;

        }


        $output .= $finanzForm;


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
        <input id='datepicker' class='form-control' name='Wunschdatum'>
        </div>

        <div class='form-group'>
        <button class='btn btn-secondary' id='reset-date' onclick='return false;'>
        <i class='fas fa-calendar-times' id='kalender'></i> 
        </button>
        </div>


        <div class='form-group'>
        <label for='exampleFormControlTextarea1'>Kommentar:</label>
        <textarea class='form-control' id='kommentar' rows='1' name='Kommentar'></textarea>
        </div>



        <div class='form-group'>
        <p style='margin-bottom:0.5rem;'>Eagle-, Gerber- und Bohrdaten: <i class='fas fa-info-circle' id='infoicon' data-toggle='popover' title='' data-content='Die Datei muss eine rar oder zip Datei sein.'></i></p>
        <div id='inlinetext'>
        <label class='btn btn-primary' id='uploadData'>
        <input id='uploadfeld' type='file' style='opacity:0'>
        <p id='uploadDataText'>upload</p>
        </label>
        </div>
        <span class='label label-info' id='upload-info' style='opacity:0'>
        </span>
        <i class='fas fa-file-archive collapse' id='inputbild' style='opacity: 0; font-size: 16px;'></i>
        <i class='fa fa-trash-alt collapse' id='delfile'></i>
        
        <div class='alert alert-warning collapse' id='fehleraddlagen'></div>
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


            if(isUserAdmin($platinendb_connection) == true) {
              $output .= $auftraggeberForm;
            }

            $output .= $finanzForm;
            

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
            <input id='datepicker' class='form-control' name='Wunschdatum' value='$_POST[Wunschdatum]'>
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
            
            
            if(isUserAdmin($platinendb_connection)) {
              $output .= "
              <div class='custom-control custom-checkbox form-group'>
              <input name='Ignorieren' type='checkbox' class='custom-control-input' id='checkbox-2' $check>
              <label class='custom-control-label' for='checkbox-2' style='margin-top: 10px;margin-bottom: 10px;'>ignorieren</label>
              <i class='fas fa-info-circle' id='infoicon2' data-toggle='popover' title='Hinweis' data-content='Die Platine wird innerhalb ihres Zustandes(Neu, Fertigung oder Abgeschlossen) nach ganz unten angefügt. Achtung: Die Platine wird nicht mehr beim Hinzufügen einer Platine auf einen Nutzen angezeigt!'></i>
              </div>
              ";
              
              if(!isInFertigung($id, $platinendb_connection) && !isOnNutzen($id, $platinendb_connection)){
                
                $output .= "
                <div class='custom-control custom-checkbox form-group fertigungcheck'>
                <input data-toggle='collapse' data-target='#collapse4' name='Fertigung' type='checkbox' class='custom-control-input' id='checkbox-3'>
                <label class='custom-control-label' for='checkbox-3' style='margin-top: 10px;margin-bottom: 10px;'>Fertigung</label>
                <i class='fas fa-info-circle' id='infoicon3' data-toggle='popover' title='Hinweis' data-content='Die Platine wird in den Zustand Fertigung versetzt. Dafür wird ein neuer Nutzen im Zustand Fertigung erstellt und die Platine hinzugefügt.'></i>
                </div>
                ";

                /*
                erfolgt jetzt automatisch
                
                $bearbeiter = 'SELECT user_name FROM login.users';

                $bearbeiterabfrage = mysqli_query($platinendb_connection, $bearbeiter);
      
                $option = '';
      
                while($row2 = mysqli_fetch_assoc($bearbeiterabfrage))
                {
                  $option .= '<option value = "'.$row2['user_name'].'">'.$row2['user_name'].'</option>';
                }
                
                $output .= "
                <div class='custom-control custom-checkbox form-group fertigungcheck'>
                <input data-toggle='collapse' data-target='#collapse4' name='Fertigung' type='checkbox' class='custom-control-input' id='checkbox-3'>
                <label class='custom-control-label' for='checkbox-3' style='margin-top: 10px;margin-bottom: 10px;'>Fertigung</label>
                <i class='fas fa-info-circle' id='infoicon3' data-toggle='popover' title='Hinweis' data-content='Die Platine wird in den Zustand Fertigung versetzt. Dafür wird ein neuer Nutzen im Zustand Fertigung erstellt und die Platine hinzugefügt.'></i>
                </div>

                <div class='collapse' id='collapse4' name='BearbeiterColl'> 
                <div id='form-group-bearbeiter' class='form-group'>

                <label for='usr'>Bearbeiter:</label>
                <select class='form-control' id='bearbeiter' name='Bearbeiter'>
                <option value='' disabled selected>Option wählen</option>
                '$option'
                </select>
                
                </div>
                </div>
                

                ";
                */
              }
            }
            

            $output .= "
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

