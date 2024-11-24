<?php
/*
    Clase para almacenar los ingredientes de un pedido
    
    Atributos:
        id_ingrediente (int): Identificador único del ingrediente
        nombre (string): Nombre del ingrediente
        foto (string): URL de la foto del ingrediente
        precio (float): Precio del ingrediente
        alergenos (array): Lista de alérgenos del ingrediente
        
    Métodos:
        getIdIngrediente(): Devuelve el ID del ingrediente
        setIdIngrediente($id_ingrediente): Establece el ID del ingrediente
        getNombre(): Devuelve el nombre del ingrediente
        setNombre($nombre): Establece el nombre del ingrediente
        getFoto(): Devuelve la URL de la foto del ingrediente
        setFoto($foto): Establece la URL de la foto del ingrediente
        getPrecio(): Devuelve el precio del ingrediente
        setPrecio($precio): Establece el precio del ingrediente
        getAlergenos(): Devuelve la lista de alérgenos del ingrediente
        setAlergenos($alergenos): Establece la lista de alérgenos del ingrediente
        __toString(): Devuelve una representación de la clase como string   
        
*/

class Ingredientes
{
    public $id_ingrediente;
    public $nombre;
    public $foto;
    public $precio;
    public $alergenos = [];


    public function __construct($id_ingrediente, $nombre, $foto, $precio, $alergenos = [])
    {
        $this->id_ingrediente = $id_ingrediente;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->precio = $precio;
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
    
    public function getAlergenos() {
        return $this->alergenos;
    }

    public function setAlergenos($alergenos) {
        $this->alergenos = $alergenos;
    }

    public function __toString() {
        return "Ingrediente: ID={$this->id_ingrediente}, Nombre={$this->nombre}, Precio={$this->precio}, Alergenos=[" . implode(", ", $this->alergenos) . "]";
    }
}
?>
