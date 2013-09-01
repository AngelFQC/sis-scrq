<?php
class Item{
	protected $id;
	private $nombre;

	private $opciones;
	private $respuestaAbierta;

	public function __construct(){
		$this->opciones = array();
	}

	public function setId($val){
		$this->id = $val;
	}
	public function getId(){
		return $this->id;
	}

	public function setNombre($val){
		$this->nombre = $val;
	}
	public function getNombre(){
		return $this->nombre;
	}

	public function addOpcion(Opcion $opcion){
		$this->opciones[] = $opcion;
	}
	public function getOpciones(){
		return $this->opciones;
	}

	public function setRespuestaAbierta(RespuestaAbierta $val){
		$this->respuestaAbierta = $val;
	}
	public function getRespuestaAbierta(){
		return $this->respuestaAbierta;
	}

	public function listarOpciones(){
		global $conexion;

		$sql = "SELECT	id, nombre
				FROM	OPCION
				WHERE	ITEM_id = " . $conexion->real_escape_string($this->id);

		if( $result = $conexion->query($sql) ){
			if( $conexion->affected_rows > 0 ){
				while( $o = $result->fetch_object() ){
					$opcion = new Opcion();
					$opcion->setId($o->id);
					$opcion->setNombre($o->nombre);

					$this->opciones[] = $opcion;
				}
			}
		}

		return $this->opciones;
	}

	public function obtenerRespuestaAbierta(){
		$sql = "";

		return $this->respuestaAbierta;
	}

	public function registrar($idGrupo){
		global $conexion;

		$registroOk = false;
		$sql = "INSERT INTO
				ITEM
				(nombre, GRUPO_id)
				VALUES
				('" . $conexion->real_escape_string($this->nombre) . "',
				" . $conexion->real_escape_string($idGrupo) . ")";

		$conexion->query($sql);

		if( $conexion->affected_rows > 0 ){
			$this->id = $conexion->insert_id;
			$registroOk = true;
		}

		return $registroOk;
	}
}
?>