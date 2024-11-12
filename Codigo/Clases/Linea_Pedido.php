<?php
class Linea_Pedido
{
    private $id_linea_pedido;
    private $cantidad;
    private $precio;
    private $linea_pedidos; // JSON
    private $id_pedidos;

    public function __construct($id_linea_pedido, $cantidad, $precio, $linea_pedidos, $id_pedidos)
    {
        $this->id_linea_pedido = $id_linea_pedido;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
        $this->setLineaPedidos($linea_pedidos); // Asegura que se guarde en formato JSON
        $this->id_pedidos = $id_pedidos;
    }

    public function getIdLineaPedido() {
        return $this->id_linea_pedido;
    }

    public function setIdLineaPedido($id_linea_pedido) {
        $this->id_linea_pedido = $id_linea_pedido;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function getLineaPedidos() {
        return is_string($this->linea_pedidos) ? json_decode($this->linea_pedidos, true) : $this->linea_pedidos;
    }

    public function setLineaPedidos($linea_pedidos) {
        $this->linea_pedidos = is_array($linea_pedidos) ? json_encode($linea_pedidos) : $linea_pedidos;
    }

    public function getIdPedidos() {
        return $this->id_pedidos;
    }

    public function setIdPedidos($id_pedidos) {
        $this->id_pedidos = $id_pedidos;
    }

    public function __toString() {
        return "LineaPedido: ID={$this->id_linea_pedido}, Cantidad={$this->cantidad}, Precio={$this->precio}, LineaPedidos={$this->linea_pedidos}, Pedidos={$this->id_pedidos}";
    }
}
