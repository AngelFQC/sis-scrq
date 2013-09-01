<?php

session_start();

include("../scrq-config.php");
include("../scrq-secure.php");

if ($_SERVER["REQUEST_METHOD"] != "GET") {
    exit();
}

if(!$_SESSION["enfermera"]["isadmin"]){
	exit();
}

include("../tool/strings.php");

function __autoload($nombreClase) {
    require_once ("../modelo/" . $nombreClase . ".class.php");
}

$page = $_REQUEST["page"];
$limit = $_GET["rows"];
$sidx = $_GET["sidx"];
$sord = $_GET["sord"];

if (!$sidx)
    $sidx = 1;

$enfermera = new Enfermera();
$count = $enfermera->cantidadUsuarios();

if ($count > 0)
    $totalPages = ceil($count / $limit);
else
    $totalPages = 0;

if ($page > $totalPages)
    $page = $totalPages;

$start = $limit * $page - $limit;

$listaUsuarios = $enfermera->listarUsuarios($sidx, $sord, $start, $limit);

$json = array();
$json["page"] = $page;
$json["total"] = $totalPages;
$json["records"] = $count;
$json["rows"] = array();

$i = 0;

foreach ($listaUsuarios as $usuario) {
    $json["rows"][$i]["id"] = $usuario->getId();
    $json["rows"][$i]["cell"] = array($usuario->getDNI(),
		$usuario->getNombre(),
        $usuario->getUsuario());

    $i++;
}

echo json_encode($json);
?>