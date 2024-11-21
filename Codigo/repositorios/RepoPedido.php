<?php
class RepoPedido
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para obtener un pedido por ID
    public function findById($id_pedido)
    {
        try {
            $sql = "SELECT * FROM pedidos WHERE id_pedidos = :id_pedido";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id_pedido' => $id_pedido]);
            $registro = $stm->fetch(PDO::FETCH_ASSOC);

            if ($registro) {
                return new Pedido(
                    $registro['id_pedidos'],
                    $registro['estado'],
                    $registro['precio_total'],
                    $registro['fecha_hora'],
                    $registro['usuario_id_usuario']
                );
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener el pedido: " . $e->getMessage()]);
            return null;
        }
    }

    // Método para crear un nuevo pedido
    public function crear(Pedido $pedido)
    {
        try {
            $sql = "INSERT INTO pedidos (estado, precio_total, fecha_hora, usuario_id_usuario) 
                    VALUES (:estado, :precio_total, :fecha_hora, :usuario_id_usuario)";
            $stm = $this->con->prepare($sql);

            $stm->bindValue(':estado', $pedido->getEstado());
            $stm->bindValue(':precio_total', $pedido->getPrecioTotal());
            $stm->bindValue(':fecha_hora', $pedido->getFechaHora());
            $stm->bindValue(':usuario_id_usuario', $pedido->getIdUsuario());

            if ($stm->execute()) {
                $pedido_id = $this->con->lastInsertId();
                $pedido->setIdPedidos($pedido_id);

                // Aquí puedes agregar líneas de pedido si las tienes
                return true;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al crear el pedido: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para actualizar un pedido
    public function modificar(Pedido $pedido)
    {
        try {
            $sql = "UPDATE pedidos SET estado = :estado, precio_total = :precio_total, fecha_hora = :fecha_hora, usuario_id_usuario = :usuario_id_usuario
                    WHERE id_pedidos = :id";
            $stm = $this->con->prepare($sql);

            $stm->bindValue(':estado', $pedido->getEstado());
            $stm->bindValue(':precio_total', $pedido->getPrecioTotal());
            $stm->bindValue(':fecha_hora', $pedido->getFechaHora());
            $stm->bindValue(':usuario_id_usuario', $pedido->getIdUsuario());
            $stm->bindValue(':id', $pedido->getIdPedidos(), PDO::PARAM_INT);

            if ($stm->execute()) {
                // Si tienes líneas de pedido, puedes actualizar las relaciones
                return true;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al modificar el pedido: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para eliminar un pedido
    public function eliminar($id_pedido)
    {
        try {
            // Primero eliminar las líneas de pedido asociadas
            $sql_lineas = "DELETE FROM linea_pedido WHERE pedidos_id_pedidos = :id_pedido";
            $stm_lineas = $this->con->prepare($sql_lineas);
            $stm_lineas->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stm_lineas->execute();

            // Ahora eliminar el pedido
            $sql_pedido = "DELETE FROM pedidos WHERE id_pedidos = :id_pedido";
            $stm_pedido = $this->con->prepare($sql_pedido);
            $stm_pedido->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stm_pedido->execute();

            return $stm_pedido->rowCount() > 0;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al eliminar el pedido: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para mostrar todos los pedidos
    public function mostrarTodos()
    {
        try {
            $sql = "SELECT * FROM pedidos";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

            $pedidos = [];
            foreach ($registros as $registro) {
                $pedido = new Pedido(
                    $registro['id_pedidos'],
                    $registro['estado'],
                    $registro['precio_total'],
                    $registro['fecha_hora'],
                    [], // Aquí también puedes agregar las líneas de pedido si es necesario
                    $registro['usuario_id_usuario']
                );
                $pedidos[] = $pedido;
            }
            return $pedidos;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al mostrar los pedidos: " . $e->getMessage()]);
            return [];
        }
    }
}
?>
