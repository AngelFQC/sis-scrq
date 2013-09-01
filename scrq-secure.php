<?php

if (!isset($_SESSION["enfermera"])) {
	header("Location: " . WEB_SERVER . "ingresar/");

	exit();
}
?>