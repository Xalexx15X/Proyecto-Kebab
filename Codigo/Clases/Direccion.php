<?php

class Direccion
{
    private $id_direccion;
    private $direccion;
    private $estado;
    private $usuario = [];

    public function __construct($id_direccion, $direccion, $estado, $usuario = [])
    {
        $this->id_direccion = $id_direccion;
        $this->direccion = $direccion;
        $this->estado = $estado;
        $this->usuario = $usuario;
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
        return isset($this->usuario['id_usuario']) ? $this->usuario['id_usuario'] : null;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function __toString() {
        return "Direccion: ID={$this->id_direccion}, Direccion={$this->direccion}, Estado={$this->estado}, Usuario={$this->usuario}";
    }
}   