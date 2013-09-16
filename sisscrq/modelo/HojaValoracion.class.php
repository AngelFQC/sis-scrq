<?php

class HojaValoracion {

	protected $id;
	private $turno;
	private $fecha;
	private $repuestaAbierta;
	private $respuestaCerrada;
	private $enfermera;

	public function setId($val) {
		$this->id = $val;
	}

	public function getId() {
		return $this->id;
	}

	public function setTurno($val) {
		$this->turno = $val;
	}

	public function getTurno() {
		return $this->turno;
	}

	public function setFecha($val) {
		$this->fecha = $val;
	}

	public function getFecha() {
		return $this->fecha;
	}

	public function setEnfermera($e) {
		$this->enfermera = $e;
	}

	public function getEnfermera() {
		return $this->enfermera;
	}

	public function registrar($idPaciente, $idEnfermera) {
		global $conexion;

		$registroOk = false;
		$sql = "INSERT INTO
				HOJA_VALORACION
				(turno, ENFERMERA_id, PACIENTE_id)
				VALUES
				('" . $conexion->real_escape_string($this->turno) . "',
				 " . $conexion->real_escape_string($idEnfermera) . ",
				 " . $conexion->real_escape_string($idPaciente) . ")";

		$conexion->query($sql);

		if ($conexion->affected_rows > 0) {
			$registroOk = true;
			$this->id = $conexion->insert_id;
		}

		return $registroOk;
	}

	public function obtener() {
		global $conexion;

		$sql = "SELECT	HV.id, HV.fecha, HV.turno, E.nombre
				FROM	HOJA_VALORACION AS HV
				JOIN	ENFERMERA AS E	ON HV.ENFERMERA_id = E.id
				JOIN	PACIENTE AS P	ON HV.PACIENTE_id = P.id
				WHERE	HV.id = " . $conexion->real_escape_string($this->id);

		if ($result = $conexion->query($sql)) {
			if ($conexion->affected_rows > 0) {
				$hv = $result->fetch_object();

				$this->id = $hv->id;
				$this->fecha = $hv->fecha;
				$this->turno = $hv->turno;

				$enfermera = new Enfermera();
				$enfermera->setNombre($hv->nombre);

				$this->enfermera = $enfermera;
			}
		}
	}

	public function obtenerPaciente() {
		global $conexion;

		$paciente = NULL;
		$sql = "SELECT	P.id, P.apellido_paterno, P.apellido_materno, P.nombres, P.dni, P.registro, P.cama
				FROM	PACIENTE AS P
				JOIN	HOJA_VALORACION AS HV	ON	P.id = HV.PACIENTE_id
				WHERE	HV.id = " . $conexion->real_escape_string($this->id);

		if ($result = $conexion->query($sql)) {
			if ($conexion->affected_rows > 0) {
				$p = $result->fetch_object();

				$paciente = new Paciente();
				$paciente->setId($p->id);
				$paciente->setApellidoPaterno($p->apellido_paterno);
				$paciente->setApellidoMaterno($p->apellido_materno);
				$paciente->setNombres($p->nombres);
				$paciente->setDni($p->dni);
				$paciente->setNumeroCama($p->cama);
				$paciente->setRegistro($p->registro);
			}
		}

		return $paciente;
	}

	public function obtenerRespuestaAbierta($idItem) {
		global $conexion;

		$this->repuestaAbierta = NULL;
		$sql = "SELECT	respuesta
				FROM	RESPUESTA_ABIERTA
				WHERE	ITEM_id = " . $conexion->real_escape_string($idItem) . "
				AND		HOJA_VALORACION_id = " . $conexion->real_escape_string($this->id);

		if ($result = $conexion->query($sql)) {
			if ($conexion->affected_rows > 0) {
				$r = $result->fetch_object();

				$this->repuestaAbierta = new RespuestaAbierta();
				$this->repuestaAbierta->setRespuesta($r->respuesta);
			}
		}

		return $this->repuestaAbierta;
	}

	public function obtenerRespuestaCerrada($idItem) {
		global $conexion;

		$this->respuestaCerrada = NULL;
		$sql = "SELECT	O.nombre
				FROM	OPCION AS O
				JOIN	RESPUESTA_CERRADA AS RC	ON	O.id = RC.OPCION_id
				WHERE	RC.OPCION_ITEM_id = " . $conexion->real_escape_string($idItem) . "
				AND		RC.HOJA_VALORACION_id = " . $conexion->real_escape_string($this->id);

		if ($result = $conexion->query($sql)) {
			if ($conexion->affected_rows > 0) {
				$r = $result->fetch_object();

				$opcion = new Opcion();
				$opcion->setNombre($r->nombre);

				$this->respuestaCerrada = new RespuestaCerrada();
				$this->respuestaCerrada->setOpcion($opcion);
			}
		} else {
			echo $conexion->error;
		}

		return $this->respuestaCerrada;
	}

}

?>