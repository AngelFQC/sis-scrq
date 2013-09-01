<?php
class Seccion{
	protected $id;
	private $titulo;
	private $descripcion;

	private $grupos;

	public function __construct(){
		$this->grupos = array();
	}

	public function setId($val){
		$this->id = $val;
	}
	public function getId(){
		return $this->id;
	}

	public function setTitulo($val)	{
		$this->titulo = $val;
	}
	public function getTitulo(){
		return $this->titulo;
	}

	public function setDescripcion($val){
		$this->descripcion = $val;
	}
	public function getDescripcion(){
		return $this->descripcion;
	}

	public function addGrupo(Grupo $grupo){
		$this->grupos[] = $grupo;
	}
	public function getGrupos(){
		return $this->grupos;
	}

	public function listarGrupos(){
		global $conexion;

		$sql = "SELECT	id, descripcion
				FROM	GRUPO
				WHERE	SECCION_id = " . $conexion->real_escape_string($this->id);

		if( $result = $conexion->query($sql) ){
			if( $conexion->affected_rows > 0 ){
				while( $g = $result->fetch_object() ){
					$grupo = new Grupo();
					$grupo->setId($g->id);
					$grupo->setDatos($g->descripcion);

					$this->grupos[] = $grupo;
				}
			}
		}

		return $this->grupos;
	}

	public function registrar(){
		global $conexion;

		$registroOk = false;
		$sql = "INSERT INTO
				SECCION
				(titulo)
				VALUES
				('" . $conexion->real_escape_string($this->titulo) . "')";

		$conexion->query($sql);

		if( $conexion->affected_rows > 0 ){
			$this->id = $conexion->insert_id;
			$registroOk = true;
		}

		return $registroOk;
	}

	public function listar(){
		global $conexion;

		$lista = array();
		$sql = "SELECT	id, titulo, descripcion
				FROM	SECCION";

		if( $result = $conexion->query($sql) ){
			if( $conexion->affected_rows > 0 ){
				while( $row = $result->fetch_object() ){
					$seccion = new Seccion();
					$seccion->setId($row->id);
					$seccion->setTitulo($row->titulo);
					$seccion->setDescripcion($row->descripcion);

					$lista[] = $seccion;
				}
			}
		}

		return $lista;
	}
}
?>