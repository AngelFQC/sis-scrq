<?php
session_start();

include("./scrq-config.php");
include("./scrq-secure.php");

if (!isset($_GET["id"])) {
	exit();
}

$idPaciente = trim($_GET["id"]);

if (!preg_match("/^[0-9]{1,}$/", $idPaciente)) {
	exit();
}

include("./tool/strings.php");
include("./tool/template.php");

function __autoload($nombreClase) {
	require_once ("./modelo/" . $nombreClase . ".class.php");
}

$paciente = new Paciente();
$paciente->setId($idPaciente);
$paciente->obtener();

$tplAside = file_get_contents("./site/html/aside.valoracion.html");
$tplValoracion = file_get_contents("./site/html/realizar.valoracion.html");
$tplFooter = file_get_contents("./site/html/footer.html");

$tplAsideSeccion = file_get_contents("./site/html/aside.valoracion.seccion.html");
$listaAsideSecciones = "";

$tplSeccion = file_get_contents("./site/html/realizar.valoracion.seccion.html");
$tplGrupo = file_get_contents("./site/html/realizar.valoracion.grupo.html");
$tplItem = file_get_contents("./site/html/realizar.valoracion.item.html");
$tplItemRadio = file_get_contents("./site/html/realizar.valoracion.item.radio.html");
$tplItemText = file_get_contents("./site/html/realizar.valoracion.item.text.html");

$listaSecciones = "";
$listaGrupos = "";
$listaItems = "";
$listaOpciones = "";

$objSeccion = new Seccion();
$secciones = $objSeccion->listar();

foreach ($secciones as $seccion) {
	$listaGrupos = "";
	$grupos = $seccion->listarGrupos();

	foreach ($grupos as $grupo) {
		$listaItems = "";
		$items = $grupo->listarItems();

		foreach ($items as $item) {
			$listaOpciones = "";
			$opciones = $item->listarOpciones();

			if (count($opciones) > 0) {
				foreach ($opciones as $opcion) {
					$etiquetas = array("{id opcion}", "{nombre opcion}", "{id item}");
					$reemplazo = array($opcion->getId(), $opcion->getNombre(), $item->getId());
					$listaOpciones .= str_replace($etiquetas, $reemplazo, $tplItemRadio);
				}
			} else {
				$etiquetas = array("{id item}");
				$reemplazo = array($item->getId());
				$listaOpciones .= str_replace($etiquetas, $reemplazo, $tplItemText);
			}

			$etiquetas = array("{id item}", "{nombre item}", "{opciones item}");
			$reemplazo = array($item->getId(), $item->getNombre(), $listaOpciones);
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

$etiquetas = array("{paciente nombre}", "{paciente dni}", "{numero cama}", "{paciente registro}", "{lista secciones}");
$reemplazo = array($paciente, $paciente->getDni(), $paciente->getNumeroCama(), getFormatedDate($paciente->getRegistro()),
	$listaAsideSecciones);
$tplAside = str_replace($etiquetas, $reemplazo, $tplAside);

$etiquetas = array("{idp}", "{secciones valoraciones}");
$reemplazo = array($paciente->getId(), $listaSecciones);
$tplValoracion = str_replace($etiquetas, $reemplazo, $tplValoracion);

$scriptsLocales = array("realizar_valoracion");

$html = getHeader($scriptsLocales) . $tplAside . $tplValoracion . getFooter();

echo getHTML($html);
?>