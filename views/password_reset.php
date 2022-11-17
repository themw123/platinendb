<?php
// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo '<div class="alert alert-warning"> ' . $error . '   </div> ';
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo '<div class="alert alert-info"> ' . $message . '   </div> ';
        }
    }
}
?>

<?php
// show potential errors / feedback (from registration object)
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo '<div class="alert alert-warning"> ' . $error . '   </div> ';
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo '<div class="alert alert-info"> ' . $message . '   </div> ';
        }
    }
}
?>





<?php if ($login->passwordResetLinkIsValid() == true) { ?>


    <div class="wrapper fadeInDown">
        <div id="formContent">

            <div class="fadeIn first">

                <i class='fas fa-user-alt' id=icon1></i>

            </div>


            <form method="post" action="password_reset.php" name="new_password_form">
                <input type='hidden' name='user_name' value='<?php echo $_GET['user_name']; ?>' />
                <input type='hidden' name='user_password_reset_hash' value='<?php echo $_GET['verification_code']; ?>' />

                <input id="user_password_new" type="password" name="user_password_new" placeholder="<?php echo WORDING_NEW_PASSWORD; ?>" pattern=".{6,}" required autocomplete="off" />

                <input id="user_password_repeat" type="password" name="user_password_repeat" placeholder="<?php echo WORDING_NEW_PASSWORD_REPEAT; ?>" pattern=".{6,}" required autocomplete="off" />


                <input id="neuespasswort" class="fadeIn second" type="submit" name="submit_new_password" value="bestätigen" />

            </form>


            <div id="formFooter">
                <a id="new-board-btn" class="fadeIn third" href="index.php">zurück zum Login</a>
            </div>

        </div>
    </div>

    <!-- no data from a password-reset-mail has been provided, so we simply show the request-a-password-reset form -->
    <?php } else {

    if (empty($message) && empty($error)) {
    ?>
        <div class="alert alert-info">Bitte geben Sie den Benutzernamen des Accounts an, dessen Passwort Sie vergessen haben. </div>


        <div class="wrapper fadeInDown">
            <div id="formContent">



                <div class="fadeIn first">

                    <i class="fas fa-user-alt" id=icon1></i>

                </div>

                <form method="post" action="password_reset.php" name="password_reset_form">

                    <input id="user_name" type="text" name="user_name" placeholder="Benutzername" required />

                    <input id="versenden" type="submit" class="fadeIn second" name="request_password_reset" value="E-Mail versenden" />

                </form>

                <div id="formFooter">
                    <a class="fadeIn third" href="index.php">zurück zum Login</a>
                </div>

            </div>
        </div>
    <?php
    } else {


    ?>

        <div class="wrapper">
            <div id="formContent">



                <div>

                    <i class="fas fa-user-alt" id=icon1></i>

                </div>

                <form method="post" action="password_reset.php" name="password_reset_form">

                    <input id="user_name" type="text" name="user_name" placeholder="Benutzername" required />

                    <input id="versenden" type="submit" name="request_password_reset" value="E-Mail versenden" />

                </form>


                <div id="formFooter">
                    <a href="index.php">zurück zum Login</a>
                </div>

            </div>
        </div>

<?php

    }
}

?>