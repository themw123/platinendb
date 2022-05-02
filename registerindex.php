
<!DOCTYPE html>

<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="#" rel="shortcut icon" />
    
    <link href="plugins/fontawesome-free-5.15.1-web/css/all.css" rel="stylesheet">

    <link href="plugins/bootstrap-4.5.3-dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="styles/registerindex.css">





  
    
    <title>Registerseite</title>

    <!--navbar includen -->
    <?php
    $currentpage = "registrierindex";
    include ('navbar/navbar.php');
    ?>

    

  </head>
  <body>


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

// load the registration class
require_once("classes/Registration.php");

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once('translations/en.php');

// include the PHPMailer library
require_once('libraries/PHPMailer.php');

// load the login class
require_once("classes/Login.php");

// create the registration object. when this object is created, it will do all registration stuff automatically
// so this single line handles the entire registration process.


$registration = new Registration();



include("views/register.php");




?>



<script src="plugins/jquery3.5.1/dist/jquery.min.js" type="text/javascript"></script>

<script src="plugins/popper1.14.7/dist/umd/popper.min.js"></script>

<script src="plugins/bootstrap-4.5.3-dist/js/bootstrap.min.js" type="text/javascript"></script>

<script src='plugins/fontawesome-free-5.15.1-web/a076d05399.js'></script>

<script src="javascript/lehrstuhl.js"></script>


  </body>
</html>


