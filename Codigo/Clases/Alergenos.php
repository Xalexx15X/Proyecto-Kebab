<?php
class Alergenos
{
    private $id_alergenos;
    private $nombre;
    private $foto;
    private $ingredientes = [];
    private $usuarios = [];

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
