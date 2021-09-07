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
                if(isUserEst($this->login->getlogin_connection()) == true) {
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
                $user_standort = $this->db_connection->real_escape_string(strip_tags($_POST['user_standort'], ENT_QUOTES));

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
                    $zustand = $this->sendRegisterMail($user_name, $user_email, $user_password_hash, $user_standort);
                    //$zustand = true;
                    if($zustand) {
                        // if user has been send successfully
                        $this->messages[] = "Sobald ein Admin deinen Account bestätigt, kannst du dich einloggen. Du wirst jetzt zur Loginseite weitergeleitet";
                        header('Refresh:5; url=index.php');
                    }
                    else{

                    }

                }
            } else {
                $this->errors[] = "Es besteht keine Verbindung zur Datenbank";
            }
        } else {
            $this->errors[] = "Ein unbekannter Fehler ist aufgetreten.";
        }
    }


    private function sendRegisterMail($user_name, $user_email, $user_password_hash, $user_standort) {
        $mail = new PHPMailer;

        //damit Umlaute richtig angezeigt werden
        $mail->CharSet = 'utf-8'; 

        // please look into the config/config.php for much more info on how to use this!
        // use SMTP or use mail()
        if (EMAIL_USE_SMTP) {
            // Set mailer to use SMTP
            $mail->IsSMTP();
            //useful for debugging, shows full SMTP errors
            //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
            // Enable SMTP authentication
            $mail->SMTPAuth = EMAIL_SMTP_AUTH;
            // Enable encryption, usually SSL/TLS
            if (defined(EMAIL_SMTP_ENCRYPTION)) {
                $mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;
            }
            // Specify host server
            $mail->Host = EMAIL_SMTP_HOST;
            $mail->Username = EMAIL_SMTP_USERNAME;
            $mail->Password = EMAIL_SMTP_PASSWORD;
            $mail->Port = EMAIL_SMTP_PORT;
        } else {
            $mail->IsMail();
        }

        
        $mail->From = EMAIL_PASSWORDRESET_FROM;
        $mail->FromName = EMAIL_PASSWORDRESET_FROM_NAME;
        $mail->AddAddress(ACCOUNT_VALIDATE_TO);
        $mail->Subject = ACCOUNT_VALIDATE_SUBJECT;
        
        //$link = EMAIL_PASSWORDRESET_URL.'?user_name='.urlencode($user_name).'&verification_code='.urlencode($user_password_reset_hash);
        //$link = ACCOUNT_VALIDATE_URL;
        $link = ACCOUNT_VALIDATE_URL.'&user_name='.urlencode($user_name).'&user_email='.urlencode($user_email).'&user_password_new='.urlencode($user_password_hash).'&user_standort='.urlencode($user_standort);

        $mail->Body = ACCOUNT_VALIDATE_CONTENT . ' ' . $link;

        if(!$mail->Send()) {
            $this->errors[] = MESSAGE_PASSWORD_RESET_MAIL_FAILED . $mail->ErrorInfo;
            return false;
        } else {
            return true;
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
        } elseif (empty($_POST['user_password_new'])) {
            $this->errors[] = "Empty Password";
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
                $user_standort = $this->db_connection->real_escape_string(strip_tags($_POST['user_standort'], ENT_QUOTES));

                // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
                // PHP 5.3/5.4, by the password hashing compatibility library
                $user_password = password_hash($user_password, PASSWORD_ARGON2ID);

                // check if user or email address already exists
                $sql = "SELECT * FROM users WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_email . "';";
                $query_check_user_name = $this->db_connection->query($sql);

                if ($query_check_user_name->num_rows == 1) {
                    $this->errors[] = "Der Benutzername/E-Mail-Adresse ist bereits vergeben";
                } else {
                    // write new user's data into database
                    $sql = "INSERT INTO users (user_name, user_password_hash, user_email, intoderext)
                            VALUES('" . $user_name . "', '" . $user_password . "', '" . $user_email . "', '" . $user_standort . "');";
                    $query_new_user_insert = $this->db_connection->query($sql);

                    // if user has been added successfully
                    if ($query_new_user_insert) {
                        $this->messages[] = "Der Benutzer wurde erfolgreich angelegt. Du wirst jetzt zur Loginseite weitergeleitet";
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
}
