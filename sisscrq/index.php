<?php

session_start();

include("./scrq-config.php");
include("./scrq-secure.php");

header("Location: " . WEB_SERVER . "buscar/");
?>