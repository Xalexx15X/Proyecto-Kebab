<?php
class Usuario
{
    private $id_usuario;
    private $nombre;
    private $contrasena;
    private $direcciones = [];
    private $carrito;  // Manejado como JSON
    private $monedero;
    private $foto;
    private $correo;
    private $telefono;

    public function __construct($id_usuario, $nombre, $contrasena, $direcciones = [], $carrito, $monedero, $foto, $telefono)
    {
        $this->id_usuario = $id_usuario;
        $this->nombre = $nombre;
        $this->contraseña = $contrasena;
        $this->direcciones = $direcciones;
        $this->carrito = json_decode($carrito, true);  // Decodifica JSON a array
        $this->monedero = $monedero;
        $this->foto = $foto;
        $this->correo = $correo;
        $this->telefono = $telefono;
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

    public function getContraseña() {
        return $this->contrasena;
    }

    public function setContraseña($contrasena) {
        $this->contrasena = $contrasena;
    }

    public function getCarrito() {
        return json_encode($this->carrito); 
    }

    public function setCarrito($carrito) {
        $this->carrito = json_decode($carrito, true);  
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

       // Métodos para gestionar el array de direcciones
       public function addDireccion(Direccion $direccion) {
        $this->direcciones[] = $direccion;
    }

    public function removeDireccion($id_direccion) {
        $this->direcciones = array_filter($this->direcciones, fn($direccion) => $direccion->getId() !== $id_direccion);
    }

    public function __toString() {
        return "Usuario: ID={$this->id}, Nombre={$this->nombre}, Email={$this->correo}, Telefono={$this->telefono}, Direcciones=[" . implode(", ", $this->direcciones) . "]";
    }
}
?>
