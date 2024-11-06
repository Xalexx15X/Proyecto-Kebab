<?php
class RepoLineaPedido
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar una línea de pedido por ID
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from linea_pedido where id_linea_pedido = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            return new LineaPedido($registro['id_linea_pedido'], $registro['precio'], $registro['cantidad'], $registro['pedido_id_pedidos']);
        }
        return null;
    }

    // Método para crear una línea de pedido
    public function crear(LineaPedido $lineaPedido)
    {
        try {
            $sql = "insert into linea_pedido (precio, cantidad, pedido_id_pedidos) values (:precio, :cantidad, :pedido_id)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':precio', $lineaPedido->precio);
            $stm->bindParam(':cantidad', $lineaPedido->cantidad);
            $stm->bindParam(':pedido_id', $lineaPedido->pedido_id);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al crear la línea de pedido: " . $e->getMessage();
        }
    }

    // Método para modificar una línea de pedido existente
    public function modificar(LineaPedido $lineaPedido)
    {
        try {
            $sql = "update linea_pedido set precio = :precio, cantidad = :cantidad, pedido_id_pedidos = :pedido_id WHERE id_linea_pedido = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':precio', $lineaPedido->precio);
            $stm->bindParam(':cantidad', $lineaPedido->cantidad);
            $stm->bindParam(':pedido_id', $lineaPedido->pedido_id);
            $stm->bindParam(':id', $lineaPedido->id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al modificar la línea de pedido: " . $e->getMessage();
        }
    }

    // Método para borrar una línea de pedido por ID
    public function borrar($id)
    {
        try {
            $sql = "delete from linea_pedido where id_linea_pedido = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al borrar la línea de pedido: " . $e->getMessage();
        }
    }

    // Método para mostrar todas las líneas de pedido
    public function mostrarTodos()
    {
        try {
            $sql = "select * from linea_pedido";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
            $lineasPedido = [];

            foreach ($registros as $registro) {
                $lineasPedido[] = new LineaPedido($registro['id_linea_pedido'], $registro['precio'], $registro['cantidad'], $registro['pedido_id_pedidos']);
            }
            return $lineasPedido;
        } catch (PDOException $e) {
            return "Error al mostrar las líneas de pedido: " . $e->getMessage();
        }
    }
}
?>
