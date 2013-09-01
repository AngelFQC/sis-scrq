<?php

/**
 * Genera la cabecera en HTML de la aplicación
 * @param array $scriptsLocal Nombres de archivos locales JavaScript a agregar en el <head>
 * @return string Código HTML de la cabecera
 */
function getHeader($scriptsLocal = array()) {
	$tplHeader = file_get_contents("./site/html/header.html");
	$tplScriptLocal = file_get_contents("./site/html/script_local.html");

	$listaScripts = "";

	if (count($scriptsLocal) > 0) {
		$etiquetas = array("{nombre arcivo}");

		foreach ($scriptsLocal as $reemplazo) {
			$listaScripts .= str_replace($etiquetas, $reemplazo, $tplScriptLocal);
		}
	}

	$etiquetas = array("{scripts locals}");
	$reemplazo = array($listaScripts);
	$header = str_replace($etiquetas, $reemplazo, $tplHeader);

	return $header;
}

/**
 * Genera el pie de página en HTML de la aplicación
 * @return string Código HTML del pie de página
 */
function getFooter() {
	$tplFooter = file_get_contents("./site/html/footer.html");

	$etiquetas = array("{nombre enfermera}", "{turno}");
	$reemplazo = array($_SESSION["enfermera"]["nombre"], getTurno());
	$footer = str_replace($etiquetas, $reemplazo, $tplFooter);

	return $footer;
}

/**
 * Procesa HTML
 * @param string $htmlUnprocessed Código HTML a ser procesado
 * @return string HTML procesado
 */
function getHTML($htmlUnprocessed) {
	$etiquetas = array("{WEB_SERVER}");
	$reemplazo = array(WEB_SERVER);

	$htmlProcessed = str_replace($etiquetas, $reemplazo, $htmlUnprocessed);
	
	global $conexion;
	
	$conexion->close();

	return $htmlProcessed;
}

?>