<?php
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
