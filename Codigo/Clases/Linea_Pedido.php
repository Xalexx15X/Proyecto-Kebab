<?php
/*
    Clase para almacenar las líneas de pedido
    
    Atributos:
        id_linea_pedido (int): Identificador único de la línea de pedido
        cantidad (int): Cantidad de pedidos en la línea
        precio (float): Precio total de la línea de pedido
        linea_pedidos (string): JSON con la lista de pedidos en la línea
        id_pedidos (int): Identificador único del pedido
        
    Métodos:
        getIdLineaPedido(): Devuelve el ID de la línea de pedido
        setIdLineaPedido($id_linea_pedido): Establece el ID de la línea de pedido
        getCantidad(): Devuelve la cantidad de pedidos en la línea
        setCantidad($cantidad): Establece la cantidad de pedidos en la línea
        getPrecio(): Devuelve el precio total de la línea de pedido
        setPrecio($precio): Establece el precio total de la línea de pedido
        getLineaPedidos(): Devuelve la lista de pedidos en la línea
        setLineaPedidos($linea_pedidos): Establece la lista de pedidos en la línea
        getIdPedidos(): Devuelve el ID del pedido
        setIdPedidos($id_pedidos): Establece el ID del pedido
        __toString(): Devuelve una representación de la clase como string
*/  


class Linea_Pedido
{
    public $id_linea_pedido;
    public $cantidad;
    public $precio;
    public $linea_pedidos; // JSON
    public $id_pedidos;

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
