<?php
/*
    Clase para almacenar los alérgenos de un ingrediente    
    
    Atributos:
        id_alergenos (int): Identificador único del alérgeno
        nombre (string): Nombre del alérgeno
        foto (string): URL de la foto del alérgeno
        ingredientes (array): Lista de ingredientes al que pertenece el alérgeno        
        usuarios (array): Lista de usuarios que tienen el alérgeno
    
    Métodos:
        getIdAlergenos(): Devuelve el ID del alérgeno
        setIdAlergenos($id_alergenos): Establece el ID del alérgeno
        getNombre(): Devuelve el nombre del alérgeno
        setNombre($nombre): Establece el nombre del alérgeno
        getFoto(): Devuelve la URL de la foto del alérgeno
        setFoto($foto): Establece la URL de la foto del alérgeno
        getIngredientes(): Devuelve la lista de ingredientes al que pertenece el alérgeno
        setIngredientes($ingredientes): Establece la lista de ingredientes al que pertenece el alérgeno
        getUsuarios(): Devuelve la lista de usuarios que tienen el alérgeno
        setUsuarios($usuarios): Establece la lista de usuarios que tienen el alérgeno
        __toString(): Devuelve una representación de la clase como string   
*/

class Alergenos
{
    public $id_alergenos;
    public $nombre;
    public $foto;
    public $ingredientes = [];
    public $usuarios = [];

    public function __construct($id_alergenos, $nombre, $foto, $ingredientes = [], $usuarios = [])
    {
        $this->id_alergenos = $id_alergenos;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->ingredientes = $ingredientes;
        $this->usuarios = $usuarios;
    }

    public function getIdAlergenos() {
        return $this->id_alergenos;
    }

    public function setIdAlergenos($id_alergenos) {
        $this->id_alergenos = $id_alergenos;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function setFoto($foto) {
        $this->foto = $foto;
    }
    
    public function getIngredientes() {
        return $this->ingredientes;
    }

    public function setIngredientes($ingredientes) {
        $this->ingredientes = $ingredientes;
    }

    public function getUsuarios() {
        return $this->usuarios;
    }

    public function setUsuarios($usuarios) {
        $this->usuarios = $usuarios;
    }

    public function __toString() {
        return "Alergeno: ID={$this->id_alergenos}, Nombre={$this->nombre}, Ingredientes=[" . implode(", ", $this->ingredientes) . "] Usuarios=[" . implode(", ", $this->usuarios) . "]";
    }


}
