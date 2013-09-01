<?php
class Opcion{
	protected $id;
	private $nombre;

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

	public function registrar($idItem){
		global $conexion;

		$registroOk = false;
		$sql = "INSERT INTO
				OPCION
				(nombre, ITEM_id)
				VALUES
				('" . $conexion->real_escape_string($this->nombre) . "',
				" . $conexion->real_escape_string($idItem) . ")";

		$conexion->query($sql);

		if( $conexion->affected_rows > 0 ){
			$this->id = $conexion->insert_id;
			$registroOk = true;
		}

		return $registroOk;
	}
}
?>