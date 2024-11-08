<?php
class Ingredientes
{
    private $id_ingrediente;
    private $nombre;
    private $foto;
    private $precio;
    private $tipo;
    private $alergenos = [];


    public function __construct($id_ingrediente, $nombre, $foto, $precio, $tipo, $alergenos = [])
    {
        $this->id_ingrediente = $id_ingrediente;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->alergenos = $alergenos;
        
    }

    public function getIdIngrediente() {
        return $this->id_ingrediente;
    }

    public function setIdIngrediente($id_ingrediente) {
        $this->id_ingrediente = $id_ingrediente;
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

    public function getPrecio() {
        return $this->precio;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }
    
    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function addAlergeno(Alergenos $alergeno) {  
        $this->alergenos[] = $alergeno;
    }
    
    public function removeAlergeno($id_alergeno) {
        $this->alergenos = array_filter($this->alergenos, fn($alergeno) => $alergeno->getIdAlergenos() !== $id_alergeno);
    }

    public function __toString() {
        return "Ingrediente: ID={$this->id_ingrediente}, Nombre={$this->nombre}, Precio={$this->precio}, Tipo={$this->tipo}, Alergenos=[" . implode(", ", $this->alergenos) . "]";
    }
}
?>
