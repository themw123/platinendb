<?php
require_once("/documents/config/db.php");
require_once("../../classes/Login.php");
require_once("../../utils/util.php");
require_once("../../classes/Sicherheit.php");

$login = new Login();

$login_connection = $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();


//sicherheit checks
if (!(isset($_POST['aktion']))) {
  $aktion = "";
} else {
  $aktion = mysqli_real_escape_string($platinendb_connection, $_POST["aktion"]);
}
$von = "nutzen";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if ($bestanden == true && $aktion == "bearbeiter") {

  $stmt = $login_connection->prepare(
    "SELECT user_name FROM users WHERE admin = '1' ORDER BY user_name asc"
  );

  $stmt->execute();
  $result = $stmt->get_result();


  $namen = array();
  $counter = 0;

  if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

      $namen[$counter] = $row['user_name'];

      $counter = $counter + 1;
    }
    echo json_encode($namen);
  }


  mysqli_close($platinendb_connection);

  mysqli_close($login_connection);
}
