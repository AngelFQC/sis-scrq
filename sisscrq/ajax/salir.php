<?php
session_start();

include("../scrq-config.php");

$_SESSION["enfermera"] = NULL;

session_unset();
session_destroy();

header("Location: " . WEB_SERVER);
?>
