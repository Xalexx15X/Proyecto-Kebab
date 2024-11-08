<?php
class LineaPedido
{
    private $id_linea_pedido;
    private $cantidad;
    private $precio;
    private $linea_pedidos;
    private Pedidos $pedidos;

    public function __construct($id_linea_pedido, $cantidad, $precio, $linea_pedidos, Pedidos $pedidos)
    {
        $this->id_linea_pedido = $id_linea_pedido;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
        $this->linea_pedidos = $linea_pedidos;
        $this->pedidos = $pedidos;
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
        return $this->linea_pedidos;
    }

    public function setLineaPedidos($linea_pedidos) {
        $this->linea_pedidos = $linea_pedidos;
    }

    public function getPedidos() {
        return $this->pedidos;
    }

    public function setPedidos($pedidos) {
        $this->pedidos = $pedidos;
    }

    public function __toString() {
        return "LineaPedido: ID={$this->id_linea_pedido}, Cantidad={$this->cantidad}, Precio={$this->precio}, LineaPedidos={$this->linea_pedidos}, Pedidos={$this->pedidos}";
    }
}
?>
