<?php



// show potential errors / feedback (from register object)
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



<!-- 
if ist da, damit einfliegende registerform nicht erneut angezeigt wird, wenn registrierung erfolgreich war
-->

<?php

if ($registration->messages == null) {

    if (isset($_GET["Validation"]) && isset($_GET["user_name"]) && isset($_GET["user_email"]) && isset($_GET["user_password"])) {


?>

        <div class="wrapper fadeInDown">

            <div id="formContent">

                <div class="fadeIn first">
                    <i class="fas fa-user-alt" id=icon1></i>
                </div>


                <form method="post" action="registerindex.php" name="registerform">

                    <input value='<?php echo $_GET["user_name"] ?>' id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder="Benutzername" required readonly>

                    <input value='<?php echo $_GET["user_email"] ?>' id="login_input_email" class="login_input" type="email" name="user_email" placeholder="E-Mail-Adresse" required readonly />

                    <input value='<?php echo $_GET["user_password"] ?>' id="login_input_password_new" class="login_input" type="hidden" name="user_password" pattern=".{6,}" placeholder="Passwort (min. 6 Zeichen)" required readonly autocomplete="off" />


                    <div class="containerlehrstuhl lehrstuhldiv">
                        <label for="usr">Lehrstuhl:</label>

                        <div class="input-group ipg2">
                            <select class="form-control" id="lehrstuhl" name="user_lehrstuhl" required>
                                <option value="" selected disabled hidden>Option wählen</option>
                            </select>

                            <div class="input-group-append">
                                <button data-toggle="collapse" data-target="#collapse4" class="btn btn-primary lehrstuhlbutton" type="button"><i id="lehrstuhlbutton" class="far fa-caret-square-up"></i></button>
                            </div>
                        </div>

                        <div class="collapse" id="collapse4">
                            <div class="form-group test">
                                <input type="text" class="form-control" id="addLehrstuhl" aria-describedby="BenutzerHelp" placeholder="Lehrstuhlkürzel">
                                <button class="btn btn-primary" id="add2" type="button">hinzufügen</button>
                                <button class="btn btn-primary" id="rem2" type="button">Auswahl löschen</button>
                                <div class="alert alert-warning collapse" id="fehleraddlehrstuhl"></div>
                            </div>
                        </div>

                    </div>

                    <input id="registrierensubmit" type="submit" name="reg" class="fadeIn second" value="anlegen/bestätigen">


                <?php


            } else {

                ?>

                    <div class="wrapper fadeInDown">

                        <div id="formContent">

                            <div class="fadeIn first">

                                <i class="fas fa-user-alt" id=icon1></i>

                            </div>


                            <form method="post" action="registerindex.php" name="registerform">

                                <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder="Benutzername" required>

                                <input id="login_input_email" class="login_input" type="email" name="user_email" placeholder="E-Mail-Adresse" required />

                                <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" placeholder="Passwort (min. 6 Zeichen)" required autocomplete="off" />


                                <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" placeholder="Passwort wiederholen" required autocomplete="off" />


                                <input id="registrierensubmit" type="submit" name="register" class="fadeIn second" value="registrieren">
                            <?php
                        }




                            ?>
                            <div id="formFooter">
                                <?php

                                if (isset($_GET["Validation"]) && isset($_GET["user_name"]) && isset($_GET["user_email"]) && isset($_GET["user_password"]) && isset($_GET["user_standort"])) {
                                ?>
                                    <a id="new-board-btn" class="fadeIn third" href="index.php">zurück zur Startseite</a>
                                <?php
                                } else {
                                ?><a id="new-board-btn" class="fadeIn third" href="index.php">zurück zum Login</a>
                                <?php
                                }
                                ?>
                            </div>

                            </form>
                        <?php

                    }


                        ?>