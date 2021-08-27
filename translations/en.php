<?php

/**
 * Please note: we can use unencoded characters like ö, é etc here as we use the html5 doctype with utf8 encoding
 * in the application's header (in views/_header.php). To add new languages simply copy this file,
 * and create a language switch in your root files.
 */

// login & registration classes
define("MESSAGE_ACCOUNT_NOT_ACTIVATED", "hr Konto ist noch nicht aktiviert. Bitte klicken Sie auf den Bestätigungslink in der Mail.");
define("MESSAGE_CAPTCHA_WRONG", "Captcha ist fehlerhaft!");
define("MESSAGE_COOKIE_INVALID", "ungültiger cookie");
define("MESSAGE_DATABASE_ERROR", "Problem mit der Datenbankverbindung.");
define("MESSAGE_EMAIL_ALREADY_EXISTS", "Diese E-Mail-Adresse ist bereits registriert. Bitte benutzen Sie die Seite \"Passwort vergessen option\", wenn Sie sich nicht mehr daran erinnern.");
define("MESSAGE_EMAIL_CHANGE_FAILED", "Entschuldigung, Ihre E-Mail-Änderung ist fehlgeschlagen.");
define("MESSAGE_EMAIL_CHANGED_SUCCESSFULLY", "Ihre E-Mail-Adresse wurde erfolgreich geändert. Die neue E-Mail-Adresse lautet");
define("MESSAGE_EMAIL_EMPTY", "E-Mail kann nicht leer sein.");
define("MESSAGE_EMAIL_INVALID", "Ihre E-Mail-Adresse ist nicht in einem gültigen E-Mail-Format.");
define("MESSAGE_EMAIL_SAME_LIKE_OLD_ONE", "Tut mir leid, diese E-Mail-Adresse ist die gleiche wie Ihre aktuelle. Bitte wählen Sie eine andere.");
define("MESSAGE_EMAIL_TOO_LONG", "E-Mail darf nicht länger als 64 Zeichen sein.");
define("MESSAGE_LINK_PARAMETER_EMPTY", "Leere Linkparameterdaten.");
define("MESSAGE_LOGGED_OUT", "Sie wurden abgemeldet.");
// The "login failed"-message is a security improved feedback that doesn't show a potential attacker if the user exists or not
define("MESSAGE_LOGIN_FAILED", "Anmeldung fehlgeschlagen.");
define("MESSAGE_OLD_PASSWORD_WRONG", "Ihr altes Passwort war falsch.");
define("MESSAGE_PASSWORD_BAD_CONFIRM", "Passwort und Passwort-Wiederholung sind nicht gleich");
define("MESSAGE_PASSWORD_CHANGE_FAILED", "Tut mir leid, die Änderung Ihres Passworts ist fehlgeschlagen.");
define("MESSAGE_PASSWORD_CHANGED_SUCCESSFULLY", "Passwort erfolgreich geändert!");
define("MESSAGE_PASSWORD_EMPTY", "Passwort-Feld war leer");
define("MESSAGE_PASSWORD_RESET_MAIL_FAILED", "E-Mail zum Zurücksetzen des Passworts NICHT erfolgreich gesendet! Fehler: ");
define("MESSAGE_PASSWORD_RESET_MAIL_SUCCESSFULLY_SENT", "E-Mail zum Zurücksetzen des Passworts erfolgreich gesendet!");
define("MESSAGE_PASSWORD_TOO_SHORT", "Das Passwort hat eine Mindestlänge von 6 Zeichen.");
define("MESSAGE_PASSWORD_WRONG", "Falsches Kennwort. Versuchen Sie es erneut.");
define("MESSAGE_PASSWORD_WRONG_3_TIMES", "Sie haben bereits 3 oder mehr Mal ein falsches Passwort eingegeben. Bitte warten Sie 30 Sekunden, um es erneut zu versuchen.");
define("MESSAGE_REGISTRATION_ACTIVATION_NOT_SUCCESSFUL", "Tut mir leid, hier gibt es keine solche Id/Prüfcode-Kombination...");
define("MESSAGE_REGISTRATION_ACTIVATION_SUCCESSFUL", "Die Aktivierung war erfolgreich! Sie können sich jetzt einloggen!");
define("MESSAGE_REGISTRATION_FAILED", "Tut mir leid, Ihre Registrierung ist fehlgeschlagen. Bitte gehen Sie zurück und versuchen Sie es erneut.");
define("MESSAGE_RESET_LINK_HAS_EXPIRED", "Ihr Reset-Link ist abgelaufen. Bitte benutzen Sie den Reset-Link innerhalb einer Stunde.");
define("MESSAGE_VERIFICATION_MAIL_ERROR", "Es tut uns leid, wir konnten Ihnen keine Bestätigungsmail schicken. Ihr Konto wurde NICHT erstellt.");
define("MESSAGE_VERIFICATION_MAIL_NOT_SENT", "Verifikationsmail NICHT erfolgreich versandt! Fehler: ");
define("MESSAGE_VERIFICATION_MAIL_SENT", "Ihr Konto wurde erfolgreich erstellt und wir haben Ihnen eine E-Mail geschickt. Bitte klicken Sie auf den VERIFICATION LINK in dieser Mail.");
define("MESSAGE_USER_DOES_NOT_EXIST", "Dieser Benutzer existiert nicht");
define("MESSAGE_USERNAME_BAD_LENGTH", "Der Benutzername darf nicht kürzer als 2 oder länger als 64 Zeichen sein.");
define("MESSAGE_USERNAME_CHANGE_FAILED", "Entschuldigung, die Umbenennung Ihres gewählten Benutzernamens ist fehlgeschlagen.");
define("MESSAGE_USERNAME_CHANGED_SUCCESSFULLY", "Ihr Benutzername wurde erfolgreich geändert. Der neue Benutzername lautet ");
define("MESSAGE_USERNAME_EMPTY", "Das Feld Benutzername war leer");
define("MESSAGE_USERNAME_EXISTS", "Entschuldigung, dieser Benutzername ist bereits vergeben. Bitte wählen Sie einen anderen.");
define("MESSAGE_USERNAME_INVALID", "Benutzername passt nicht in das Namensschema: nur a-Z und Zahlen sind erlaubt, 2 bis 64 Zeichen");
define("MESSAGE_USERNAME_SAME_LIKE_OLD_ONE", "Entschuldigung, dieser Benutzername ist derselbe wie Ihr aktueller. Bitte wählen Sie einen anderen.");

// views
define("WORDING_BACK_TO_LOGIN", "Zurück zur Login-Seite");
define("WORDING_CHANGE_EMAIL", "E-Mail ändern");
define("WORDING_CHANGE_PASSWORD", "Passwort ändern");
define("WORDING_CHANGE_USERNAME", "Benutzername ändern");
define("WORDING_CURRENTLY", "derzeit");
define("WORDING_EDIT_USER_DATA", "Benutzerdaten bearbeiten");
define("WORDING_EDIT_YOUR_CREDENTIALS", "Sie sind eingeloggt und können Ihre Zugangsdaten hier bearbeiten");
define("WORDING_FORGOT_MY_PASSWORD", "Ich habe mein Passwort vergessen");
define("WORDING_LOGIN", "einloggen");
define("WORDING_LOGOUT", "Logout");
define("WORDING_NEW_EMAIL", "neue E-Mail");
define("WORDING_NEW_PASSWORD", "neues Passwort");
define("WORDING_NEW_PASSWORD_REPEAT", "Neues Passwort wiederholen");
define("WORDING_NEW_USERNAME", "Neuer Benutzername (der Benutzername darf nicht leer sein und muss aus azAZ09 und 2-64 Zeichen bestehen)");
define("WORDING_OLD_PASSWORD", "Ihr altes Passwort");
define("WORDING_PASSWORD", "Passwort");
define("WORDING_PROFILE_PICTURE", "Ihr Profilbild (von gravatar):");
define("WORDING_REGISTER", "registrieren");
define("WORDING_REGISTER_NEW_ACCOUNT", "Neues Konto registrieren");
define("WORDING_REGISTRATION_CAPTCHA", "Bitte geben Sie diese Zeichen ein");
define("WORDING_REGISTRATION_EMAIL", "E-Mail des Benutzers (bitte geben Sie eine echte E-Mail-Adresse an, Sie erhalten eine Bestätigungsmail mit einem Aktivierungslink)");
define("WORDING_REGISTRATION_PASSWORD", "Passwort (mindestens 6 Zeichen!)");
define("WORDING_REGISTRATION_PASSWORD_REPEAT", "Passwort wiederholen");
define("WORDING_REGISTRATION_USERNAME", "Benutzername (nur Buchstaben und Zahlen, 2 bis 64 Zeichen)");
define("WORDING_REMEMBER_ME", "Mich eingeloggt lassen (für 2 Wochen)");
define("WORDING_REQUEST_PASSWORD_RESET", "Fordern Sie eine Passwortzurücksetzung an. Geben Sie Ihren Benutzernamen ein und Sie erhalten eine E-Mail mit Anweisungen:");
define("WORDING_RESET_PASSWORD", "Mein Passwort zurücksetzen");
define("WORDING_SUBMIT_NEW_PASSWORD", "Neues Passwort anfordern");
define("WORDING_USERNAME", "Benutzername");
define("WORDING_YOU_ARE_LOGGED_IN_AS", "Sie sind eingeloggt als ");