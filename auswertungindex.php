<!DOCTYPE html>

<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    
    <link href="#" rel="shortcut icon" />
    
    <link href="plugins/bootstrap-4.5.3-dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="styles/platinenundnutzenindex.css" rel="stylesheet" type="text/css">
 

    
    <title>Auswertung</title>

    <?php
    /**
     * A simple, clean and secure PHP Login Script / MINIMAL VERSION
     *
     * Uses PHP SESSIONS, modern password-hashing and salting and gives the basic functions a proper login system needs.
     *
     * @author Panique
     * @link https://github.com/panique/php-login-minimal/
     * @license http://opensource.org/licenses/MIT MIT License
     */

    // checking for minimum PHP version
    if (version_compare(PHP_VERSION, '5.3.7', '<')) {
      exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
    } else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
      // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
      // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
      require_once("libraries/password_compatibility_library.php");
    }

    // include the configs / constants for the database connection
    require_once("/documents/config/db.php");

    require_once("funktion/alle.php");
    // load the login class
    require_once("classes/Login.php");



    // create a login object. when this object is created, it will do all login/logout stuff automatically
    // so this single line handles the entire login process. in consequence, you can simply ...
    
    //stellt nur session un datenbankverbindungen wiederher wenn schon eingeloggt
    $login = new Login();


    $login_connection = $login->getlogin_connection();


    ?>


    <!--navbar includen -->
    <?php
    $currentpage = "auswertungindex";
    include ('navbar/navbar.php');
    ?>


    <h2>Auswertung</h2>

   </head>
  <body>

<?php



// ... ask if we are logged in here:
if ($login->isUserLoggedIn() == true) {
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.

    //gucken ob es est ist
    if (isUserAdmin($login_connection) == true) {

     //gucken ob Datenbankverbindung zu platinendb (bzw auch login) besteht, sonnst abbruch
    if (isset($login)) {
      if ($login->errors) {
          foreach ($login->errors as $error) {
              if($error != false) {
                echo '<div class="alert alert-danger"> '.$error.'   </div> ';
                die();
              }
          }
      }
    }

    include("views/auswertung.php");
    }
    else {
      header("location: index.php");
    }

    
} 
else {
  // the user is not logged in. you can do whatever you want here.
  // for demonstration purposes, we simply show the "you are not logged in" view.
  header("location: index.php");
}

?>
    




    <script src="plugins/jquery3.5.1/dist/jquery.min.js" type="text/javascript"></script>

    <script src="plugins/popper1.14.7/dist/umd/popper.min.js"></script>
 
    <script src="plugins/bootstrap-4.5.3-dist/js/bootstrap.min.js" type="text/javascript"></script>








  </body>
</html>
