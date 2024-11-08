<?php
class Pedido
{
    private $id_pedidos;
    private $estado;
    private $precio_total;
    private $fecha_hora;
    private Usuarios $usuario;
    

    public function __construct($id_pedidos, $estado, $precio_total, $fecha_hora, Usuarios $usuario)
    {
        $this->id_pedidos = $id_pedidos;
        $this->estado = $estado;
        $this->precio_total = $precio_total;
        $this->fecha_hora = $fecha_hora;
        $this->usuario_id_usuario = $usuario_id_usuario;
        $this->linea_pedidos = $linea_pedidos;
    }

    public function getIdPedidos() {
        return $this->id_pedidos;
    }

    public function setIdPedidos($id_pedidos) {
        $this->id_pedidos = $id_pedidos;
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



    public function __toString() {
        return "Pedido: ID={$this->id}, Estado={$this->estado}, Precio Total={$this->precio_total}, LineaPedidos=[" . implode(", ", $this->linea_pedidos) . "]";
    }
}
?>
