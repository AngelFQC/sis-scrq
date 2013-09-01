<?php

class Paciente {

    protected $id;
    private $apellidoPaterno;
    private $apellidoMaterno;
    private $nombres;
    private $dni;
	private $numeroCama;
    private $registro;
    private $hojasValoracion;

    public function setId($val) {
        $this->id = $val;
    }

    public function getId() {
        return $this->id;
    }

    public function setApellidoPaterno($val) {
        $this->apellidoPaterno = $val;
    }

    public function getApellidoPaterno() {
        return $this->apellidoPaterno;
    }

    public function setApellidoMaterno($val) {
        $this->apellidoMaterno = $val;
    }

    public function getApellidoMaterno() {
        return $this->apellidoMaterno;
    }

    public function setNombres($val) {
        $this->nombres = $val;
    }

    public function getNombres() {
        return $this->nombres;
    }

    public function setDni($val) {
        $this->dni = $val;
    }

    public function getDni() {
        return $this->dni;
    }
	
	public function setNumeroCama($numero){
		$this->numeroCama = $numero;
	}
	
	public function getNumeroCama(){
		return $this->numeroCama;
	}

    public function setRegistro($val) {
        $this->registro = $val;
    }

    public function getRegistro() {
        return $this->registro;
    }

    public function addHojaValoracion(HojaValoracion $hoja) {
        $this->hojasValoracion[] = $hoja;
    }

    public function getHojasValoracion() {
        return $this->hojasValoracion;
    }

    public function obtener() {
        global $conexion;

        $sql = "SELECT	apellido_paterno, apellido_materno, nombres, dni, cama, registro
				FROM	PACIENTE
				WHERE	id = " . $conexion->real_escape_string($this->id);

        if ($result = $conexion->query($sql)) {
            if ($conexion->affected_rows > 0) {
                while ($p = $result->fetch_object()) {
                    $this->apellidoPaterno = $p->apellido_paterno;
                    $this->apellidoMaterno = $p->apellido_materno;
                    $this->nombres = $p->nombres;
                    $this->dni = $p->dni;
					$this->numeroCama = $p->cama;
                    $this->registro = $p->registro;
                }
            }
        }
    }

    public function registrar() {
        global $conexion;

        $registroOk = false;
        $sql = "INSERT INTO
				PACIENTE
				(apellido_paterno, apellido_materno, nombres, dni, cama, registro)
				VALUES
				('" . $conexion->real_escape_string($this->apellidoPaterno) . "',
				 '" . $conexion->real_escape_string($this->apellidoMaterno) . "',
				 '" . $conexion->real_escape_string($this->nombres) . "',
				 '" . $conexion->real_escape_string($this->dni) . "',
				" . $conexion->real_escape_string($this->numeroCama) . ",
				 CURRENT_TIMESTAMP)";

        $conexion->query($sql);

        if ($conexion->affected_rows > 0) {
            $this->id = $conexion->insert_id;
            $registroOk = true;
        }

        return $registroOk;
    }

    public function buscar($search) {
        global $conexion;

        $pacientes = array();
        $sql = "SELECT	id, apellido_paterno, apellido_materno, nombres, dni, registro
				FROM	PACIENTE
				WHERE	apellido_paterno LIKE '" . $conexion->real_escape_string($search) . "%'
				OR		apellido_materno LIKE '" . $conexion->real_escape_string($search) . "%'
				OR		nombres LIKE '%" . $conexion->real_escape_string($search) . "%'
				OR		dni LIKE '" . $conexion->real_escape_string($search) . "%'
				ORDER BY	apellido_paterno, apellido_materno, nombres ASC
				LIMIT	0, 10";

        if ($result = $conexion->query($sql)) {
            if ($conexion->affected_rows > 0) {
                while ($p = $result->fetch_object()) {
                    $paciente = new Paciente();
                    $paciente->setId($p->id);
                    $paciente->setApellidoPaterno($p->apellido_paterno);
                    $paciente->setApellidoMaterno($p->apellido_materno);
                    $paciente->setNombres($p->nombres);
                    $paciente->setDni($p->dni);
                    $paciente->setRegistro($p->registro);

                    $pacientes[] = $paciente;
                }
            }
        }

        return $pacientes;
    }

    public function cantidadHojasValoracion() {
        global $conexion;

        $cantidad = 0;
        $sql = "SELECT	COUNT(1) AS count
				FROM	HOJA_VALORACION
				WHERE	PACIENTE_id = " . $conexion->real_escape_string($this->id);

        if ($result = $conexion->query($sql)) {
            $c = $result->fetch_object();
            $cantidad = $c->count;
        }

        return $cantidad;
    }

    public function listarHojasValoracion($orderBy, $order, $start, $limit) {
        global $conexion;

        $this->hojasValoracion = array();
        $sql = "SELECT	HV.id, HV.fecha, HV.turno, E.nombre
				FROM	HOJA_VALORACION AS HV
				JOIN	ENFERMERA AS E	ON HV.ENFERMERA_id = E.id
				JOIN	PACIENTE AS P	ON HV.PACIENTE_id = P.id
				WHERE	P.id = " . $conexion->real_escape_string($this->id) . "
				ORDER BY	HV.$orderBy $order
				LIMIT	$start, $limit";

        if ($result = $conexion->query($sql)) {
            if ($conexion->affected_rows > 0) {
                while ($hv = $result->fetch_object()) {
                    $hojaValoracion = new HojaValoracion();
                    $hojaValoracion->setId($hv->id);
                    $hojaValoracion->setFecha($hv->fecha);
                    $hojaValoracion->setTurno($hv->turno);

                    $enfermera = new Enfermera();
                    $enfermera->setNombre($hv->nombre);

                    $hojaValoracion->setEnfermera($enfermera);

                    $this->hojasValoracion[] = $hojaValoracion;
                }
            }
        }

        return $this->hojasValoracion;
    }

    public function getHojaValoracion($idValoracion) {
        global $conexion;

        $hojaValoracion = new HojaValoracion();
        $sql = "SELECT	HV.id, HV.fecha, HV.turno, E.nombre
				FROM	HOJA_VALORACION AS HV
				JOIN	ENFERMERA AS E	ON HV.ENFERMERA_id = E.id
				JOIN	PACIENTE AS P	ON HV.PACIENTE_id = P.id
				WHERE	HV.id = " . $conexion->real_escape_string($idValoracion);

        if ($result = $conexion->query($sql)) {
            if ($conexion->affected_rows > 0) {
                $hv = $result->fetch_object();

                $hojaValoracion->setId($hv->id);
                $hojaValoracion->setFecha($hv->fecha);
                $hojaValoracion->setTurno($hv->turno);

                $enfermera = new Enfermera();
                $enfermera->setNombre($hv->nombre);

                $hojaValoracion->setEnfermera($enfermera);
            }
        }

        return $hojaValoracion;
    }

    public function __toString() {
        return $this->apellidoPaterno . ' ' . $this->apellidoMaterno . ', ' . $this->nombres;
    }

}

?>