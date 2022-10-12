<?php

/**
 * Class registration
 * handles the user registration
 */
class Registration
{

    private $login = null;

    /**
     * @var object $db_connection The database connection
     */
    
    private $db_connection = null;
    /**
     * @var array $errors Collection of error messages
     */
    public $errors = array();
    /**
     * @var array $messages Collection of success / neutral messages
     */
    public $messages = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$registration = new Registration();"
     */
    public function __construct()
    {

        if (isset($_POST["register"])) {
            //$this->registerNewUser();
            $this->registerNewUser();
        }
        else if(isset($_GET["Validation"]) || isset($_POST["reg"])) {
            $this->login = new Login();
            if($this->login->isUserLoggedIn() == true) {
                if(isUserAdmin($this->login->getlogin_connection()) == true) {
                    if(isset($_POST["reg"])) {
                        $this->registerNewUserEst();
                    }
                }
                else {
                    header("location: platinenindex.php");
                }
            }
            else {
                header("location: index.php?Validation"); 
            }
        }

    }


    private function registerNewUser()
    {
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Empty Username";
        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $this->errors[] = "Empty Password";
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $this->errors[] = "Die Passwörter stimmen nicht überein";
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $this->errors[] = "Passwort muss mindestens 6 Zeichen lang sein";
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $this->errors[] = "Benutzername muss zwischen 2 und 62 Zeichen lang sein";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $this->errors[] = "Benutzername stimmt nicht mit den erlaubten Zeichen überein: a-Z und 2 bis 64";
        } elseif (empty($_POST['user_email'])) {
            $this->errors[] = "E-Mail-Feld kann nicht leer sein";
        } elseif (strlen($_POST['user_email']) > 64) {
            $this->errors[] = "Email-Adresse darf nicht mehr als 62 Zeichen beinhalten";
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Deine E-Mail-Adresse entspricht nicht den erlaubten Zeichen";
        } elseif (!empty($_POST['user_name'])
            && strlen($_POST['user_name']) <= 64
            && strlen($_POST['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
            && !empty($_POST['user_email'])
            && strlen($_POST['user_email']) <= 64
            && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['user_password_new'])
            && !empty($_POST['user_password_repeat'])
            && ($_POST['user_password_new'] === $_POST['user_password_repeat'])
        ) {
            // create a database connection
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // change character set to utf8 and check it
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                // escaping, additionally removing everything that could be (html/javascript-) code
                $user_name = $this->db_connection->real_escape_string(strip_tags($_POST['user_name'], ENT_QUOTES));
                $user_email = $this->db_connection->real_escape_string(strip_tags($_POST['user_email'], ENT_QUOTES));
                $user_password = $this->db_connection->real_escape_string(strip_tags($_POST['user_password_new'], ENT_QUOTES));

                // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
                // PHP 5.3/5.4, by the password hashing compatibility library
                $user_password_hash = password_hash($user_password, PASSWORD_ARGON2ID);

                // check if user or email address already exists
                $sql = "SELECT * FROM users WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_email . "';";
                $query_check_user_name = $this->db_connection->query($sql);

                if ($query_check_user_name->num_rows == 1) {
                    $this->errors[] = "Der Benutzername/E-Mail-Adresse ist bereits vergeben";
                } else {
                    //send email with user's data
                    $art = "validation";
                    $zustand = sendMail($art, $user_name, $user_email, $user_password_hash);
                    
                    //$zustand = true;
                    if($zustand) {
                        // if user has been send successfully
                        $this->messages[] = "Du wirst jetzt zur Loginseite weitergeleitet";
                        header('Refresh:2.5; url=index.php?wait');
                    }
                    else {
                        $this->messages[] = "Registrierung fehlgeschlagen (Es konnte keine Mail an EST gesendet werden.)";
                    }

                }
            } else {
                $this->errors[] = "Es besteht keine Verbindung zur Datenbank";
            }
        } else {
            $this->errors[] = "Ein unbekannter Fehler ist aufgetreten.";
        }
    }



    /**
     * handles the entire registration process. checks all error possibilities
     * and creates a new user in the database if everything is fine
     */
    private function registerNewUserEst()
    {
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Empty Username";
        } elseif (empty($_POST['user_password'])) {
            $this->errors[] = "Empty Password";
        } elseif (strlen($_POST['user_password']) < 6) {
            $this->errors[] = "Passwort muss mindestens 6 Zeichen lang sein";
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $this->errors[] = "Benutzername muss zwischen 2 und 62 Zeichen lang sein";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $this->errors[] = "Benutzername stimmt nicht mit den erlaubten Zeichen überein: a-Z und 2 bis 64";
        } elseif (empty($_POST['user_email'])) {
            $this->errors[] = "E-Mail-Feld kann nicht leer sein";
        } elseif (strlen($_POST['user_email']) > 64) {
            $this->errors[] = "Email-Adresse darf nicht mehr als 62 Zeichen beinhalten";
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Deine E-Mail-Adresse entspricht nicht den erlaubten Zeichen";
        } elseif (!empty($_POST['user_name'])
            && strlen($_POST['user_name']) <= 64
            && strlen($_POST['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
            && !empty($_POST['user_email'])
            && strlen($_POST['user_email']) <= 64
            && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['user_password'])
        ) {
            // create a database connection
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // change character set to utf8 and check it
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                
                // escaping, additionally removing everything that could be (html/javascript-) code
                $user_name = $this->db_connection->real_escape_string(strip_tags($_POST['user_name'], ENT_QUOTES));
                $user_email = $this->db_connection->real_escape_string(strip_tags($_POST['user_email'], ENT_QUOTES));
                $user_password = $this->db_connection->real_escape_string(strip_tags($_POST['user_password'], ENT_QUOTES));
                $user_lehrstuhl = $this->db_connection->real_escape_string(strip_tags($_POST['user_lehrstuhl'], ENT_QUOTES));

                $platinendb_connection = $this->login->getplatinendb_connection();

                $user_lehrstuhl = "select id from lehrstuhl where kuerzel = '$user_lehrstuhl'";
                $user_lehrstuhl = mysqli_query($platinendb_connection,$user_lehrstuhl);
                $user_lehrstuhl = mysqli_fetch_array($user_lehrstuhl);
                $user_lehrstuhl = $user_lehrstuhl['id'];
                // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
                // PHP 5.3/5.4, by the password hashing compatibility library
                // $user_password = password_hash($user_password, PASSWORD_ARGON2ID);

                // check if user or email address already exists
                $sql = "SELECT * FROM users WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_email . "';";
                $query_check_user_name = $this->db_connection->query($sql);

                if ($query_check_user_name->num_rows == 1) {
                    $this->errors[] = "Der Benutzername/E-Mail-Adresse ist bereits vergeben";
                } else {
                    // write new user's data into database
                    $sql = "INSERT INTO users (user_name, admin, user_password_hash, user_email, lehrstuhl)
                            VALUES('" . $user_name . "', 0, '" . $user_password . "', '" . $user_email . "', '" . $user_lehrstuhl . "');";
                    $query_new_user_insert = $this->db_connection->query($sql);

                    // if user has been added successfully
                    if ($query_new_user_insert) {
                        $this->messages[] = "Der Benutzer wurde erfolgreich angelegt. Du wirst jetzt weitergeleitet.";
                        $art = "userNotification";
                        $zustand = sendMail($art, $user_name, $user_email, "", "");
                        header('Refresh:2.5; url=index.php');
                    } else {
                        $this->errors[] = "Die Registrierung ist fehlgeschlagen";
                    }
                }
            } else {
                $this->errors[] = "Es besteht keine Verbindung zur Datenbank";
            }
        } else {
            $this->errors[] = "Ein unbekannter Fehler ist aufgetreten.";
        }
    }

    public function getloginObj() {
        return $this->login;
    }

}
