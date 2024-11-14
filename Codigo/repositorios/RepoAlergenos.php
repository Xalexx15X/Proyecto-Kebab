<?php
class RepoAlergenos
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Obtener un alérgeno por su ID
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM alergenos WHERE id_alergenos = :id";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id' => $id]);
            $registro = $stm->fetch(PDO::FETCH_ASSOC);

            if ($registro) {
                $alergeno = new Alergenos(
                    $registro['id_alergenos'],
                    $registro['nombre'],
                    $registro['foto'],
                    $this->findIngredientesByAlergenoId($registro['id_alergenos']),
                    $this->findUsuariosByAlergenoId($registro['id_alergenos'])
                );
                return $alergeno;
            }
            return null;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener el alérgeno: " . $e->getMessage()]);
            return null;
        }
    }

    // Crear un alérgeno
    public function crear(Alergenos $alergeno)
    {
        try {
            // Insertar el alérgeno en la tabla `alergenos`
            $sql = "INSERT INTO alergenos (nombre, foto) VALUES (:nombre, :foto)";
            $stm = $this->con->prepare($sql);
            $stm->bindValue(':nombre', $alergeno->getNombre());
            $stm->bindValue(':foto', $alergeno->getFoto());

            if ($stm->execute()) {
                // Obtener el ID del alérgeno recién creado
                $alergeno_id = $this->con->lastInsertId();
                $alergeno->setIdAlergenos($alergeno_id);
                return $alergeno_id;  // Retorna el ID del nuevo alérgeno
            }
            return false;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al crear el alérgeno: " . $e->getMessage()]);
            return false;
        }
    }


    // Relación de alérgenos con ingredientes
    public function agregarRelacionAlergenoIngrediente($alergeno_id, $ingredientes)
    {
        try {
            // Recorrer la lista de ingredientes y asociarlos con el alérgeno
            foreach ($ingredientes as $ingrediente_id) {
                $sql = "INSERT INTO alergenos_tiene_ingredientes (alergenos_id_alergenos, ingredientes_id_ingredientes) 
                        VALUES (:alergeno_id_alergenos, :ingrediente_id)";
                $stm = $this->con->prepare($sql);
                $stm->bindParam(':alergeno_id_alergenos', $alergeno_id, PDO::PARAM_INT);
                $stm->bindParam(':ingrediente_id', $ingrediente_id, PDO::PARAM_INT);
                $stm->execute();
            }
            return true;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al agregar relación con ingrediente: " . $e->getMessage()]);
            return false;
        }
    }


    public function agregarRelacionAlergenoUsuario($alergeno_id, $usuarios)
    {
        try {
            // Recorrer la lista de usuarios y asociarlos con el alérgeno
            foreach ($usuarios as $usuario_id) {
                $sql = "INSERT INTO usuario_tiene_alergenos (usuario_id_usuario, alergenos_id_alergenos) 
                        VALUES (:usuario_id_usuario, :alergeno_id)";
                $stm = $this->con->prepare($sql);
                $stm->bindParam(':usuario_id_usuario', $usuario_id, PDO::PARAM_INT);
                $stm->bindParam(':alergeno_id', $alergeno_id, PDO::PARAM_INT);
                $stm->execute();
            }
            return true;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al agregar relación con usuario: " . $e->getMessage()]);
            return false;
        }
    }


    // Obtener todos los ingredientes de un alérgeno
    public function findIngredientesByAlergenoId($id_alergeno)
    {
        $ingredientes = [];
        $sql = "SELECT i.* FROM ingredientes i
                INNER JOIN alergenos_tiene_ingredientes ai ON i.id_ingredientes = ai.ingredientes_id_ingredientes
                WHERE ai.alergenos_id_alergenos = :id_alergeno";
        $stm = $this->con->prepare($sql);
        $stm->execute(['id_alergeno' => $id_alergeno]);
        $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($registros as $registro) {
            $ingredientes[] = $registro['id_ingredientes'];
        }
        return $ingredientes;
    }

    // Obtener todos los usuarios que tienen este alérgeno
    public function findUsuariosByAlergenoId($id_alergeno)
    {
        $usuarios = [];
        $sql = "SELECT u.* FROM usuario u
                INNER JOIN usuario_tiene_alergenos ua ON u.id_usuario = ua.usuario_id_usuario
                WHERE ua.alergenos_id_alergenos = :id_alergeno";
        $stm = $this->con->prepare($sql);
        $stm->execute(['id_alergeno' => $id_alergeno]);
        $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($registros as $registro) {
            $usuarios[] = $registro['id_usuario'];
        }
        return $usuarios;
    }

    // Eliminar alérgeno
    public function eliminar($id_alergeno)
    {
        try {
            $sql = "DELETE FROM alergenos_tiene_ingredientes WHERE alergenos_id_alergenos = :id_alergeno";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id_alergeno' => $id_alergeno]);

            $sql = "DELETE FROM usuario_tiene_alergenos WHERE alergenos_id_alergenos = :id_alergeno";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id_alergeno' => $id_alergeno]);

            $sql = "DELETE FROM alergenos WHERE id_alergenos = :id_alergeno";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id_alergeno' => $id_alergeno]);

            return true;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al eliminar el alérgeno: " . $e->getMessage()]);
            return false;
        }
    }

    // Actualizar un alérgeno
    public function modificar(Alergenos $alergeno)
    {
        try {
            // Actualizar el alérgeno en la tabla `alergenos`
            $sql = "UPDATE alergenos SET nombre = :nombre, foto = :foto WHERE id_alergenos = :id_alergenos";
            $stm = $this->con->prepare($sql);
            $stm->bindValue(':nombre', $alergeno->getNombre());
            $stm->bindValue(':foto', $alergeno->getFoto());
            $stm->bindValue(':id_alergenos', $alergeno->getIdAlergenos());
            $stm->execute();

            // Eliminar las relaciones previas de ingredientes y usuarios
            $this->eliminarRelaciones($alergeno->getIdAlergenos());

            // Insertar las nuevas relaciones
            foreach ($alergeno->getIngredientes() as $ingrediente_id) {
                $this->agregarRelacionAlergenoIngrediente($alergeno->getIdAlergenos(), $ingrediente_id);
            }

            foreach ($alergeno->getUsuarios() as $usuario_id) {
                $this->agregarRelacionAlergenoUsuario($alergeno->getIdAlergenos(), $usuario_id);
            }

            return true;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al actualizar el alérgeno: " . $e->getMessage()]);
            return false;
        }
    }

    // Eliminar relaciones previas de ingredientes y usuarios
    public function eliminarRelaciones($id_alergeno)
    {
        // Eliminar las relaciones con los ingredientes
        $sql = "DELETE FROM alergenos_tiene_ingredientes WHERE alergenos_id_alergenos = :id_alergeno";
        $stm = $this->con->prepare($sql);
        $stm->execute(['id_alergeno' => $id_alergeno]);

        // Eliminar las relaciones con los usuarios
        $sql = "DELETE FROM usuario_tiene_alergenos WHERE alergenos_id_alergenos = :id_alergeno";
        $stm = $this->con->prepare($sql);
        $stm->execute(['id_alergeno' => $id_alergeno]);
    }

    // Mostrar todos los alérgenos
    public function mostrarTodos()
    {
        try {
            $sql = "SELECT * FROM alergenos";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

            $alergenos = [];
            foreach ($registros as $registro) {
                $alergenos[] = new Alergenos(
                    $registro['id_alergenos'],
                    $registro['nombre'],
                    $registro['foto'],
                    $this->findIngredientesByAlergenoId($registro['id_alergenos']),
                    $this->findUsuariosByAlergenoId($registro['id_alergenos'])
                );
            }
            return $alergenos;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al mostrar los alérgenos: " . $e->getMessage()]);
            return [];
        }
    }
}
