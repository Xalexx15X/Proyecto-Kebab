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
            $registro = $stm->fetch(PDO::FETCH_ASSOC);

            if ($registro) {
                $direccion = new Direccion(
                    $registro['id_direccion'],
                    $registro['direccion'],
                    $registro['estado'],
                    $registro['usuario_id_usuario']
                );
                return $direccion;
            } else {
                return null;  // No se encontró la dirección
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener la dirección: " . $e->getMessage()]);
            return null;
        }
    }

    public function crear(Direccion $direccion)
    {
        try {
            // Obtener el ID del usuario usando el nuevo método
            $usuario_id = $direccion->getUsuarioId(); // Esto devolverá el ID del usuario

            if ($usuario_id === null) {
                throw new Exception("Usuario ID no proporcionado.");
            }

            // Preparar la consulta SQL
            $sql = "INSERT INTO direccion (direccion, estado, usuario_id_usuario) 
                    VALUES (:direccion, :estado, :usuario_id_usuario)";
            $stm = $this->con->prepare($sql);

            // Asignar los valores
            $stm->bindValue(':direccion', $direccion->getDireccion());
            $stm->bindValue(':estado', $direccion->getEstado());
            $stm->bindValue(':usuario_id_usuario', $usuario_id, PDO::PARAM_INT);

            // Ejecutar la consulta
            if ($stm->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al crear la dirección: " . $e->getMessage()]);
            return false;
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
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

            if ($stm->execute()) {
                return true;  // Dirección actualizada con éxito
            }
            return false;
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
            if ($stm->execute()) {
                return true;  // Dirección eliminada correctamente
            }
            return false;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al eliminar la dirección: " . $e->getMessage()]);
            return false;
        }
    }
}
?>
