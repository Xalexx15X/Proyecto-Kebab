<?php
/*
    Clase para almacenar los kebabs
    
    Atributos:
        id_kebab (int): Identificador único del kebab
        nombre (string): Nombre del kebab
        foto (string): URL de la foto del kebab
        precio_min (float): Precio mínimo del kebab
        descripcion (string): Descripción del kebab
        ingredientes (array): Lista de ingredientes del kebab
        
    Métodos:
        getIdKebab(): Devuelve el ID del kebab
        setIdKebab($id_kebab): Establece el ID del kebab
        getNombre(): Devuelve el nombre del kebab
        setNombre($nombre): Establece el nombre del kebab
        getFoto(): Devuelve la URL de la foto del kebab
        setFoto($foto): Establece la URL de la foto del kebab
        getPrecioMin(): Devuelve el precio mínimo del kebab
        setPrecioMin($precio_min): Establece el precio mínimo del kebab
        getDescripcion(): Devuelve la descripción del kebab
        setDescripcion($descripcion): Establece la descripción del kebab
        getIngredientes(): Devuelve la lista de ingredientes del kebab
        setIngredientes($ingredientes): Establece la lista de ingredientes del kebab
        __toString(): Devuelve una representación de la clase como string
*/

class Kebab
{
    public $id_kebab;
    public $nombre;
    public $foto;
    public $precio_min;
    public $descripcion;
    public $ingredientes = [];

    public function __construct($id_kebab, $nombre, $foto, $precio_min, $descripcion, $ingredientes = [])
    {
        $this->id_kebab = $id_kebab;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->precio_min = $precio_min;
        $this->descripcion = $descripcion;
        $this->ingredientes = $ingredientes;
        
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
    
    public  function setIngredientes($ingredientes) {
        $this->ingredientes = $ingredientes;    
    }

    public function getIngredientes() {
        return $this->ingredientes; 
    }

    public function __toString() {
        return "Kebab: ID={$this->id_kebab}, Nombre={$this->nombre}, Foto={$this->foto}, Precio={$this->precio_min}, Descripcion={$this->descripcion}, Ingredientes=[" . implode(", ", $this->ingredientes) . "]";
    }
}
?>
