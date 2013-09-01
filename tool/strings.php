<?php

/**
 * Devuelve una fecha formateada y en al español.
 * @param string $unformatedDate Fecha sin formatear (YY-mm-dd hh:mm:ss). Por defecto la fecha actual
 * @param string $stringFormat (Opcional) Formato saliente de la fecha. Por defecto l d F Y\, h:i a
 * @return string Fecha formateada (Lun 06 agosto 2012, 08:00 am)
 */
function getFormatedDate($unformatedDate = '', $stringFormat = "l d F Y\, h:i a") {
	if ($unformatedDate == '')
		$unformatedDate = "now";

	$date = new DateTime($unformatedDate, new DateTimeZone("America/Lima"));
	$formatedDate = $date->format($stringFormat);

	$namesDaysEnglish = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	$namesDaysSpanish = array("Lun", "Mar", "Mie", "Jue", "Vie", "Sab", "Dom");

	$namesMonthsEnglish = array("January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December");
	$namesMonthsSpanish = array("ene", "feb", "mar", "abr", "may", "jun",
		"jul", "ago", "sept", "oct", "nov", "dic");

	$formatedDate = str_replace($namesDaysEnglish, $namesDaysSpanish, $formatedDate);
	$formatedDate = str_replace($namesMonthsEnglish, $namesMonthsSpanish, $formatedDate);

	return $formatedDate;
}

/**
 * Limpia los datos enviados por POST o GET
 * @param string $request Dato a limpiar
 * @return string Dato limpio
 */
function getCleanedRequest($request, $isHTML = false) {
	$cleanedRequest = trim($request);

	if ($isHTML)
		$cleanedRequest = htmlspecialchars($request);
	else
		$cleanedRequest = strip_tags($request);

	return $cleanedRequest;
}

/**
 * Develve el turno en texto completo (Mañana, tarde, noche)
 * @return string Turno
 */
function getTurno($t = "") {
	if($t == "")
		$t = $_SESSION["enfermera"]["turno"];

	$turno = "";

	switch ($t) {
		case "T": $turno = "Tarde";
			break;
		case "N": $turno = "Noche";
			break;
		default: $turno = "Mañana";
	}

	return $turno;
}

?>