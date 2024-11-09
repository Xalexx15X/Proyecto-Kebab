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
    private $alergenos = [];

    public function __construct($id_usuario, $nombre, $contrasena, $carrito, $monedero, $foto, $correo, $telefono, $ubicacion, $alergenos = [])
    {
        $this->id_usuario = $id_usuario;
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;
        $this->setCarrito($carrito);
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
        // Decodifica el JSON a un array de PHP si es una cadena
        return is_string($this->carrito) ? json_decode($this->carrito, true) : $this->carrito;
    }

    public function setCarrito($carrito) {
        // Si es un array, lo convierte a JSON; si es JSON, lo mantiene
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

    public function addAlergeno(Alergenos $alergeno) {
        $this->alergenos[] = $alergeno; 
    }

    public function removeAlergeno($id_alergeno) {
        $this->alergenos = array_filter($this->alergenos, fn($alergeno) => $alergeno->getIdAlergenos() !== $id_alergeno);
    }

    public function __toString() {
        return "Usuario: ID={$this->id_usuario}, Nombre={$this->nombre}, Contrasena={$this->contrasena}, Carrito=" . json_encode($this->getCarrito()) . ", Monedero={$this->monedero}, Foto={$this->foto}, Correo={$this->correo}, Telefono={$this->telefono}, Ubicacion={$this->ubicacion}, Alergenos=[" . implode(", ", $this->alergenos) . "]";
    }
}
?>
