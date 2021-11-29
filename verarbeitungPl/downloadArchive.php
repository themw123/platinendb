<?php
require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

$login_connection= $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//$aktion = "einfuegen";
//sicherheit checks
if(!(isset($_POST['aktion']))) {
  $aktion = "";
}
else {
  $aktion = mysqli_real_escape_string($platinendb_connection, $_POST["aktion"]);
}
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();



if($bestanden == true) {

    $id = mysqli_real_escape_string($platinendb_connection, $_POST['Id']);
    $query = "SELECT ID,  " .
            "FROM platinen WHERE id = '$id'";
    $result = mysqli_query($platinendb_connection,$query) or die('Error, query failed');
    list($id, $file, $type, $size,$content) = mysqli_fetch_array($result);
                   //echo $id . $file . $type . $size;
                   //echo 'sampath';
    header("Content-length: $size");
    header("Content-type: $type");
    header("Content-Disposition: attachment; filename=$file");
    ob_clean();
    flush();
            $content = stripslashes($content);
    echo $content;
    mysqli_close($platinendb_connection);
    exit;

}


else {
  header('Content-Type: application/json');
  echo json_encode(array('data'=> "fehlerhaft"));
  die();
}


?>