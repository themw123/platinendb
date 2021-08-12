<!DOCTYPE html>

<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    
    <link href="#" rel="shortcut icon" />
    
    <link href="/logingit4/plugins/fontawesome-free-5.15.1-web/css/all.css" rel="stylesheet">
    <link href="/logingit4/plugins/gijgo1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css">

    <link href="/logingit4/plugins/bootstrap-4.5.3-dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="/logingit4/plugins/bootstrap-table-master/dist/bootstrap-table.min.css" rel="stylesheet">

    <link href="/logingit4/plugins/datatable/DataTables-1.10.22/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="/logingit4/plugins/datatable/buttons/Buttons-1.6.5/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
  
    <link href="/logingit4/plugins/datatable/searchpanes/SearchPanes-1.2.1/css/searchPanes.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="/logingit4/plugins/datatable/select/Select-1.3.1/css/select.dataTables.min.css" rel="stylesheet" type="text/css">


    <link href="/logingit4/styles/platinenundnutzenindex.css" rel="stylesheet" type="text/css">




    <title>Platinenaufträge</title>
    
    <!--php stuff-->

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
    require_once("config/db.php");
    require_once("config/db2.php");
    require_once("funktion/alle.php");
    // load the login class
    require_once("classes/Login.php");


    // create a login object. when this object is created, it will do all login/logout stuff automatically
    // so this single line handles the entire login process. in consequence, you can simply ...
    $login = new Login();

    //Verbindung zur Platinendb Datenbank aufbauen
    $link = OpenCon();

      
    ?>


    <!--navbar includen -->
    <?php
    $currentpage = "platinenindex";
    include ('navbar/navbar.php');
    ?>


    <h2>Platinenaufträge</h2>

   </head>
  <body>

<?php

// ... ask if we are logged in here:
if ($login->isUserLoggedIn() == true) {
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
    include("views/logged_in.php");
    
} 
else {
  // the user is not logged in. you can do whatever you want here.
  // for demonstration purposes, we simply show the "you are not logged in" view.
  header("location: index.php");
}

?>
    
    <script src="/logingit4/plugins/jquery3.5.1/dist/jquery.min.js" type="text/javascript"></script>

    <script src="/logingit4/plugins/popper1.14.7/dist/umd/popper.min.js"></script>
 
    <script src="/logingit4/plugins/bootstrap-4.5.3-dist/js/bootstrap.min.js" type="text/javascript"></script>

    <script src="/logingit4/plugins/bootstrap-table-master/dist/bootstrap-table.min.js"></script>

    <script src="/logingit4/plugins/datatable/DataTables-1.10.22/js/jquery.dataTables.min.js" charset="utf8" type="text/javascript"></script>
    <script src="/logingit4/plugins/fnsort/fnSortNeutral.js" type="text/javascript" charset="utf8"></script>
    <script src="/logingit4/plugins/datatable/DataTables-1.10.22/js/dataTables.bootstrap4.min.js" type="text/javascript" charset="utf8"></script>

    <script src="/logingit4/plugins/tabletools2.2.4/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="/logingit4/plugins/button/Buttons-1.6.5/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="/logingit4/plugins/button/JSZip-2.5.0/jszip.min.js" type="text/javascript"></script>
    <script src="/logingit4/plugins/button/pdfmake-0.1.36/pdfmake.min.js" type="text/javascript"></script>
    <script src="/logingit4/plugins/button/pdfmake-0.1.36/vfs_fonts.js" type="text/javascript"></script>
    <script src="/logingit4/plugins/button/Buttons-1.6.5/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="/logingit4/plugins/button/Buttons-1.6.5/js/buttons.print.min.js" type="text/javascript" ></script>
    <script src="/logingit4/plugins/button/Buttons-1.6.5/js/buttons.bootstrap4.min.js" type="text/javascript"></script>
    
    <script src="/logingit4/plugins/moment2.8.4/moment.min.js" type="text/javascript" charset="utf8"></script>
    <script src="/logingit4/plugins/datetime1.10.21/datetime-moment.js" type="text/javascript" charset="utf8"></script>
    
    <script src="/logingit4/plugins/SearchPanes-1.2.1/js/dataTables.searchPanes.min.js" type="text/javascript"></script>
    <script src="/logingit4/plugins/SearchPanes-1.2.1/js/searchPanes.bootstrap4.min.js" type="text/javascript"></script>
    <script src="/logingit4/plugins/Select-1.3.1/js/dataTables.select.min.js" type="text/javascript"></script>

    <script src="/logingit4/plugins/bootbox5/bootbox.min.js" type="text/javascript"></script>


    <!-- Datepicker für Bearbeiten-->
    <script src="/logingit4/plugins/gijgo1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script src="/logingit4/plugins/gijgo1.9.13/js/messages/messages.de-de.js" type="text/javascript"></script>

    <script src="/logingit4/plugins/dataTables.liveAjax.js" language="JavaScript"></script>




    <script src="javascript/platinenindex.js"></script>

  </body>
</html>