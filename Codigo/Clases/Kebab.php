<?php
class Kebab
{
    private $id_kebab;
    private $nombre;
    private $foto;
    private $precio_min;
    private $descripcion;

    public function __construct($id_kebab, $nombre, $foto, $precio_min, $descripcion)
    {
        $this->id_kebab = $id_kebab;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->precio_min = $precio_min;
        $this->descripcion = $descripcion;
    }

    public function getIdKebab() {
        return $this->id_kebab;
    }

    public function setIdKebab($id_kebab) {
        $this->id_kebab = $id_kebab;
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

    public function getPrecioMin() {
        return $this->precio_min;
    }

    public function setPrecioMin($precio_min) {
        $this->precio_min = $precio_min;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function __toString() {
        return "Kebab: ID={$this->id}, Nombre={$this->nombre}, Precio Min={$this->precio_min}, Descripcion={$this->descripcion}";
    }
}
?>
