<?php
/*
    Clase para almacenar los usuarios
    
    Atributos:
        id_usuario (int): Identificador único del usuario
        nombre (string): Nombre del usuario
        contrasena (string): Contraseña del usuario
        carrito (string): JSON con la lista de pedidos del usuario
        monedero (float): Saldo del monedero del usuario
        foto (string): URL de la foto del usuario
        telefono (string): Teléfono del usuario
        ubicacion (string): Ubicación del usuario
        correo (string): Correo electrónico del usuario
        tipo (string): Tipo del usuario (Cliente o Administrador)
        
    Métodos:
        getIdUsuario(): Devuelve el ID del usuario
        setIdUsuario($id_usuario): Establece el ID del usuario
        getNombre(): Devuelve el nombre del usuario
        setNombre($nombre): Establece el nombre del usuario
        getContrasena(): Devuelve la contraseña del usuario
        setContrasena($contrasena): Establece la contraseña del usuario
        getCarrito(): Devuelve la lista de pedidos del usuario
        setCarrito($carrito): Establece la lista de pedidos del usuario
        getMonedero(): Devuelve el saldo del monedero del usuario
        setMonedero($monedero): Establece el saldo del monedero del usuario
        getFoto(): Devuelve la URL de la foto del usuario
        setFoto($foto): Establece la URL de la foto del usuario
        getTelefono(): Devuelve el teléfono del usuario
        setTelefono($telefono): Establece el teléfono del usuario
        getUbicacion(): Devuelve la ubicación del usuario
        setUbicacion($ubicacion): Establece la ubicación del usuario
        getCorreo(): Devuelve el correo electrónico del usuario
        setCorreo($correo): Establece el correo electrónico del usuario
        getTipo(): Devuelve el tipo del usuario (Cliente o Administrador)
        setTipo($tipo): Establece el tipo del usuario (Cliente o Administrador)
        __toString(): Devuelve una representación de la clase como string
*/  

class Usuario
{
    public $id_usuario;
    public $nombre;
    public $contrasena;
    public $carrito;  // Manejado como JSON
    public $monedero;
    public $foto;
    public $telefono;
    public $ubicacion;
    public $correo; 
    public $tipo;

    public function __construct($id_usuario, $nombre, $contrasena, $carrito, $monedero, $foto, $telefono, $ubicacion, $correo, $tipo)
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

    public function __toString() {
        return "Usuario: ID={$this->id_usuario}, Nombre={$this->nombre}, Contrasena={$this->contrasena}, Carrito=" . json_encode($this->getCarrito()) . ", Monedero={$this->monedero}, Foto={$this->foto}, Telefono={$this->telefono}, Ubicacion={$this->ubicacion}, Correo={$this->correo}, Tipo={$this->tipo}";
    }
}
?>
