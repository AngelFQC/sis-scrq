<?php

session_start();

include("../scrq-config.php");
include("../scrq-secure.php");

if (!isset($_GET)) {
    exit();
}

if (!isset($_GET["term"])) {
    exit();
}


function __autoload($nombreClase) {
    require_once ("../modelo/" . $nombreClase . ".class.php");
}

$term = trim($_GET["term"]);
$term = strip_tags($term);

if (strlen($term) <= 0) {
    exit();
}

$obj = new Paciente();
$listaPacientes = $obj->buscar($term);

$json = array();

foreach ($listaPacientes as $paciente) {
    $label = $paciente->getApellidoPaterno() . " " . $paciente->getApellidoMaterno() . ", " . $paciente->getNombres();

    $json[] = array("label" => $label,
        "value" => $paciente->getId());
}

echo json_encode($json);
?>