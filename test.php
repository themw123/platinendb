<?php
require_once("/documents/config/db.php");
require_once("classes/Login.php");
require_once("funktion/alle.php");
require_once("classes/Sicherheit.php");

$login = new Login();

$login_connection= $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();




  $size = 422444;
  $type = "application/zip";
  $name = "CAM_Spielfeld.zip";

  $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);
  $query = "SELECT ID, archive FROM platinen WHERE ID = 576";

  $result = mysqli_query($platinendb_connection,$query);
  $row = mysqli_fetch_array($result);


  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-length: $size");
  header("Content-type: $type");
  header("Content-Disposition: attachment; filename=$name");
  header("Content-Transfer-Encoding: binary");
  echo $row['archive']; 




?>
