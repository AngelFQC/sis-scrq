<?php

session_start();

$json = array("state" => false, "messageHTML" => "");

if ($_SERVER["REQUEST_METHOD"] != "POST")
	exit();

if (!isset($_POST["usuario"]) || !isset($_POST["password"])) {
	$json["messageHTML"] = "<li>No se han ingresado los datos necesarios para el registro.</li>";
	echo json_encode($json);
	exit();
}

include("../scrq-config.php");
include("../tool/strings.php");

function __autoload($nombreClase) {
	require_once ("../modelo/" . $nombreClase . ".class.php");
}

$usuario = getCleanedRequest($_POST["usuario"]);
$password = getCleanedRequest($_POST["password"]);

if (empty($usuario))
	$json["messageHTML"] .= "<li>Ingrese un usuario</li>";

if (empty($password))
	$json["messageHTML"] .= "<li>Ingrese la contraseña</li>";

if (!empty($json["messageHTML"])) {
	echo json_encode($json);
	exit();
}

$password = md5($password);

$enfermera = new Enfermera();

if ($enfermera->verificar($usuario, $password)) {
	$_SESSION["enfermera"] = array();
	$_SESSION["enfermera"]["id"] = $enfermera->getId();
	$_SESSION["enfermera"]["nombre"] = $enfermera->getNombre();
	$_SESSION["enfermera"]["turno"] = "N";
	$_SESSION["enfermera"]["isadmin"] = $enfermera->getIsAdmin();

	$dateTime = new DateTime("now", new DateTimeZone("America/Lima"));
	$hour = $dateTime->format("H");

	if ($hour >= 7 && $hour < 13)
		$_SESSION["enfermera"]["turno"] = "M";
	else if ($hour >= 13 && $hour < 19)
		$_SESSION["enfermera"]["turno"] = "T";
	else if ($hour >= 0 && $hour < 7)
		$_SESSION["enfermera"]["turno"] = "N";

	$json["state"] = true;
	$json["messageHTML"] = "¡Bienvenido! " . $_SESSION["enfermera"]["nombre"];
} else {
	$json["messageHTML"] = "<li>No se puede acceder al sistema</li><li>Por favor verifique el usuario y la contraseña ingresados.</li>";
}

echo json_encode($json);
?>