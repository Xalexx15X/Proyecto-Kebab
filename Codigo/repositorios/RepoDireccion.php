<?php

class RepoDireccion
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para obtener todas las direcciones de un usuario por ID
    public function findByUsuarioId($id_usuario)
        {
        try {
            $sql = "SELECT * FROM direccion WHERE usuario_id_usuario = :id_usuario";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id_usuario' => $id_usuario]);
            $direcciones = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (!$direcciones) {
                echo json_encode(["error" => "No se encontraron direcciones."]);
                http_response_code(404);  // Not Found
            } else {
                return $direcciones;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener las direcciones: " . $e->getMessage()]);
            http_response_code(500);  // Internal Server Error
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

    public function mostrarTodos()
    {
        try {
            $sql = "SELECT * FROM direccion";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

            $direcciones = [];
            foreach ($registros as $registro) {
                $direccion = new Direccion(
                    $registro['id_direccion'],
                    $registro['direccion'],
                    $registro['estado'],
                    $registro['usuario_id_usuario']
                );
                $direcciones[] = $direccion;
            }
            return $direcciones;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al mostrar las direcciones: " . $e->getMessage()]);
            return [];
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
