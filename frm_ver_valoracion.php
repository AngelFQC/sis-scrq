<?php

session_start();

include("./scrq-config.php");
include("./scrq-secure.php");

if (!isset($_GET["id"])) {
	exit();
}

$idHojaValoracion = trim($_GET["id"]);

if (!preg_match("/^[0-9]{1,}$/", $idHojaValoracion)) {
	exit();
}

include("./tool/strings.php");
include("./tool/template.php");

function __autoload($nombreClase) {
	require_once ("./modelo/" . $nombreClase . ".class.php");
}

$hojaValoracion = new HojaValoracion();
$hojaValoracion->setId($idHojaValoracion);
$hojaValoracion->obtener();
$paciente = $hojaValoracion->obtenerPaciente();

$tplAside = file_get_contents("./site/html/aside.valoracion.ver.html");
$tplValoracion = file_get_contents("./site/html/ver.valoracion.html");
$tplFooter = file_get_contents("./site/html/footer.html");

$tplAsideSeccion = file_get_contents("./site/html/aside.valoracion.seccion.html");
$listaAsideSecciones = "";

$tplSeccion = file_get_contents("./site/html/ver.valoracion.seccion.html");
$tplGrupo = file_get_contents("./site/html/ver.valoracion.grupo.html");
$tplItem = file_get_contents("./site/html/ver.valoracion.item.html");

$listaSecciones = "";
$listaGrupos = "";
$listaItems = "";

$objSeccion = new Seccion();
$secciones = $objSeccion->listar();

foreach ($secciones as $seccion) {
	$listaGrupos = "";
	$grupos = $seccion->listarGrupos();

	foreach ($grupos as $grupo) {
		$listaItems = "";
		$items = $grupo->listarItems();

		foreach ($items as $item) {
			$opciones = $item->listarOpciones();
			$respuestaItem = "--";

			if (count($opciones) > 0) {
				$respuestaCerrada = $hojaValoracion->obtenerRespuestaCerrada($item->getId());

				if ($respuestaCerrada != NULL) {
					$respuestaItem = $respuestaCerrada->getOpcion()->getNombre();
				}
			} else {
				$respuestaAbierta = $hojaValoracion->obtenerRespuestaAbierta($item->getId());

				$respuestaItem = ($respuestaAbierta->getRespuesta() != "") ? $respuestaAbierta->getRespuesta() : "--";
			}

			$etiquetas = array("{id item}", "{nombre item}", "{respuesta item}");
			$reemplazo = array($item->getId(), $item->getNombre(), $respuestaItem);
			$listaItems .= str_replace($etiquetas, $reemplazo, $tplItem);
		}

		$etiquetas = array("{id grupo}", "{datos grupo}", "{items grupo}");
		$reemplazo = array($grupo->getId(), $grupo->getDatos(), $listaItems);
		$listaGrupos .= str_replace($etiquetas, $reemplazo, $tplGrupo);
	}

	$etiquetas = array("{id seccion}", "{titulo seccion}", "{grupos seccion}");
	$reemplazo = array($seccion->getId(), $seccion->getTitulo(), $listaGrupos);
	$listaSecciones .= str_replace($etiquetas, $reemplazo, $tplSeccion);

	$etiquetas = array("{id seccion}", "{titulo seccion}");
	$reemplazo = array($seccion->getId(), $seccion->getTitulo());
	$listaAsideSecciones .= str_replace($etiquetas, $reemplazo, $tplAsideSeccion);
}

$etiquetas = array("{paciente nombre}", "{paciente dni}", "{paciente registro}", "{lista secciones}",
	"{enfermera valoracion}", "{fecha valoracion}", "{turno valoracion}", "{numero cama}");
$reemplazo = array($paciente, $paciente->getDni(), getFormatedDate($paciente->getRegistro()), $listaAsideSecciones,
	$hojaValoracion->getEnfermera(), getFormatedDate($hojaValoracion->getFecha()), getTurno($hojaValoracion->getTurno()), $paciente->getNumeroCama());
$tplAside = str_replace($etiquetas, $reemplazo, $tplAside);

$etiquetas = array("{idv}", "{secciones}");
$reemplazo = array($hojaValoracion->getId(), $listaSecciones);
$tplValoracion = str_replace($etiquetas, $reemplazo, $tplValoracion);

$scriptsLocales = array("imprimir_valoracion");

$html = getHeader($scriptsLocales) . $tplValoracion . $tplAside . getFooter();

echo getHTML($html);
?>