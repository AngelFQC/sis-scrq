<?php

session_start();

include("../scrq-config.php");
include("../scrq-secure.php");

if ($_SERVER["REQUEST_METHOD"] != "GET") {
    exit();
}

if (!isset($_GET["id"])) {
    exit();
}

$idPaciente = trim($_GET["id"]);

if (!preg_match("/^[0-9]{1,}$/", $idPaciente)) {
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

$paciente = new Paciente();
$paciente->setId($idPaciente);
$count = $paciente->cantidadHojasValoracion();

if ($count > 0)
    $totalPages = ceil($count / $limit);
else
    $totalPages = 0;

if ($page > $totalPages)
    $page = $totalPages;

$start = $limit * $page - $limit;

$listaValoraciones = $paciente->listarHojasValoracion($sidx, $sord, $start, $limit);

$json = array();
$json["page"] = $page;
$json["total"] = $totalPages;
$json["records"] = $count;
$json["rows"] = array();

$i = 0;

foreach ($listaValoraciones as $hojaValoracion) {
    $json["rows"][$i]["id"] = $hojaValoracion->getId();
    $json["rows"][$i]["cell"] = array($i + 1,
        getFormatedDate($hojaValoracion->getFecha()),
        getTurno($hojaValoracion->getTurno()),
        $hojaValoracion->getEnfermera()->getNombre());

    $i++;
}

echo json_encode($json);
?>