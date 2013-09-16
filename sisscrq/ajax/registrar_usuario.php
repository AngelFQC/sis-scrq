<?php

session_start();

include("../scrq-config.php");
include("../scrq-secure.php");

$json = array("state" => false, "messageHTML" => "");

if ($_SERVER["REQUEST_METHOD"] != "POST")
	exit();

if(!$_SESSION["enfermera"]["isadmin"]){
	$json["messageHTML"] = "<li>Usted no es un usuario administrador.</li>";
	echo json_encode($json);
	exit();
}

if (!isset($_POST["dni"]) || !isset($_POST["nombre"]) || !isset($_POST["usuario"]) || !isset($_POST["password"])) {
	$json["messageHTML"] = "<li>No se han ingresado los datos necesarios para el registro.</li>";
	echo json_encode($json);
	exit();
}

include("../tool/strings.php");

function __autoload($nombreClase) {
	require_once ("../modelo/" . $nombreClase . ".class.php");
}

$dni = getCleanedRequest($_POST["dni"]);
$nombre = getCleanedRequest($_POST["nombre"]);
$usuario = getCleanedRequest($_POST["usuario"]);
$password = getCleanedRequest($_POST["password"]);

if (!preg_match("/^[0-9]{8}$/", $dni))
	$json["messageHTML"] .= "<li>Ingrese un número de DNI válido</li>";

if(empty($nombre))
	$json["messageHTML"] .= "<li>Ingrese el nombre</li>";

if(empty($usuario))
	$json["messageHTML"] .= "<li>Ingrese el usuario</li>";

if(empty($password))
	$json["messageHTML"] .= "<li>Ingrese el password</li>";

if(!empty($json["messageHTML"])){
	echo json_encode($json);
	exit();
}

$password = md5($password);

$enfermera = new Enfermera();
$enfermera->setDni($dni);
$enfermera->setNombre($nombre);
$enfermera->setUsuario($usuario);
$enfermera->setPassword($password);

if($enfermera->registrar()){
	$json["state"] = true;
	$json["messageHTML"] = "Usuario registrado correctamente.<br>$enfermera (" . $enfermera->getUsuario() . ")";
}
else{
	$json["messageHTML"] = "<li>No se registró al usuario</li><li>Por favor verifique los datos ingresados.</li>";
}

echo json_encode($json);
?>