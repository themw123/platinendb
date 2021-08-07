
<?php
// show potential errors / feedback (from register object)
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo '<div class="alert alert-warning"> '.$error.'   </div> ';
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo '<div class="alert alert-info"> '.$message.'   </div> ';
        }
    }
}
?>



<!-- 
if ist da, damit einfliegende registerform nicht erneut angezeigt wird, wenn registrierung erfolgreich war
-->

<?php

if ($registration->messages == null ) {

    echo

    '<div class="wrapper fadeInDown">

    <div id="formContent">

    <div class="fadeIn first"> 
    
    <i class="fas fa-user-alt" id = icon1></i>
  
    </div> 
    

    <form method="post" action="registerindex.php" name="registerform">
 
    <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder="Benutzername" required >
 
    <input id="login_input_email" class="login_input" type="email" name="user_email" placeholder="E-Mail-Adresse" required /> 

    <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" placeholder="Passwort (min. 6 Zeichen)"  required autocomplete="off" />
 
    <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" placeholder="Passwort wiederholen"  required autocomplete="off" />
 
    <div class="containerintext">
    <p>Standort?</p>
    <label class="radio-inline" id="option1">
    <input value="int" type="radio" name="user_standort" checked>intern
    </label>
    <label class="radio-inline" id="option2">
    <input value="ext" type="radio" name="user_standort">extern
    </label>
    </div>



    <input id="registrierensubmit" type="submit" name="register" class="fadeIn second" value="registrieren">

    

    
 
  <div id="formFooter">
    <a id="new-board-btn" class="fadeIn third" href="index.php">zurück zum Login</a>
  </div>
 
  </form>';
  }

 ?>



 













