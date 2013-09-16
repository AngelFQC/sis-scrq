<?php
class RespuestaCerrada{
	private $opcion;

	public function setOpcion($o){
		$this->opcion = $o;
	}

	public function getOpcion(){
		return $this->opcion;
	}

	public function registrar($idHojaValoracion, $idOpcion, $idItem){
		global $conexion;

		$registroOk = false;
		$sql = "INSERT INTO
				RESPUESTA_CERRADA
				(HOJA_VALORACION_id, OPCION_id, OPCION_ITEM_id)
				VALUES
				(" . $conexion->real_escape_string($idHojaValoracion) . ",
				 " . $conexion->real_escape_string($idOpcion) . ",
				 " . $conexion->real_escape_string($idItem) . ")";

		$conexion->query($sql);

		if( $conexion->affected_rows > 0 ){
			$registroOk = true;
		}
		else{
			echo $conexion->error . "<br />";
		}

		return $registroOk;
	}
}
?>