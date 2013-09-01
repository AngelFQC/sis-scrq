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

if (!isset($_POST["id"])) {
	$json["messageHTML"] = "<li>No se ha especificado el usuario a eliminar.</li>";
	echo json_encode($json);
	exit();
}

include("../tool/strings.php");

function __autoload($nombreClase) {
	require_once ("../modelo/" . $nombreClase . ".class.php");
}

$id = getCleanedRequest($_POST["id"]);

if (!preg_match("/^[0-9]{1,}$/", $id))
	$json["messageHTML"] .= "<li>Especifique el usuario</li>";

$usuario = new Enfermera();
$usuario->setId($id);

if($usuario->deshabilitar()){
	$json["state"] = true;
	$json["messageHTML"] = "El usuario ha sido deshabilitado.";
}
else{
	$json["messageHTML"] = "<li>No se deshabilitó el usuario.</li>";
}

echo json_encode($json);
?>