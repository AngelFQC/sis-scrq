<?php

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

include("../scrq-config.php");
include("../tool/strings.php");

function __autoload($nombreClase) {
    require_once ("../modelo/" . $nombreClase . ".class.php");
}

$paciente = new Paciente();
$paciente->setId($idPaciente);
$paciente->obtener();

$json = array("apellidoPaterno" => $paciente->getApellidoPaterno(),
    "apellidoMaterno" => $paciente->getApellidoMaterno(),
    "nombres" => $paciente->getNombres(),
    "dni" => $paciente->getDni(),
    "registro" => getFormatedDate($paciente->getRegistro()));

echo json_encode($json);
?>