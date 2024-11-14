<?php

class RepoIngredientes
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para obtener todos los ingredientes
    public function mostrarTodo()
    {
        try {
            $sql = "SELECT * FROM ingredientes";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener los ingredientes: " . $e->getMessage()]);
            return [];
        }
    }

    // Método para obtener un ingrediente por su ID
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM ingredientes WHERE id_ingredientes = :id";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id' => $id]);
            $registro = $stm->fetch(PDO::FETCH_ASSOC);

            if ($registro) {
                // Obtener los alérgenos asociados a este ingrediente
                $sqlAlergenos = "SELECT alergenos_id_alergenos FROM alergenos_tiene_ingredientes WHERE ingredientes_id_ingredientes = :ingrediente_id";
                $stmAlergenos = $this->con->prepare($sqlAlergenos);
                $stmAlergenos->execute(['ingrediente_id' => $id]);
                $alergenos = $stmAlergenos->fetchAll(PDO::FETCH_COLUMN);

                $ingrediente = new Ingredientes(
                    $registro['id_ingredientes'],
                    $registro['nombre'],
                    $registro['foto'],
                    $registro['precio'],
                    $registro['tipo'],
                    $alergenos
                );

                return $ingrediente;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener el ingrediente: " . $e->getMessage()]);
            return null;
        }
    }

    // Método para obtener los alérgenos asociados a un ingrediente por su ID
    public function findAlergenosByIngredienteId($ingredienteId)
    {
        try {
            $sql = "SELECT a.* FROM alergenos a
                    JOIN alergenos_tiene_ingredientes ati ON a.id_alergenos = ati.alergenos_id_alergenos
                    WHERE ati.ingredientes_id_ingredientes = :ingrediente_id";
            $stm = $this->con->prepare($sql);
            $stm->execute(['ingrediente_id' => $ingredienteId]);
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener los alérgenos: " . $e->getMessage()]);
            return [];
        }
    }

    // Método para crear un nuevo ingrediente y asignarle alérgenos
    public function crear(Ingredientes $ingrediente)
    {
        try {
            // Insertar el ingrediente sin usar transacción
            $sql = "INSERT INTO ingredientes (nombre, foto, precio, tipo) 
                    VALUES (:nombre, :foto, :precio, :tipo)";
            $stm = $this->con->prepare($sql);

            $stm->bindValue(':nombre', $ingrediente->getNombre());
            $stm->bindValue(':foto', $ingrediente->getFoto());
            $stm->bindValue(':precio', $ingrediente->getPrecio());
            $stm->bindValue(':tipo', $ingrediente->getTipo());

            if ($stm->execute()) {
                $ingredienteId = $this->con->lastInsertId();

                // Insertar los alérgenos de forma independiente
                foreach ($ingrediente->getAlergenos() as $idAlergeno) {
                    $sqlAlergenos = "INSERT INTO alergenos_tiene_ingredientes (alergenos_id_alergenos, ingredientes_id_ingredientes) 
                                    VALUES (:alergeno_id, :ingrediente_id)";
                    $stmAlergenos = $this->con->prepare($sqlAlergenos);
                    $stmAlergenos->bindValue(':alergeno_id', $idAlergeno, PDO::PARAM_INT);
                    $stmAlergenos->bindValue(':ingrediente_id', $ingredienteId, PDO::PARAM_INT);
                    $stmAlergenos->execute();
                }

                echo json_encode(["success" => true, "message" => "Ingrediente creado correctamente."]);
                return true;
            } else {
                echo json_encode(["error" => "Error al ejecutar la inserción de ingrediente."]);
                return false;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al crear el ingrediente: " . $e->getMessage()]);
            return false;
        }
    }


    // Método para asignar un alérgeno a un ingrediente en la tabla de relación
    public function asignarAlergeno($ingredienteId, $alergenoId)
    {
        try {
            $sql = "INSERT INTO alergenos_tiene_ingredientes (alergenos_id_alergenos, ingredientes_id_ingredientes) 
                    VALUES (:alergeno_id, :ingrediente_id)";
            $stm = $this->con->prepare($sql);
            $stm->execute(['alergeno_id' => $alergenoId, 'ingrediente_id' => $ingredienteId]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al asignar alérgeno: " . $e->getMessage()]);
        }
    }

    // Método para actualizar un ingrediente y sus alérgenos
    public function modificar(Ingredientes $ingrediente)
    {
        try {
            // Primero, actualiza los campos de la tabla `ingredientes`
            $sql = "UPDATE ingredientes SET nombre = :nombre, foto = :foto, precio = :precio, tipo = :tipo 
                    WHERE id_ingredientes = :id";
            $stm = $this->con->prepare($sql);

            $stm->bindValue(':nombre', $ingrediente->getNombre());
            $stm->bindValue(':foto', $ingrediente->getFoto());
            $stm->bindValue(':precio', $ingrediente->getPrecio());
            $stm->bindValue(':tipo', $ingrediente->getTipo());
            $stm->bindValue(':id', $ingrediente->getIdIngrediente(), PDO::PARAM_INT);

            if (!$stm->execute()) {
                return false; // Si falla la actualización, retorna false
            }

            // Luego, actualiza la relación en `alergenos_tiene_ingredientes`
            // Primero elimina los registros existentes para este ingrediente
            $sqlDelete = "DELETE FROM alergenos_tiene_ingredientes WHERE ingredientes_id_ingredientes = :id";
            $stmDelete = $this->con->prepare($sqlDelete);
            $stmDelete->bindValue(':id', $ingrediente->getIdIngrediente(), PDO::PARAM_INT);
            $stmDelete->execute();

            // Ahora inserta los nuevos alérgenos asociados
            $sqlInsert = "INSERT INTO alergenos_tiene_ingredientes (alergenos_id_alergenos, ingredientes_id_ingredientes) 
                        VALUES (:alergeno_id, :ingrediente_id)";
            $stmInsert = $this->con->prepare($sqlInsert);

            foreach ($ingrediente->getAlergenos() as $alergenoId) {
                $stmInsert->bindValue(':alergeno_id', $alergenoId, PDO::PARAM_INT);
                $stmInsert->bindValue(':ingrediente_id', $ingrediente->getIdIngrediente(), PDO::PARAM_INT);
                $stmInsert->execute();
            }

            return true;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al modificar el ingrediente: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para eliminar los alérgenos asociados a un ingrediente
    public function eliminarAlergenos($ingredienteId)
    {
        try {
            $sql = "DELETE FROM alergenos_tiene_ingredientes WHERE ingredientes_id_ingredientes = :ingrediente_id";
            $stm = $this->con->prepare($sql);
            $stm->execute(['ingrediente_id' => $ingredienteId]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al eliminar alérgenos asociados: " . $e->getMessage()]);
        }
    }

    // Método para eliminar un ingrediente
    public function eliminar($id)
    {
        try {
            $this->eliminarAlergenos($id);
            $sql = "DELETE FROM ingredientes WHERE id_ingredientes = :id";
            $stm = $this->con->prepare($sql);
            return $stm->execute(['id' => $id]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al eliminar el ingrediente: " . $e->getMessage()]);
            return false;
        }
    }
}
?>
