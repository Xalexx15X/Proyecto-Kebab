<?php

class RepoDireccion
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para obtener todas las direcciones de un usuario por ID
    public function findByUsuarioId($usuarioId)
    {
        try {
            $sql = "SELECT * FROM direccion WHERE usuario_id_usuario = :usuario_id_usuario";
            $stm = $this->con->prepare($sql);
            $stm->execute(['usuario_id_usuario' => $usuarioId]);
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener las direcciones: " . $e->getMessage()]);
            return [];
        }
    }

    // Método para obtener una dirección por su ID
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM direccion WHERE id_direccion = :id";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id' => $id]);
            return $stm->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener la dirección: " . $e->getMessage()]);
            return null;
        }
    }

    // Método para crear una nueva dirección
    public function crear(Direccion $direccion)
    {
        try {
            $sql = "INSERT INTO direccion (direccion, estado, usuario_id_usuario) 
                    VALUES (:direccion, :estado, :usuario_id_usuario)";
            $stm = $this->con->prepare($sql);
            $stm->bindValue(':direccion', $direccion->getDireccion());
            $stm->bindValue(':estado', $direccion->getEstado());
            $stm->bindValue(':usuario_id_usuario', $direccion->getUsuarioId(), PDO::PARAM_INT);
            return $stm->execute();
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al crear la dirección: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para actualizar una dirección
    public function modificar(Direccion $direccion)
    {
        try {
            $sql = "UPDATE direccion SET direccion = :direccion, estado = :estado 
                    WHERE id_direccion = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindValue(':direccion', $direccion->getDireccion());
            $stm->bindValue(':estado', $direccion->getEstado());
            $stm->bindValue(':id', $direccion->getIdDireccion());
            return $stm->execute();
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al actualizar la dirección: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para eliminar una dirección
    public function eliminar($id)
    {
        try {
            $sql = "DELETE FROM direccion WHERE id_direccion = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            return $stm->execute();
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al eliminar la dirección: " . $e->getMessage()]);
            return false;
        }
    }
}
