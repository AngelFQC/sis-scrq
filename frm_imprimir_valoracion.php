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

$tplHeader = file_get_contents("./site/html/header.imprimir.valoracion.html");
$tplValoracion = file_get_contents("./site/html/ver.valoracion.html");
$tplFooter = file_get_contents("./site/html/footer.imprimir.valoracion.html");

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

				$respuestaItem = $respuestaAbierta->getRespuesta();
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

$etiquetas = array("{paciente nombre}", "{fecha valoracion}", "{cama paciente}");
$reemplazo = array($paciente, getFormatedDate($hojaValoracion->getFecha()), $paciente->getNumeroCama());
$tplHeader = str_replace($etiquetas, $reemplazo, $tplHeader);

$etiquetas = array("{idv}", "{secciones}");
$reemplazo = array($hojaValoracion->getId(), $listaSecciones);
$tplValoracion = str_replace($etiquetas, $reemplazo, $tplValoracion);

$etiquetas = array("{enfermera}");
$reemplazo = array($hojaValoracion->getEnfermera());
$tplFooter = str_replace($etiquetas, $reemplazo, $tplFooter);

$html = $tplHeader . $tplValoracion . $tplFooter;

echo getHTML($html);
?>