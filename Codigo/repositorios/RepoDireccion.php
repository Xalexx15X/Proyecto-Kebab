<?php

class RepoDireccion
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar una dirección por ID, incluyendo sus usuarios relacionados
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from direccion where id_direccion = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            $direccion = new Direccion($registro['id_direccion'], $registro['direccion'], $registro['estado']);
            $direccion->setUsuarios($this->findUsuariosByDireccionId($registro['id_direccion']));
            return $direccion;
        }
        return null;
    }

    // Método para encontrar los usuarios relacionados con una dirección
    private function findUsuariosByDireccionId($id_direccion)
    {
        $usuarios = [];
        $stm = $this->con->prepare("select u.* from usuario u 
                                    innser join usuario_tiene_direccion utd on u.id_usuario = utd.usuario_id_usuario 
                                    where utd.direccion_id_direccion = :id_direccion");
        $stm->execute(['id_direccion' => $id_direccion]);
        $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($registros as $registro) {
            $usuarios[] = new Usuario($registro['id_usuario'], $registro['nombre'], $registro['contraseña'], $registro['direccion'], $registro['carrito'], $registro['monedero'], $registro['foto'], $registro['correo'], $registro['telefono']);
        }
        return $usuarios;
    }

    // Método para crear una dirección y asociarla a usuarios
    public function crear(Direccion $direccion)
    {
        try {
            $sql = "insert into direccion (direccion, estado) values (:direccion, :estado)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':direccion', $direccion->getDireccion());
            $stm->bindParam(':estado', $direccion->getEstado());
            $stm->execute();

            // Obtener el ID de la dirección recién creada
            $direccion_id = $this->con->lastInsertId();

            // Asignar la relación con los usuarios
            foreach ($direccion->getUsuarios() as $usuario) {
                $this->agregarRelacionUsuarioDireccion($usuario->getIdUsuario(), $direccion_id);
            }

            return true;
        } catch (PDOException $e) {
            return "Error al crear la dirección: " . $e->getMessage();
        }
    }

    // Método para agregar una relación entre un usuario y una dirección
    private function agregarRelacionUsuarioDireccion($usuario_id, $direccion_id)
    {
        $sql = "insert into usuario_tiene_direccion (usuario_id_usuario, direccion_id_direccion) values (:usuario_id, :direccion_id)";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':usuario_id', $usuario_id);
        $stm->bindParam(':direccion_id', $direccion_id);
        $stm->execute();
    }

    // Método para modificar una dirección y actualizar sus usuarios relacionados
    public function modificar(Direccion $direccion)
    {
        try {
            $sql = "update direccion set direccion = :direccion, estado = :estado where id_direccion = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':direccion', $direccion->getDireccion());
            $stm->bindParam(':estado', $direccion->getEstado());
            $stm->bindParam(':id', $direccion->getIdDireccion());
            $stm->execute();

            // Actualizar relaciones con los usuarios
            $this->actualizarRelacionUsuarios($direccion);

            return true;
        } catch (PDOException $e) {
            return "Error al modificar la dirección: " . $e->getMessage();
        }
    }

    // Método para actualizar la relación entre dirección y usuarios
    private function actualizarRelacionUsuarios(Direccion $direccion)
    {
        // Primero, eliminamos todas las relaciones actuales
        $sql = "delete from usuario_tiene_direccion where direccion_id_direccion = :direccion_id";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':direccion_id', $direccion->getIdDireccion());
        $stm->execute();

        // Luego, agregamos las nuevas relaciones
        foreach ($direccion->getUsuarios() as $usuario) {
            $this->agregarRelacionUsuarioDireccion($usuario->getIdUsuario(), $direccion->getIdDireccion());
        }
    }

    // Método para eliminar una dirección y sus relaciones con usuarios
    public function eliminar($id)
    {
        try {
            // Eliminar relaciones con usuarios
            $sql = "delete from usuario_tiene_direccion where direccion_id_direccion = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id);
            $stm->execute();

            // Eliminar la dirección
            $sql = "delete from direccion where id_direccion = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id);
            $stm->execute();

            return true;
        } catch (PDOException $e) {
            return "Error al eliminar la dirección: " . $e->getMessage();
        }
    }

    // Método para mostrar todas las direcciones con sus usuarios
    public function mostrarTodos()
    {
        try {
            $sql = "select * from direccion";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
            $direcciones = [];

            foreach ($registros as $registro) {
                $direccion = new Direccion($registro['id_direccion'], $registro['direccion'], $registro['estado']);
                $direccion->setUsuarios($this->findUsuariosByDireccionId($registro['id_direccion']));
                $direcciones[] = $direccion;
            }

            return $direcciones;
        } catch (PDOException $e) {
            return "Error al mostrar las direcciones: " . $e->getMessage();
        }
    }
}
