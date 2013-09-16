<?php
class RespuestaAbierta{
	private $respuesta;

	public function setRespuesta($val){
		$this->respuesta = $val;
	}
	public function getRespuesta(){
		return $this->respuesta;
	}

	public function registrar($idHojaValoracion, $idItem){
		global $conexion;

		$registroOk = false;
		$sql = "INSERT INTO
				RESPUESTA_ABIERTA
				(HOJA_VALORACION_id, ITEM_id, respuesta)
				VALUES
				(" . $conexion->real_escape_string($idHojaValoracion) . ",
				 " . $conexion->real_escape_string($idItem) . ",
				'" . $conexion->real_escape_string($this->respuesta) . "')";

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