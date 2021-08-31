<?php

class Sicherheit {

    private $bestanden;
    private $aktion;
    private $von;
    private $login;
    private $login_connection;
    private $platinendb_connection;

    //Konstruktor
    public function __construct($aktion, $von, $login, $login_connection, $platinendb_connection){

        $this->bestanden = false;
        $this->aktion = $aktion;
        $this->von = $von;
        $this->login = $login;
        $this->login_connection = $login_connection;
        $this->platinendb_connection = $platinendb_connection;

        if($von == "platine") {
            $this->platine();
        }
        else {
            $this->nutzen();
        }
    }





    public function ergebnis(){
        return $this->bestanden;
    } 


    public function checkQuery($connection) {
        if (mysqli_error($connection))
        {
          header('Content-Type: application/json');
          echo json_encode(array('data'=> "dberror"));
          die();
        }
        else{
          header('Content-Type: application/json');
          echo json_encode(array('data'=> "erfolgreich"));
          die();
        }
    }




    private function nutzen(){   

        $fromajax = $this->fromJavascript();
        $eingeloggt = $this->login->isUserLoggedIn();
        $est = isUserEst($this->login_connection);
        $parameter = $this->parameterNu($this->aktion); 
 
 
        if($eingeloggt == false) {
         die (header("location: ../index.php"));
        }
 
        if($est == false) {
         die (header("location: ../platinenindex.php"));
        }

        if($fromajax == false) {
            die (header("location: ../nutzenindex.php"));
        }
 
 
         if($this->aktion == "nutzen" || $this->aktion == "modaleinfuegen") {
            $this->bestanden = true;
         }

 
         elseif($this->aktion == "modalbearbeiten") {
                 $existens = existens($this->platinendb_connection);
 
                 if ($existens == false) {
                     
                 }
                 elseif ($parameter == false) {
 
                 }
                 else{
                     $this->bestanden = true;
                 }
             }
 
 
 
         elseif($this->aktion == "einfuegen") {
                 if ($parameter == false) {
                 }
                 else{
                     $this->bestanden = true;
                 }
         }


         elseif($this->aktion == "bearbeiter") {
            $this->bestanden = true;
         }

 
         elseif($this->aktion == "bearbeiten") {
                 $existens = existens($this->platinendb_connection);
 
                 if ($existens == false) {
                 
                 }
                 elseif ($parameter == false) {
                         
                 }
                 elseif(veraenderbarNutzen($this->platinendb_connection) == false) {
                    header('Content-Type: application/json');
                    echo json_encode(array('data'=> "nichtveraenderbar"));
                    die();
                }
 
                 else{
                     $this->bestanden = true;
                 }
         }
 
         elseif($this->aktion == "detail") {
                 $existens = existens($this->platinendb_connection);
 
                 if ($existens == false) {
 
                 }
                 else{
                     $this->bestanden = true;
                 }
             }
 
             elseif($this->aktion == "loeschen") {
                 $existens = existens($this->platinendb_connection);
 
                 if ($existens == false) {
 
                 }
                 else{
                     $this->bestanden = true;
                 }
             }
 
         
 
     }
 
 
 
 
 
 
 
 
     private function parameterNu($aktion) {
         if($this->aktion == "modalbearbeiten") {
            if (isset($_POST['Id']) && isset($_POST['Nr']) && isset($_POST['Bearbeiter']) && isset($_POST['Status']) && isset($_POST['Material']) && isset($_POST['Erstellt']) && isset($_POST['Fertigung']) && isset($_POST['Abgeschlossen'])&& isset($_POST['Groesse']) && isset($_POST['Int']) && isset($_POST['Testdaten']) && isset($_POST['Kommentar'])) { 
                return true;
             }
             else {
                 return false;
             }
         }
         elseif($this->aktion == "einfuegen") {
            if (isset($_POST['Nr']) && isset($_POST['Bearbeiter']) && isset($_POST['Status']) && isset($_POST['Material']) && isset($_POST['Erstellt'])  && isset($_POST['Groesse']) && isset($_POST['Int']) && isset($_POST['Kommentar'])){
                return true;
             }
             else {
                 return false;
             }
         }
         elseif($this->aktion == "bearbeiten") {
            if (isset($_POST['Nr']) && isset($_POST['Bearbeiter']) && isset($_POST['Status']) && isset($_POST['Material']) && isset($_POST['Erstellt'])  && isset($_POST['Groesse']) && isset($_POST['Int']) && isset($_POST['Kommentar'])){
                return true;
             }
             else {
                 return false;
             }
         }
     }














































    private function platine(){   

       $fromajax = $this->fromJavascript();
       $eingeloggt = $this->login->isUserLoggedIn();
       $parameter = $this->parameterPl($this->aktion); 


       if($eingeloggt == false) {
        die (header("location: ../index.php"));
       }

       if($fromajax == false) {
        die (header("location: ../platinenindex.php"));
       }


        if($this->aktion == "platinen" || $this->aktion == "modaleinfuegen") {
            $this->bestanden = true;
        }

        elseif($this->aktion == "modalbearbeiten") {
                $existens = existens($this->platinendb_connection);

                if ($existens == false) {
                    
                }
                elseif ($parameter == false) {

                }
                else{
                    $this->bestanden = true;
                }
            }



        elseif($this->aktion == "einfuegen") {
                if ($parameter == false) {
                }
                else{
                    $this->bestanden = true;
                }
        }

        elseif($this->aktion == "auftraggeber") {
            $this->bestanden = true;
         }

        elseif($this->aktion == "bearbeiten") {
                $existens = existens($this->platinendb_connection);
                $veraenderbar = veraenderbarPlatine($this->platinendb_connection);

                if ($existens == false) {
                
                }
                elseif ($parameter == false) {
                        
                }
                elseif(legitimierung($this->login_connection) == false) {

                }
                elseif($veraenderbar[0] == false) {
                        header('Content-Type: application/json');
                        echo json_encode(array('data'=> $veraenderbar[1]));
                        die();
                }

                else{
                    $this->bestanden = true;
                }
        }

        elseif($this->aktion == "detail") {
                $existens = existens($this->platinendb_connection);

                if ($existens == false) {

                }
                elseif(legitimierung ($this->login_connection) == false) {

                }
                else{
                    $this->bestanden = true;
                }
            }

            elseif($this->aktion == "loeschen") {
                $existens = existens($this->platinendb_connection);
                $veraenderbar = veraenderbarPlatine($this->platinendb_connection);

                if ($existens == false) {

                }
                elseif(legitimierung ($this->login_connection) == false) {

                }
                elseif($veraenderbar[0] == false) {
                    header('Content-Type: application/json');
                    echo json_encode(array('data'=> $veraenderbar[1]));
                    die();
                }
                else{
                    $this->bestanden = true;
                }
            }

        

    }

















    private function fromJavascript() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
        {
            return true;
        }
        else {
            return false;
        }
        
        
    }


    private function parameterPl($aktion) {
        if($this->aktion == "modalbearbeiten") {
            if (isset($_POST['Id']) && isset($_POST['Leiterkartenname']) && isset($_POST['Anzahl']) && isset($_POST['Material']) && isset($_POST['Groesse']) && isset($_POST['Endkupfer']) && isset($_POST['Staerke']) && isset($_POST['Lagen'])&& isset($_POST['Oberflaeche']) && isset($_POST['Loetstopp']) && isset($_POST['Wunschdatum']) && isset($_POST['Kommentar'])) { 
                return true;
            }
            else {
                return false;
            }
        }
        elseif($this->aktion == "einfuegen") {
            if (isset($_POST['Name']) && isset($_POST['Anzahl']) && isset($_POST['Material']) && isset($_POST['Groesse'])  && isset($_POST['Endkupfer']) && isset($_POST['Staerke']) && isset($_POST['Lagen']) && isset($_POST['Oberflaeche']) && isset($_POST['Loetstopp']) && isset($_POST['Wunschdatum']) && isset($_POST['Kommentar']) ){
                return true;
            }
            else {
                return false;
            }
        }
        elseif($this->aktion == "bearbeiten") {
            if (isset($_POST['Name']) && isset($_POST['Anzahl']) && isset($_POST['Material']) && isset($_POST['Groeße']) && isset($_POST['Endkupfer']) && isset($_POST['Staerke']) && isset($_POST['Lagen']) && isset($_POST['Oberflaeche']) && isset($_POST['Loetstopp']) && isset($_POST['Wunschdatum']) && isset($_POST['Kommentar']) ) { 
                return true;
            }
            else {
                return false;
            }
        }
    }


}