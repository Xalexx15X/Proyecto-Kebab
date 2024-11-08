<?php

class RepoPedido
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar un pedido por ID, incluyendo sus usuarios y líneas de pedido
    public function findById($id)
    {
        // Buscar el pedido
        $stm = $this->con->prepare("select * from pedidos where id_pedidos = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            $pedido = new Pedido(
                $registro['id_pedidos'],
                $registro['estado'],
                $registro['precio_total'],
                $registro['fecha_hora']
            );

            // Cargar usuarios asociados al pedido
            $pedido->setUsuarios($this->findUsuariosByPedidoId($registro['id_pedidos']));

            // Cargar líneas de pedido asociadas
            $pedido->setLineaPedidos($this->findLineasPedidosByPedidoId($registro['id_pedidos']));

            return $pedido;
        }
        return null;
    }

    // Método para encontrar los usuarios asociados a un pedido
    private function findUsuariosByPedidoId($id_pedido)
    {
        $usuarios = [];
        $stm = $this->con->prepare("select u.* from usuarios u
                                    inner join pedidos_tiene_usuarios pu on u.id_usuario = pu.usuario_id_usuario
                                    where pu.pedido_id_pedido = :id_pedido");
        $stm->execute(['id_pedido' => $id_pedido]);
        $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($registros as $registro) {
            $usuarios[] = new Usuarios(
                $registro['id_usuario'],
                $registro['nombre'],
                $registro['email']
            );
        }
        return $usuarios;
    }

    // Método para encontrar las líneas de pedido asociadas a un pedido
    private function findLineasPedidosByPedidoId($id_pedido)
    {
        $lineasPedidos = [];
        $stm = $this->con->prepare("SELECT * FROM linea_pedido WHERE pedido_id_pedidos = :id_pedido");
        $stm->execute(['id_pedido' => $id_pedido]);
        $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($registros as $registro) {
            $lineasPedidos[] = new LineaPedido(
                $registro['id_linea_pedido'],
                $registro['cantidad'],
                $registro['precio'],
                $registro['pedido_id_pedidos']
            );
        }
        return $lineasPedidos;
    }

    // Método para crear un pedido con sus usuarios y líneas de pedido
    public function crear(Pedido $pedido)
    {
        try {
            $sql = "INSERT INTO pedidos (estado, precio_total, fecha_hora, usuario_id_usuario) VALUES (:estado, :precio_total, :fecha_hora, :usuario_id)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':estado', $pedido->getEstado());
            $stm->bindParam(':precio_total', $pedido->getPrecioTotal());
            $stm->bindParam(':fecha_hora', $pedido->getFechaHora());
            $stm->bindParam(':usuario_id', $pedido->getUsuarioId());
            $stm->execute();

            // Obtener el ID del pedido recién creado
            $pedido_id = $this->con->lastInsertId();

            // Asociar usuarios al pedido
            foreach ($pedido->getUsuarios() as $usuario) {
                $this->agregarRelacionPedidoUsuario($pedido_id, $usuario->getIdUsuario());
            }

            // Agregar las líneas de pedido
            foreach ($pedido->getLineaPedidos() as $lineaPedido) {
                $this->agregarLineaPedido($pedido_id, $lineaPedido);
            }

            return true;
        } catch (PDOException $e) {
            return "Error al crear el pedido: " . $e->getMessage();
        }
    }

    // Método para agregar la relación entre un pedido y un usuario
    private function agregarRelacionPedidoUsuario($pedido_id, $usuario_id)
    {
        $sql = "INSERT INTO pedidos_tiene_usuarios (pedido_id_pedido, usuario_id_usuario) VALUES (:pedido_id, :usuario_id)";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':pedido_id', $pedido_id);
        $stm->bindParam(':usuario_id', $usuario_id);
        $stm->execute();
    }

    // Método para agregar una línea de pedido a un pedido
    private function agregarLineaPedido($pedido_id, LineaPedido $lineaPedido)
    {
        $sql = "INSERT INTO linea_pedido (cantidad, precio, pedido_id_pedidos) VALUES (:cantidad, :precio, :pedido_id)";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':cantidad', $lineaPedido->getCantidad());
        $stm->bindParam(':precio', $lineaPedido->getPrecio());
        $stm->bindParam(':pedido_id', $pedido_id);
        $stm->execute();
    }

    // Método para modificar un pedido y sus usuarios y líneas de pedido asociadas
    public function modificar(Pedido $pedido)
    {
        try {
            $sql = "UPDATE pedidos SET estado = :estado, precio_total = :precio_total, fecha_hora = :fecha_hora, usuario_id_usuario = :usuario_id WHERE id_pedidos = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':estado', $pedido->getEstado());
            $stm->bindParam(':precio_total', $pedido->getPrecioTotal());
            $stm->bindParam(':fecha_hora', $pedido->getFechaHora());
            $stm->bindParam(':usuario_id', $pedido->getUsuarioId());
            $stm->bindParam(':id', $pedido->getIdPedidos(), PDO::PARAM_INT);
            $stm->execute();

            // Actualizar usuarios asociados al pedido
            $this->actualizarRelacionUsuarios($pedido);

            // Actualizar líneas de pedido
            $this->actualizarLineasPedido($pedido);

            return true;
        } catch (PDOException $e) {
            return "Error al modificar el pedido: " . $e->getMessage();
        }
    }

    // Método para actualizar la relación de usuarios de un pedido
    private function actualizarRelacionUsuarios(Pedido $pedido)
    {
        $sql = "DELETE FROM pedidos_tiene_usuarios WHERE pedido_id_pedido = :id_pedido";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':id_pedido', $pedido->getIdPedidos());
        $stm->execute();

        foreach ($pedido->getUsuarios() as $usuario) {
            $this->agregarRelacionPedidoUsuario($pedido->getIdPedidos(), $usuario->getIdUsuario());
        }
    }

    // Método para actualizar las líneas de pedido de un pedido
    private function actualizarLineasPedido(Pedido $pedido)
    {
        $sql = "DELETE FROM linea_pedido WHERE pedido_id_pedidos = :id_pedido";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':id_pedido', $pedido->getIdPedidos());
        $stm->execute();

        foreach ($pedido->getLineaPedidos() as $lineaPedido) {
            $this->agregarLineaPedido($pedido->getIdPedidos(), $lineaPedido);
        }
    }

    // Método para borrar un pedido y sus relaciones con usuarios y líneas de pedido
    public function borrar($id)
    {
        try {
            // Eliminar relaciones con usuarios
            $sql = "DELETE FROM pedidos_tiene_usuarios WHERE pedido_id_pedido = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            // Eliminar líneas de pedido
            $sql = "DELETE FROM linea_pedido WHERE pedido_id_pedidos = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            // Eliminar el pedido
            $sql = "DELETE FROM pedidos WHERE id_pedidos = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            return true;
        } catch (PDOException $e) {
            return "Error al borrar el pedido: " . $e->getMessage();
        }
    }
}
