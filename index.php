<!DOCTYPE html>

<html lang="de">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link href="#" rel="shortcut icon" />

  <link href="plugins/bootstrap-4.5.3-dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="plugins/fontawesome-free-5.15.1-web/css/all.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="styles/index.css">


  <title>Loginseite</title>


  <?php
  //navbar includen
  $currentpage = "index";
  include('navbar/navbar.php');
  ?>


  </ul>
  </div>
  </nav>

</head>

<body>

  <?php

  /*
Menü einbinden
*/



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

  // include the to-be-used language, english by default. feel free to translate your project and include something else
  require_once('translations/en.php');

  // include the PHPMailer library
  require_once('libraries/PHPMailer.php');

  // create a login object. when this object is created, it will do all login/logout stuff automatically
  // so this single line handles the entire login process. in consequence, you can simply ...
  $login = new Login();

  // ... ask if we are logged in here:
  if ($login->isUserLoggedIn() == true) {
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
    header("location: platinenindex.php");
  } else {
    // the user is not logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are not logged in" view.
    include("views/not_logged_in.php");
  }

  ?>


  <script src='plugins/fontawesome-free-5.15.1-web/a076d05399.js'></script>
</body>

</html>