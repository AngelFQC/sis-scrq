<?php

class Enfermera {

	protected $id;
	private $nombre;
	private $dni;
	private $usuario;
	private $password;
	private $isAdmin;
	private $hojasValoracion;

	public function setId($val) {
		$this->id = $val;
	}

	public function getId() {
		return $this->id;
	}

	public function setNombre($val) {
		$this->nombre = $val;
	}

	public function getNombre() {
		return $this->nombre;
	}

	public function setDni($val) {
		$this->dni = $val;
	}

	public function getDni() {
		return $this->dni;
	}

	public function setUsuario($val) {
		$this->usuario = $val;
	}

	public function getUsuario() {
		return $this->usuario;
	}

	public function setPassword($val) {
		$this->password = $val;
	}

	public function getPassword() {
		return $this->password;
	}
	
	public function getIsAdmin(){
		return $this->isAdmin;
	}

	public function addHojaValoracion($hoja) {
		$this->hojasValoracion[] = $hoja;
	}

	public function getHojasValoracion() {
		return $this->hojasValoracion;
	}

	public function listarHojasValoracion() {
		global $conexion;

		$sql = "";

		return $this->hojasValoracion;
	}

	public function verificar($usuario, $password) {
		global $conexion;
		$existe = false;

		$sql = "SELECT	id, nombre, admin
				FROM	ENFERMERA
				WHERE	usuario = '" . $conexion->real_escape_string($usuario) . "'
				AND		password = '" . $conexion->real_escape_string($password) . "'
				AND		estado = 1";

		if ($result = $conexion->query($sql)) {
			if ($conexion->affected_rows > 0) {
				$existe = true;
				$e = $result->fetch_object();

				$this->id = $e->id;
				$this->nombre = $e->nombre;
				$this->isAdmin = (bool) $e->admin;
			}
		}

		return $existe;
	}

	public function registrar(){
		global $conexion;
		$registroOk = false;
		
		$sql = "INSERT INTO
				ENFERMERA
				(nombre, dni, usuario, password, estado)
				VALUES
				('" . $conexion->real_escape_string($this->nombre) . "',
				'" . $conexion->real_escape_string($this->dni) . "',
				'" . $conexion->real_escape_string($this->usuario) . "',
				'" . $conexion->real_escape_string($this->password) . "', 
				1)";
		
		$conexion->query($sql);
		
		if($conexion->affected_rows > 0){
			$registroOk = true;
			
			$this->id = $conexion->insert_id;
		}
		
		return $registroOk;
	}
	
	public function cantidadUsuarios(){
		global $conexion;

        $cantidad = 0;
        $sql = "SELECT	COUNT(1) AS count
				FROM	ENFERMERA
				WHERE	estado = 1";

        if ($result = $conexion->query($sql)) {
            $c = $result->fetch_object();
            $cantidad = $c->count;
        }

        return $cantidad;
	}

	public function listarUsuarios($orderBy, $order, $start, $limit) {
        global $conexion;

        $listaUsuarios = array();

		$sql = "SELECT	id, nombre, dni, usuario
				FROM	ENFERMERA
				WHERE	estado = 1
				ORDER	BY " . $conexion->real_escape_string($orderBy) . " " . $conexion->real_escape_string($order) . "
				LIMIT	" . $conexion->real_escape_string($start) . ", " . $conexion->real_escape_string($limit);

        if ($result = $conexion->query($sql)) {
            if ($conexion->affected_rows > 0) {
                while ($e = $result->fetch_object()) {
					$usuario = new Enfermera();
					$usuario->setId($e->id);
					$usuario->setUsuario($e->usuario);
					$usuario->setDni($e->dni);
					$usuario->setNombre($e->nombre);
					
					$listaUsuarios[] = $usuario;
                }
            }
        }

        return $listaUsuarios;
    }

	public function deshabilitar(){
		global $conexion;
		$registroOk = false;
		
		$sql = "UPDATE	ENFERMERA
				SET		estado = 0
				WHERE	id = " . $conexion->real_escape_string($this->id);
		
		$conexion->query($sql);
		
		if($conexion->affected_rows > 0){
			$registroOk = true;
		}
		
		return $registroOk;
	}

	public function __toString() {
		return $this->nombre;
	}

}

?>