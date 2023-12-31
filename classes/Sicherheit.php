<?php

class Sicherheit
{

    private $bestanden;
    private $aktion;
    private $von;
    private $login;
    private $login_connection;
    private $platinendb_connection;

    //Konstruktor
    public function __construct($aktion, $von, $login, $login_connection, $platinendb_connection)
    {

        $this->bestanden = false;
        $this->aktion = $aktion;
        $this->von = $von;
        $this->login = $login;
        $this->login_connection = $login_connection;
        $this->platinendb_connection = $platinendb_connection;

        if ($von == "platine") {
            $this->platine();
        } else if ($von == "nutzen") {
            $this->nutzen();
        } else {
            $this->auswertung();
        }
    }





    public function ergebnis()
    {
        return $this->bestanden;
    }


    public function checkQuery($connection)
    {
        if (mysqli_error($connection)) {
            header('Content-Type: application/json');
            echo json_encode(array('data' => 'dberror', 'error' => $connection->error));
            //die();
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('data' => "erfolgreich"));
            //die();
        }
    }

    //Rückmeldung nicht an javascript, sondern php 
    public function checkQuery2($connection)
    {
        if (mysqli_error($connection)) {
            return $connection->error;
        } else {
            return "erfolgreich";
        }
    }


    //Rückmeldung für platinen und nutzen und auswertung
    public function checkQuery3($connection)
    {
        if (mysqli_error($connection)) {
            $datax[1] = "dberror";
            $datax[2] = $connection->error;
            header('Content-Type: application/json');
            echo json_encode(array('data' => $datax));
            die();
        }
    }

    public function checkQuery4()
    {
        header('Content-Type: application/json');
        echo json_encode(array('data' => 'nichterlaubt'));
        die();
    }

    public function checkQuery5($connection, $upload)
    {
        if (mysqli_error($connection)) {
            header('Content-Type: application/json');
            echo json_encode(array('data' => 'dberror', 'error' => $connection->error));
        } else if (isset($upload) && !$upload) {
            header('Content-Type: application/json');
            echo json_encode(array('data' => 'ohneupload'));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('data' => "erfolgreich"));
        }
    }

    private function nutzen()
    {

        $fromajax = $this->fromJavascript();
        $eingeloggt = $this->login->isUserLoggedIn();
        $est = isUserAdmin($this->login_connection);


        if ($eingeloggt == false) {
            die(header("location: ../index.php"));
        }

        if ($est == false) {
            die(header("location: ../platinenindex.php"));
        }

        if ($fromajax == false) {
            die(header("location: ../nutzenindex.php"));
        }


        if ($this->aktion == "nutzen" || $this->aktion == "modaleinfuegen" || $this->aktion == "einfuegen" || $this->aktion == "bearbeiter") {
            $this->bestanden = true;
        } elseif ($this->aktion == "modalbearbeiten") {
            $existens = existens($this->platinendb_connection);

            if ($existens == false) {
            } else {
                $this->bestanden = true;
            }
        } elseif ($this->aktion == "bearbeiten") {
            $existens = existens($this->platinendb_connection);

            if ($existens == false) {
            } elseif (veraenderbarNutzen($this->platinendb_connection) == false) {
                header('Content-Type: application/json');
                echo json_encode(array('data' => "nichtveraenderbar"));
                die();
            } else {
                $this->bestanden = true;
            }
        } elseif ($this->aktion == "detail") {
            $existens = existens($this->platinendb_connection);

            if ($existens == false) {
            } else {
                $this->bestanden = true;
            }
        } elseif ($this->aktion == "loeschen") {
            $existens = existens($this->platinendb_connection);

            if ($existens == false) {
            } else {
                $this->bestanden = true;
            }
        }
    }



















    private function fromJavascript()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }












    private function platine()
    {

        $fromajax = $this->fromJavascript();
        $eingeloggt = $this->login->isUserLoggedIn();


        if ($eingeloggt == false) {
            die(header("location: ../index.php"));
        }

        if ($fromajax == false) {
            die(header("location: ../platinenindex.php"));
        }


        if ($this->aktion == "platinen" || $this->aktion == "modaleinfuegen" || $this->aktion == "finanzGet" || $this->aktion == "einfuegen") {
            $this->bestanden = true;
        } elseif ($this->aktion == "lehrstuhl" || $this->aktion == "finanz" || $this->aktion == "auftraggeber") {
            $admin = isUserAdmin($this->login_connection);

            if ($admin) {
                $this->bestanden = true;
            }
        } elseif ($this->aktion == "modalbearbeiten") {
            $existens = existens($this->platinendb_connection);

            if ($existens == false) {
            } else {
                $this->bestanden = true;
            }
        } elseif ($this->aktion == "bearbeiten") {
            $existens = existens($this->platinendb_connection);
            $veraenderbar = veraenderbarPlatine($this->platinendb_connection);

            if ($existens == false) {
            } elseif (legitimierung($this->login_connection) == false) {
            } elseif ($veraenderbar[0] == false) {
                header('Content-Type: application/json');
                echo json_encode(array('data' => $veraenderbar[1]));
                die();
            } else {
                $this->bestanden = true;
            }
        } elseif ($this->aktion == "detail") {
            $existens = existens($this->platinendb_connection);

            if ($existens == false) {
            } elseif (legitimierung($this->login_connection) == false) {
            } else {
                $this->bestanden = true;
            }
        } elseif ($this->aktion == "loeschen") {
            $existens = existens($this->platinendb_connection);
            $veraenderbar = veraenderbarPlatine($this->platinendb_connection);

            if ($existens == false) {
            } elseif (legitimierung($this->login_connection) == false) {
            } elseif ($veraenderbar[0] == false) {
                header('Content-Type: application/json');
                echo json_encode(array('data' => $veraenderbar[1]));
                die();
            } else {
                $this->bestanden = true;
            }
        } elseif ($this->aktion == "download") {
            $legitim = legitimierungDownload($this->platinendb_connection, $this->login_connection);

            if ($legitim == true) {
                $this->bestanden = true;
            }
        }
    }








    private function auswertung()
    {

        $fromajax = $this->fromJavascript();
        $eingeloggt = $this->login->isUserLoggedIn();
        $est = isUserAdmin($this->login_connection);


        if ($eingeloggt == false) {
            die(header("location: ../index.php"));
        }

        if ($est == false) {
            die(header("location: ../platinenindex.php"));
        }

        if ($fromajax == false) {
            die(header("location: ../auswertungindex.php"));
        } else {
            $this->bestanden = true;
        }
    }
}
