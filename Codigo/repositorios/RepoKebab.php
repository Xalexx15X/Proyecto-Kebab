<?php
    /*
        Clase para gestionar los kebabs
        
        Métodos:
            findById($id_kebab): Obtener un kebab por su ID
            crear($kebab): Crear un nuevo kebab
            modificar($kebab): Modificar un kebab
            eliminar($id_kebab): Eliminar un kebab por su ID
            mostrarTodos(): Obtener todos los kebabs
            
        TODO: Implementar métodos para gestionar los kebabs (findById, crear, modificar, eliminar, mostrarTodos)
        * Obtener un kebab por su ID: Obtener un kebab por su ID
        * Crear un nuevo kebab: Crear un nuevo kebab
        * Modificar un kebab: Modificar un kebab
        * Eliminar un kebab por su ID: Eliminar un kebab por su ID
        * Obtener todos los kebabs: Obtener todos los kebabs    
    */  


class RepoKebab
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para obtener un kebab por ID
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM kebab WHERE id_kebab = :id";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id' => $id]);
            $registro = $stm->fetch(PDO::FETCH_ASSOC);

            if ($registro) {
                $kebab = new Kebab(
                    $registro['id_kebab'],
                    $registro['nombre'],
                    $registro['foto'],
                    $registro['precio_min'],
                    $registro['descripcion']
                );

                // Cargar ingredientes para el kebab
                $kebab->setIngredientes($this->findIngredientesByKebabId($registro['id_kebab']));
                return $kebab;
            } else {
                echo json_encode(["error" => "Kebab no encontrado."]);
                return null;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener el kebab: " . $e->getMessage()]);
            return null;
        }
    }

    // Método para crear un nuevo kebab
    public function crear(Kebab $kebab)
    {
        try {
            $sql = "INSERT INTO kebab (nombre, foto, precio_min, descripcion) 
                    VALUES (:nombre, :foto, :precio_min, :descripcion)";
            $stm = $this->con->prepare($sql);

            $stm->bindValue(':nombre', $kebab->getNombre());
            $stm->bindValue(':foto', $kebab->getFoto());
            $stm->bindValue(':precio_min', $kebab->getPrecioMin());
            $stm->bindValue(':descripcion', $kebab->getDescripcion());

            if ($stm->execute()) {
                $kebab_id = $this->con->lastInsertId();
                $kebab->setIdKebab($kebab_id);

                // Asociar ingredientes
                foreach ($kebab->getIngredientes() as $ingrediente_id) {
                    $this->agregarRelacionKebabIngrediente($kebab_id, $ingrediente_id);
                }

                return true;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al crear el kebab: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para actualizar un kebab
    public function modificar(Kebab $kebab)
    {
        try {
            $sql = "UPDATE kebab SET nombre = :nombre, foto = :foto, precio_min = :precio_min, descripcion = :descripcion
                    WHERE id_kebab = :id";
            $stm = $this->con->prepare($sql);

            $stm->bindValue(':nombre', $kebab->getNombre());
            $stm->bindValue(':foto', $kebab->getFoto());
            $stm->bindValue(':precio_min', $kebab->getPrecioMin());
            $stm->bindValue(':descripcion', $kebab->getDescripcion());
            $stm->bindValue(':id', $kebab->getIdKebab(), PDO::PARAM_INT);

            if ($stm->execute()) {
                // Actualizar relación con ingredientes
                $this->actualizarRelacionIngredientes($kebab);
                return true;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al modificar el kebab: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para actualizar la relación de ingredientes de un kebab
    private function actualizarRelacionIngredientes(Kebab $kebab)
    {
        $idKebab = $kebab->getIdKebab();
        
        // Eliminar todas las relaciones actuales
        $sqlDelete = "DELETE FROM ingredientes_tiene_kebab WHERE kebab_id_kebab = :id_kebab";
        $stmDelete = $this->con->prepare($sqlDelete);
        $stmDelete->bindParam(':id_kebab', $idKebab, PDO::PARAM_INT);
        $stmDelete->execute();

        // Agregar nuevas relaciones
        foreach ($kebab->getIngredientes() as $ingredienteId) {
            $this->agregarRelacionKebabIngrediente($idKebab, $ingredienteId);
        }
    }

    // Método para agregar la relación entre un kebab y un ingrediente
    private function agregarRelacionKebabIngrediente($kebab_id, $ingrediente_id)
    {
        $sql = "INSERT INTO ingredientes_tiene_kebab (kebab_id_kebab, ingredientes_id_ingredientes) 
                VALUES (:kebab_id_kebab, :ingredientes_id)";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':kebab_id_kebab', $kebab_id, PDO::PARAM_INT);
        $stm->bindParam(':ingredientes_id', $ingrediente_id, PDO::PARAM_INT);
        $stm->execute();
    }

    // Método para eliminar un kebab
    public function eliminarKebab($id_kebab)
    {
        try {
            // Eliminar las relaciones del kebab con los ingredientes
            $sql = "DELETE FROM ingredientes_tiene_kebab WHERE kebab_id_kebab = :id_kebab";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id_kebab', $id_kebab, PDO::PARAM_INT);
            $stm->execute();

            // Eliminar el kebab de la tabla 'kebab'
            $sql = "DELETE FROM kebab WHERE id_kebab = :id_kebab";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id_kebab', $id_kebab, PDO::PARAM_INT);
            $stm->execute();

            return $stm->rowCount() > 0;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al eliminar el kebab: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para mostrar todos los kebabs con sus ingredientes
    public function mostrarTodos()
    {
        try {
            $sql = "SELECT * FROM kebab";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

            $kebabs = [];
            foreach ($registros as $registro) {
                $kebab = new Kebab(
                    $registro['id_kebab'],
                    $registro['nombre'],
                    $registro['foto'],
                    $registro['precio_min'],
                    $registro['descripcion']
                );

                // Cargar ingredientes para cada kebab
                $kebab->setIngredientes($this->findIngredientesByKebabId($registro['id_kebab']));
                $kebabs[] = $kebab;
            }
            return $kebabs;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al mostrar los kebabs: " . $e->getMessage()]);
            return [];
        }
    }

    // Método para buscar ingredientes asociados a un kebab
    private function findIngredientesByKebabId($id_kebab)
    {
        $ingredientes = [];
        $sql = "SELECT i.* FROM ingredientes i
                INNER JOIN ingredientes_tiene_kebab ik ON i.id_ingredientes = ik.ingredientes_id_ingredientes
                WHERE ik.kebab_id_kebab = :id_kebab";
        $stm = $this->con->prepare($sql);
        $stm->execute(['id_kebab' => $id_kebab]);
        $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($registros as $registro) {
            $ingredientes[] = new Ingredientes(
                $registro['id_ingredientes'], 
                $registro['nombre'], 
                $registro['foto'] ?? '', 
                $registro['precio']
            );
        }
        return $ingredientes;
    }
}
?>
