<?php

session_start();

include("../scrq-config.php");
include("../scrq-secure.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    exit();
}

if (!isset($_POST["idp"])) {
    exit();
}

$idPaciente = trim($_POST["idp"]);

if (!preg_match("/^[0-9]{1,}$/", $idPaciente)) {
    exit();
}

function __autoload($nombreClase) {
    require_once ("../modelo/" . $nombreClase . ".class.php");
}

$hojaValoracion = new HojaValoracion();
$hojaValoracion->setTurno($_SESSION["enfermera"]["turno"]);
$hojaValoracion->registrar($idPaciente, $_SESSION["enfermera"]["id"]);

$registroOk = true; // Debería ser una transacción esto

foreach ($_POST["text"] as $name => $value) {
    $respuestaAbierta = new RespuestaAbierta();
    $respuestaAbierta->setRespuesta($value);

    if (!$respuestaAbierta->registrar($hojaValoracion->getId(), $name))
        $registroOk = false;
}

foreach ($_POST["opt"] as $name => $value) {
    $respuestaCerrada = new RespuestaCerrada();

    if (!$respuestaCerrada->registrar($hojaValoracion->getId(), $value, $name))
        $registroOk = false;
}

$json = array();

if ($registroOk) {
    $json["state"] = true;
    $json["msg"] = "Registro de valoración diaria de enfermería, correcto.";
} else {
    $json["state"] = false;
    $json["msg"] = "No se pudo registrar la valoración diaria de enfermería.";
}

$json["id"] = $hojaValoracion->getId();

echo json_encode($json);
?>
