<?php
class Alergenos
{
    private $id_alergenos;
    private $nombre;
    private $foto;

    public function __construct($id_alergenos, $nombre, $foto)
    {
        $this->id_alergenos = $id_alergenos;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->descripcion = $descripcion;

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

    public function __toString() {
        return "Alergeno: ID={$this->id}, Nombre={$this->nombre}, Descripcion={$this->descripcion}";
    }
}
