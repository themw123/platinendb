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
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if ($bestanden == true && $aktion == "auftraggeber") {

  $auftraggeber = mysqli_real_escape_string($login_connection, $_POST['Text']);

  $admin = mysqli_real_escape_string($login_connection, $_SESSION['admin']);

  if (isThisUserAdmin($login_connection, $auftraggeber)) {
    header('Content-Type: application/json');
    echo json_encode(array('data' => 'nichtadmin'));
    die();
  }

  $stmt = $login_connection->prepare(
    "SELECT user_id FROM users WHERE user_name=?"
  );
  $stmt->bind_param("s", $auftraggeber);
  $stmt->execute();
  $queryresult = $stmt->get_result();
  $queryresult = mysqli_fetch_assoc($queryresult);
  $AuftraggeberId = $queryresult['user_id'];




  $stmt = $login_connection->prepare(
    "DELETE FROM users WHERE user_id=?"
  );
  $stmt->bind_param("i", $AuftraggeberId);
  $stmt->execute();


  $sicherheit->checkQuery($login_connection);
  mysqli_close($platinendb_connection);
  mysqli_close($login_connection);
}
