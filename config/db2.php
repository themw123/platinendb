<?php
    function OpenCon()
     {
     $dbhost = "127.0.0.1";
     $dbuser = "est";
     $dbpass = "***REMOVED***";
     $db = "platinendb";
     $link = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $link -> error);
     
     return $link;
     }


     function OpenCon2()
     {
     $dbhost = "127.0.0.1";
     $dbuser = "est";
     $dbpass = "***REMOVED***";
     $db = "login";
     $link = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $link -> error);
     
     return $link;
     }
     
    function CloseCon($link)
     {
     $link -> close();
     }
       
    ?>