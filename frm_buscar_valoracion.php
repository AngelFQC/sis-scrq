<?php

session_start();

include("./scrq-config.php");
include("./scrq-secure.php");
include("./tool/strings.php");
include("./tool/template.php");

function __autoload($nombreClase) {
	require_once ("./modelo/" . $nombreClase . ".class.php");
}

$tplBuscar = file_get_contents("./site/html/buscar.valoracion.html");

$scriptsLocales = array("buscar_valoracion");

$html = getHeader($scriptsLocales) . $tplBuscar . getFooter();

echo getHTML($html);
?>