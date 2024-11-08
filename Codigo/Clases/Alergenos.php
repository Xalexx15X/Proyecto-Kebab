<?php
class Alergenos
{
    private $id_alergenos;
    private $nombre;
    private $foto;
    private $ingredientes = [];
    private $usuarios = []; 

    public function __construct($id_alergenos, $nombre, $foto ,$ingredientes = [], $usuarios = [])  
    {
        $this->id_alergenos = $id_alergenos;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->descripcion = $descripcion;
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
    public function getDescripcion() {
        return $this->descripcion;
    }
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    
    public function addIngrediente(Ingredientes $ingrediente) {
        $this->ingredientes[] = $ingrediente;
    }
    
    public function removeIngrediente($id_ingrediente) {
        $this->ingredientes = array_filter($this->ingredientes, fn($ingrediente) => $ingrediente->getIdIngrediente() !== $id_ingrediente);
    }

    public function __toString() {
        return "Alergeno: ID={$this->id}, Nombre={$this->nombre}, Descripcion={$this->descripcion}";
    }
}
