<?php
session_start();

include("./scrq-config.php");
include("./scrq-secure.php");

include("./tool/strings.php");
include("./tool/template.php");

if(!$_SESSION["enfermera"]["isadmin"]){
	header("Location: " . WEB_SERVER);
}

function __autoload($nombreClase) {
	require_once ("./modelo/" . $nombreClase . ".class.php");
}

$tplRegistrar = file_get_contents("./site/html/admin.html");

$scriptsLocales = array("admin");

$html = getHeader($scriptsLocales) . $tplRegistrar . getFooter();

echo getHTML($html);
?>