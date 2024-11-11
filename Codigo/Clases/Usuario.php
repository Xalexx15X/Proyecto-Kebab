<?php
class Usuario
{
    private $id_usuario;
    private $nombre;
    private $contrasena;
    private $carrito;  // Manejado como JSON
    private $monedero;
    private $foto;
    private $telefono;
    private $ubicacion;
    private $correo; 
    private $tipo;
    private $alergenos = [];

    public function __construct($id_usuario, $nombre, $contrasena, $carrito, $monedero, $foto, $telefono, $ubicacion, $correo, $tipo, $alergenos = [])
    {
        $this->id_usuario = $id_usuario;
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;
        $this->setCarrito($carrito);
        $this->monedero = $monedero;
        $this->foto = $foto;
        $this->telefono = $telefono;
        $this->ubicacion = $ubicacion;
        $this->correo = $correo;
        $this->tipo = $tipo;    
        $this->alergenos = $alergenos;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getContrasena() {
        return $this->contrasena;
    }

    public function setContrasena($contrasena) {
        $this->contrasena = $contrasena;
    }

    public function getCarrito() {
        return is_string($this->carrito) ? json_decode($this->carrito, true) : $this->carrito;
    }

    public function setCarrito($carrito) {
        $this->carrito = is_array($carrito) ? json_encode($carrito) : $carrito;
    }

    public function getMonedero() {
        return $this->monedero;
    }

    public function setMonedero($monedero) {
        $this->monedero = $monedero;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function setFoto($foto) {
        $this->foto = $foto;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getUbicacion() {
        return $this->ubicacion;
    }

    public function setUbicacion($ubicacion) {
        $this->ubicacion = $ubicacion;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getAlergenos() {
        return $this->alergenos;
    }

    public function setAlergenos($alergenos) {
        $this->alergenos = $alergenos;
    }

    public function __toString() {
        return "Usuario: ID={$this->id_usuario}, Nombre={$this->nombre}, Contrasena={$this->contrasena}, Carrito=" . json_encode($this->getCarrito()) . ", Monedero={$this->monedero}, Foto={$this->foto}, Telefono={$this->telefono}, Ubicacion={$this->ubicacion}, Alergenos = {$this->alergenos}";
    }
}
?>
