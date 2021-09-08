
<?php

if(isset($_GET["Validation"])) {
  $login->messages[] = "Du musst dich erst als est einloggen und anschließend den Link erneut öffnen, um den Account bestätigen zu können.";
}

if(isset($_GET["wait"])) {
  $login->messages[] = "Sobald ein Admin deinen Account bestätigt, kannst du dich einloggen. Du wirst außerdem per E-Mail benachrichtigt wenn dein Account bestätigt wurde.";
}

// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            if($error != false) {
              echo '<div class="alert alert-warning"> '.$error.'   </div> ';
            }
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo '<div class="alert alert-info"> '.$message.'   </div> ';
        }
    }
}
?>



<!--
Hier folgt das einfliegende Login     
-->
<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->

    <!-- Icon -->
    <div class="fadeIn first">

    <i class='fas fa-user-alt' id = icon1></i>
    
    </div>

    <!-- Login Form -->
    <form method="post" action="index.php" name="loginform">

      <input type="text" id="login_input_username" class="login_input" name="user_name" placeholder="Benutzername" required>

      <input type="password" id="login_input_password" class="login_input" name="user_password" placeholder="Passwort" required>

      <input id="einloggen" type="submit" name="login" class="fadeIn second" value="einloggen">


    </form>
    
    <!--registrieren-->

    <div id="formFooter">
      <a id="registrieren" class="fadeIn third" href="registerindex.php">registrieren</a>
      <a class="fadeIn third" href="password_reset.php">Passwort vergessen?</a>
    </div>

  </div>
</div>

