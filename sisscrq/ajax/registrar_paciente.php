<?php

session_start();

include("../scrq-config.php");
include("../scrq-secure.php");

$json = array("state" => false, "messageHTML" => "");

if ($_SERVER["REQUEST_METHOD"] != "POST")
	exit();

if (!isset($_POST["dni"]) || !isset($_POST["paterno"]) || !isset($_POST["materno"]) || !isset($_POST["nombres"]) ||
		!isset($_POST["cama"])) {
	$json["messageHTML"] = "<li>No se han ingresado los datos necesarios para el registro.</li>";
	echo json_encode($json);
	exit();
}

include("../tool/strings.php");

function __autoload($nombreClase) {
	require_once ("../modelo/" . $nombreClase . ".class.php");
}

$dni = getCleanedRequest($_POST["dni"]);
$paterno = getCleanedRequest($_POST["paterno"]);
$materno = getCleanedRequest($_POST["materno"]);
$nombres = getCleanedRequest($_POST["nombres"]);
$cama = getCleanedRequest($_POST["cama"]);

if (!preg_match("/^[0-9]{8}$/", $dni))
	$json["messageHTML"] .= "<li>Ingrese un número de DNI válido</li>";

if(empty($paterno))
	$json["messageHTML"] .= "<li>Ingrese el apellido paterno</li>";

if(empty($materno))
	$json["messageHTML"] .= "<li>Ingrese el apellido materno</li>";

if(empty($nombres))
	$json["messageHTML"] .= "<li>Ingrese el nombre</li>";

if(empty($cama))
	$json["messageHTML"] .= "<li>Ingrese el número de cama</li>";

if(!empty($json["messageHTML"])){
	echo json_encode($json);
	exit();
}

$paciente = new Paciente();
$paciente->setDni($dni);
$paciente->setApellidoPaterno($paterno);
$paciente->setApellidoMaterno($materno);
$paciente->setNombres($nombres);
$paciente->setNumeroCama($cama);

if($paciente->registrar()){
	$json["state"] = true;
	$json["messageHTML"] = "Paciente registrado correctamente.";
}
else{
	$json["messageHTML"] = "<li>No se registró al paciente</li><li>Por favor verifique los datos ingresados.</li>";
}

echo json_encode($json);
?>
