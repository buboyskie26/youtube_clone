<?php
require_once("includes/header.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/SettingsFormProvider.php");
require_once("includes/classes/Constants.php");

if(!User::isLoggedIn()) {
    header("Location: signIn.php");
}

$detailsMessage = "";
$passwordMessage = "";
$formProvider = new SettingsFormProvider();



?>