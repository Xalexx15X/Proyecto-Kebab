<?php
    /*
        Clase para almacenar los pedidos
        
        Atributos:
            id_pedido (int): Identificador único del pedido
            estado (string): Estado del pedido (Pendiente, Procesado, Finalizado)
            precio_total (float): Precio total del pedido
            fecha_hora (string): Fecha y hora del pedido
            id_usuario (int): Identificador único del usuario
            
        Métodos:
            getIdPedidos(): Devuelve el ID del pedido
            setIdPedidos($id_pedido): Establece el ID del pedido
            getEstado(): Devuelve el estado del pedido (Pendiente, Procesado, Finalizado)
            setEstado($estado): Establece el estado del pedido (Pendiente, Procesado, Finalizado)
            getPrecioTotal(): Devuelve el precio total del pedido
            setPrecioTotal($precio_total): Establece el precio total del pedido
            getFechaHora(): Devuelve la fecha y hora del pedido
            setFechaHora($fecha_hora): Establece la fecha y hora del pedido
            getIdUsuario(): Devuelve el ID del usuario
            setIdUsuario($id_usuario): Establece el ID del usuario
            __toString(): Devuelve una representación de la clase como string
    */

class Pedido
{
    public $id_pedido;
    public $estado;
    public $precio_total;
    public $fecha_hora;
    public $id_usuario;  

    public function __construct($id_pedido, $estado, $precio_total, $fecha_hora, $id_usuario = null)
    {
        $this->id_pedido = $id_pedido;
        $this->estado = $estado;
        $this->precio_total = $precio_total;
        $this->fecha_hora = $fecha_hora;
        $this->id_usuario = $id_usuario;
    }

    public function getIdPedidos() {
        return $this->id_pedido;
    }

    public function setIdPedidos($id_pedido) {
        $this->id_pedido = $id_pedido;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getPrecioTotal() {
        return $this->precio_total;
    }

    public function setPrecioTotal($precio_total) {
        $this->precio_total = $precio_total;
    }

    public function getFechaHora() {
        return $this->fecha_hora;
    }

    public function setFechaHora($fecha_hora) {
        $this->fecha_hora = $fecha_hora;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function __toString() {
        return "Pedido: ID={$this->id_pedido}, Estado={$this->estado}, Precio={$this->precio_total}, Fecha={$this->fecha_hora}, UsuarioID={$this->id_usuario}";
    }
}
