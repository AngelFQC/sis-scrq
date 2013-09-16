<?php

class Grupo {

    protected $id;
    private $datos;
    private $items;

    public function __construct() {
        $this->items = array();
    }

    public function setId($val) {
        $this->id = $val;
    }

    public function getId() {
        return $this->id;
    }

    public function setDatos($val) {
        $this->datos = $val;
    }

    public function getDatos() {
        return $this->datos;
    }

    public function addItem(Item $item) {
        $this->items[] = $item;
    }

    public function getItems() {
        return $this->items;
    }

    public function listarItems() {
        global $conexion;

        $sql = "SELECT	id, nombre
				FROM	ITEM
				WHERE	GRUPO_id = " . $conexion->real_escape_string($this->id);

        if ($result = $conexion->query($sql)) {
            if ($conexion->affected_rows > 0) {
                while ($i = $result->fetch_object()) {
                    $item = new Item();
                    $item->setId($i->id);
                    $item->setNombre($i->nombre);

                    $this->items[] = $item;
                }
            }
        }

        return $this->items;
    }

    public function registrar($idSection) {
        global $conexion;

        $registroOk = false;
        $sql = "INSERT INTO
				GRUPO
				(descripcion, SECCION_id)
				VALUES
				('" . $conexion->real_escape_string($this->datos) . "',
				  " . $conexion->real_escape_string($idSection) . ")";

        $conexion->query($sql);

        if ($conexion->affected_rows > 0) {
            $this->id = $conexion->insert_id;
            $registroOk = true;
        }

        return $registroOk;
    }

}

?>