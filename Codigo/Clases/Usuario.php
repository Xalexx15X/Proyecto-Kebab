<?php
class Usuario
{
    private $id_usuario;
    private $nombre;
    private $contrasena;
    private $carrito;  // Manejado como JSON
    private $monedero;
    private $foto;
    private $correo;
    private $telefono;
    private $ubicacion;
    private Alergenos $alergenos;

    public function __construct($id_usuario, $nombre, $contrasena, $carrito, $monedero, $foto, $correo, $telefono, $ubicacion, Alergenos $alergenos)
    {
        $this->id_usuario = $id_usuario;
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;
        $this->carrito = $carrito;
        $this->monedero = $monedero;
        $this->foto = $foto;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->ubicacion = $ubicacion;
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
        return $this->carrito;
    }

    public function setCarrito($carrito) {
        $this->carrito = $carrito;
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

    public function getCorreo() {
        return $this->correo;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
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

    public function getAlergenos() {
        return $this->alergenos;
    }

    public function setAlergenos($alergenos) {
        $this->alergenos = $alergenos;
    }

    public function __toString() {
        return "Usuario: ID={$this->id_usuario}, Nombre={$this->nombre}, Contrasena={$this->contrasena}, Carrito={$this->carrito}, Monedero={$this->monedero}, Foto={$this->foto}, Correo={$this->correo}, Telefono={$this->telefono}, Ubicacion={$this->ubicacion}, Alergenos=[" . implode(", ", $this->alergenos) . "]";
    }
}
?>
