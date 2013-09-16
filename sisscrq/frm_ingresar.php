<?php

include("./scrq-config.php");
include("./tool/strings.php");
include("./tool/template.php");

$tplIngresar = file_get_contents("./site/html/ingresar.html");

echo getHTML($tplIngresar);
?>