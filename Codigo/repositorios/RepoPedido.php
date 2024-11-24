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
    
    public function findByUsuarioId($id_usuario)
    {
        try {
            // Modificamos la consulta para obtener solo el último pedido del usuario
            $sql = "SELECT * FROM pedidos WHERE usuario_id_usuario = :id_usuario ORDER BY fecha_hora DESC LIMIT 1";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id_usuario' => $id_usuario]);
            $pedido = $stm->fetch(PDO::FETCH_ASSOC);  // Solo obtenemos un pedido
    
            if (!$pedido) {
                echo json_encode(["error" => "No se encontró el último pedido para el usuario especificado."]);
                http_response_code(404);  // Not Found
                return null;
            }
    
            // Convertir el resultado en un objeto Pedido
            return new Pedido(
                $pedido['id_pedidos'],
                $pedido['estado'],
                $pedido['precio_total'],
                $pedido['fecha_hora'],
                $pedido['usuario_id_usuario']
            );
    
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener el último pedido: " . $e->getMessage()]);
            http_response_code(500);  // Internal Server Error
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

    public function mostrarTodosConUsuarios()
    {
        try {
            // Consulta con JOIN para obtener pedidos junto con los nombres de usuario
            $sql = "
                SELECT 
                    p.id_pedidos, 
                    p.estado, 
                    p.precio_total, 
                    p.fecha_hora, 
                    p.usuario_id_usuario, 
                    u.nombre AS nombre_usuario
                FROM 
                    pedidos p
                INNER JOIN 
                    usuario u ON p.usuario_id_usuario = u.id_usuario
            ";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
    
            return $registros; // Devuelve un array con los pedidos y los nombres de usuario
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al mostrar los pedidos con usuarios: " . $e->getMessage()]);
            return [];
        }
    }  

    public function mostrarPedidosPorClienteConLineas($id_usuario)
    {
        try {
            // Consulta SQL para obtener los pedidos y las líneas de pedido
            $sql = "
                SELECT 
                    p.id_pedidos, 
                    p.fecha_hora, 
                    p.precio_total, 
                    lp.linea_pedidos
                FROM 
                    pedidos p
                LEFT JOIN 
                    linea_pedido lp ON p.id_pedidos = lp.pedidos_id_pedidos
                WHERE 
                    p.usuario_id_usuario = :id_usuario
                ORDER BY 
                    p.fecha_hora DESC
            ";

            // Preparar la consulta
            $stm = $this->con->prepare($sql);
            $stm->execute(['id_usuario' => $id_usuario]);

            // Obtener los resultados
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

            // Devolver directamente los registros
            return $registros;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener los pedidos del cliente: " . $e->getMessage()]);
            return [];
        }
    }
}    
?>
