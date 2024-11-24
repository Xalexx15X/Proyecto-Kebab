<?php

/*
    Clase para almacenar la dirección de un usuario

    Atributos:
        id_direccion (int): Identificador único de la dirección
        direccion (string): Dirección del usuario
        estado (string): Estado de la dirección (Activo o Inactivo)
        usuario_id (int): Identificador único del usuario

    Métodos:
        getIdDireccion(): Devuelve el ID de la dirección
        setIdDireccion($id_direccion): Establece el ID de la dirección
        getDireccion(): Devuelve la dirección del usuario
        setDireccion($direccion): Establece la dirección del usuario
        getEstado(): Devuelve el estado de la dirección (Activo o Inactivo)
        setEstado($estado): Establece el estado de la dirección (Activo o Inactivo)
        getUsuarioId(): Devuelve el ID del usuario
        setUsuarioId($usuario_id): Establece el ID del usuario
*/

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
