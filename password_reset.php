<!DOCTYPE html>

<html lang="de">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link href="#" rel="shortcut icon" />

  <link href="plugins/bootstrap-4.5.3-dist/css/bootstrap.min.css" rel="stylesheet">


  <link rel="stylesheet" type="text/css" href="styles/password_reset.css">



  <title>Passwort zurücksetzen</title>


  <?php
  //navbar includen
  $currentpage = "password_reset";
  include('navbar/navbar.php');
  ?>


  </ul>
  </div>
  </nav>

</head>

<body>

  <?php

  // check for minimum PHP version
  if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
  } else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once('libraries/password_compatibility_library.php');
  }
  // include the config
  require_once("/documents/config/db.php");

  // include the to-be-used language, english by default. feel free to translate your project and include something else
  require_once('translations/en.php');

  // include the PHPMailer library
  require_once('libraries/PHPMailer.php');

  // load the login class
  require_once('classes/Login.php');

  // create a login object. when this object is created, it will do all login/logout stuff automatically
  // so this single line handles the entire login process.
  $login = new Login();

  // the user has just successfully entered a new password
  // so we show the index page = the login page
  if ($login->passwordResetWasSuccessful() == true && $login->passwordResetLinkIsValid() != true) {
    header("location: index.php");
  } else {
    // show the request-a-password-reset or type-your-new-password form
    include("views/password_reset.php");
  }


  ?>

  <script src='plugins/fontawesome-free-5.15.1-web/a076d05399.js'></script>

</body>

</html>