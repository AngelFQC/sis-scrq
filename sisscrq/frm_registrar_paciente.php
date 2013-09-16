<?php
session_start();

include("./scrq-config.php");
include("./scrq-secure.php");

include("./tool/strings.php");
include("./tool/template.php");

function __autoload($nombreClase) {
	require_once ("./modelo/" . $nombreClase . ".class.php");
}

$tplRegistrar = file_get_contents("./site/html/registrar.paciente.html");
$tplFooter = file_get_contents("./site/html/footer.html");

$scriptsLocales = array("registrar_paciente");

$etiquetas = array("{registro fecha}");
$reemplazo = array(getFormatedDate("", "l d F Y"));
$tplRegistrar = str_replace($etiquetas, $reemplazo, $tplRegistrar);

$html = getHeader($scriptsLocales) . $tplRegistrar . getFooter();

echo getHTML($html);
?>