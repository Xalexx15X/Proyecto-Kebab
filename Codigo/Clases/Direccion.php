<?php

class Direccion
{
    public $id_direccion;
    public $direccion;
    public $estado;
    public $usuario_id;

    public function __construct($id_direccion, $direccion, $estado, $usuario_id)
    {
        $this->id_direccion = $id_direccion;
        $this->direccion = $direccion;
        $this->estado = $estado;
        $this->usuario_id = $usuario_id;
    }

    public function getIdDireccion() {
        return $this->id_direccion; 
    }
    
    public function setIdDireccion($id_direccion) {
        $this->id_direccion = $id_direccion;
    }
    
    public function getDireccion() {
        return $this->direccion;
    }
    
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }
    
    public function getEstado() {
        return $this->estado;
    }
    
    public function setEstado($estado) {
        $this->estado = $estado;
    }
    
    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id) {
        $this->usuario_id = $usuario_id;
    }
}
