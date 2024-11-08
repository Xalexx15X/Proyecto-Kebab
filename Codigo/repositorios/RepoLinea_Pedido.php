<?php

class RepoLineaPedido
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar una línea de pedido por ID, incluyendo sus pedidos asociados
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from linea_pedido where id_linea_pedido = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            $lineaPedido = new LineaPedido(
                $registro['id_linea_pedido'],
                $registro['cantidad'],
                $registro['precio'],
                $registro['pedido_id_pedidos']
            );
            $lineaPedido->setPedidos($this->findPedidosByLineaPedidoId($registro['id_linea_pedido']));
            return $lineaPedido;
        }
        return null;
    }

    // Método para encontrar los pedidos asociados con una línea de pedido específica
    private function findPedidosByLineaPedidoId($id_linea_pedido)
    {
        $pedidos = [];
        $stm = $this->con->prepare("select p.* from pedidos p
                                    inner join linea_pedido_tiene_pedidos lpp on p.id_pedido = lpp.pedido_id_pedido
                                    where lpp.linea_pedido_id_linea_pedido = :id_linea_pedido");
        $stm->execute(['id_linea_pedido' => $id_linea_pedido]);
        $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($registros as $registro) {
            $pedidos[] = new Pedidos(
                $registro['id_pedido'],
                $registro['fecha'],
                $registro['total'],
                $registro['cliente_id']
            );
        }
        return $pedidos;
    }

    // Método para crear una línea de pedido y asociarla con pedidos
    public function crear(LineaPedido $lineaPedido)
    {
        try {
            $sql = "insert into linea_pedido (precio, cantidad, pedido_id_pedidos) values (:precio, :cantidad, :pedido_id)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':precio', $lineaPedido->getPrecio());
            $stm->bindParam(':cantidad', $lineaPedido->getCantidad());
            $stm->bindParam(':pedido_id', $lineaPedido->getLineaPedidos());
            $stm->execute();

            // Obtener el ID de la línea de pedido recién creada
            $linea_pedido_id = $this->con->lastInsertId();

            // Asociar pedidos
            foreach ($lineaPedido->getPedidos() as $pedido) {
                $this->agregarRelacionLineaPedidoPedido($linea_pedido_id, $pedido->getIdPedidos());
            }

            return true;
        } catch (PDOException $e) {
            return "Error al crear la línea de pedido: " . $e->getMessage();
        }
    }

    // Método para agregar una relación entre una línea de pedido y un pedido
    private function agregarRelacionLineaPedidoPedido($linea_pedido_id, $pedido_id)
    {
        $sql = "insert into linea_pedido_tiene_pedidos (linea_pedido_id_linea_pedido, pedido_id_pedido) values (:linea_pedido_id, :pedido_id)";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':linea_pedido_id', $linea_pedido_id);
        $stm->bindParam(':pedido_id', $pedido_id);
        $stm->execute();
    }

    // Método para modificar una línea de pedido y sus pedidos asociados
    public function modificar(LineaPedido $lineaPedido)
    {
        try {
            $sql = "updates linea_pedido set precio = :precio, cantidad = :cantidad, pedido_id_pedidos = :pedido_id where id_linea_pedido = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':precio', $lineaPedido->getPrecio());
            $stm->bindParam(':cantidad', $lineaPedido->getCantidad());
            $stm->bindParam(':pedido_id', $lineaPedido->getLineaPedidos());
            $stm->bindParam(':id', $lineaPedido->getIdLineaPedido(), PDO::PARAM_INT);
            $stm->execute();

            // Actualizar relaciones con pedidos
            $this->actualizarRelacionPedidos($lineaPedido);

            return true;
        } catch (PDOException $e) {
            return "Error al modificar la línea de pedido: " . $e->getMessage();
        }
    }

    // Método para actualizar las relaciones entre una línea de pedido y sus pedidos
    private function actualizarRelacionPedidos(LineaPedido $lineaPedido)
    {
        // Eliminar relaciones actuales
        $sql = "delete from linea_pedido_tiene_pedidos where linea_pedido_id_linea_pedido = :linea_pedido_id";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':linea_pedido_id', $lineaPedido->getIdLineaPedido());
        $stm->execute();

        // Agregar las nuevas relaciones
        foreach ($lineaPedido->getPedidos() as $pedido) {
            $this->agregarRelacionLineaPedidoPedido($lineaPedido->getIdLineaPedido(), $pedido->getIdPedidos());
        }
    }

    // Método para borrar una línea de pedido y sus relaciones con pedidos
    public function borrar($id)
    {
        try {
            // Eliminar relaciones con pedidos
            $sql = "delete from linea_pedido_tiene_pedidos where linea_pedido_id_linea_pedido = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            // Eliminar la línea de pedido
            $sql = "delete from linea_pedido where id_linea_pedido = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            return true;
        } catch (PDOException $e) {
            return "Error al borrar la línea de pedido: " . $e->getMessage();
        }
    }

    // Método para mostrar todas las líneas de pedido con sus pedidos asociados
    public function mostrarTodos()
    {
        try {
            $sql = "select * from linea_pedido";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
            $lineasPedido = [];

            foreach ($registros as $registro) {
                $lineaPedido = new LineaPedido(
                    $registro['id_linea_pedido'],
                    $registro['cantidad'],
                    $registro['precio'],
                    $registro['pedido_id_pedidos']
                );
                $lineaPedido->setPedidos($this->findPedidosByLineaPedidoId($registro['id_linea_pedido']));
                $lineasPedido[] = $lineaPedido;
            }

            return $lineasPedido;
        } catch (PDOException $e) {
            return "Error al mostrar las líneas de pedido: " . $e->getMessage();
        }
    }
}
