<?php
class RepoPedido
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar un pedido por ID
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from pedidos where id_pedidos = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            return new Pedido($registro['id_pedidos'], $registro['estado'], $registro['precio_total'], $registro['fecha_hora'], $registro['usuario_id_usuario']);
        }
        return null;
    }

    // Método para crear un pedido
    public function crear(Pedido $pedido)
    {
        try {
            $sql = "insert into pedidos (estado, precio_total, fecha_hora, usuario_id_usuario) values (:estado, :precio_total, :fecha_hora, :usuario_id)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':estado', $pedido->estado);
            $stm->bindParam(':precio_total', $pedido->precio_total);
            $stm->bindParam(':fecha_hora', $pedido->fecha_hora);
            $stm->bindParam(':usuario_id', $pedido->usuario_id);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al crear el pedido: " . $e->getMessage();
        }
    }

    // Método para modificar un pedido existente
    public function modificar(Pedido $pedido)
    {
        try {
            $sql = "update pedidos set estado = :estado, precio_total = :precio_total, fecha_hora = :fecha_hora, usuario_id_usuario = :usuario_id WHERE id_pedidos = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':estado', $pedido->estado);
            $stm->bindParam(':precio_total', $pedido->precio_total);
            $stm->bindParam(':fecha_hora', $pedido->fecha_hora);
            $stm->bindParam(':usuario_id', $pedido->usuario_id);
            $stm->bindParam(':id', $pedido->id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al modificar el pedido: " . $e->getMessage();
        }
    }

    // Método para borrar un pedido por ID
    public function borrar($id)
    {
        try {
            $sql = "delete from pedidos where id_pedidos = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al borrar el pedido: " . $e->getMessage();
        }
    }

    // Método para mostrar todos los pedidos
    public function mostrarTodos()
    {
        try {
            $sql = "selec * from pedidos";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
            $pedidos = [];

            foreach ($registros as $registro) {
                $pedidos[] = new Pedido($registro['id_pedidos'], $registro['estado'], $registro['precio_total'], $registro['fecha_hora'], $registro['usuario_id_usuario']);
            }
            return $pedidos;
        } catch (PDOException $e) {
            return "Error al mostrar los pedidos: " . $e->getMessage();
        }
    }
}
?>
