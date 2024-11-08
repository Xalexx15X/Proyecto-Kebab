<?php

class RepoKebab
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar un kebab por ID, incluyendo sus ingredientes
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from kebab where id_kebab = :id");
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
            $kebab->setIngredientes($this->findIngredientesByKebabId($registro['id_kebab']));
            return $kebab;
        }
        return null;
    }

    // Método para encontrar los ingredientes relacionados con un kebab
    private function findIngredientesByKebabId($id_kebab)
    {
        $ingredientes = [];
        $stm = $this->con->prepare("select i.* from ingredientes i
                                    inner join kebab_tiene_ingrediente kti on i.id_ingrediente = kti.ingrediente_id_ingrediente
                                    where kti.kebab_id_kebab = :id_kebab");
        $stm->execute(['id_kebab' => $id_kebab]);
        $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($registros as $registro) {
            $ingredientes[] = new Ingredientes(
                $registro['id_ingrediente'],
                $registro['nombre'],
                $registro['foto'],
                $registro['precio'],
                $registro['tipo']
            );
        }
        return $ingredientes;
    }

    // Método para crear un kebab y asociarlo con ingredientes
    public function crear(Kebab $kebab)
    {
        try {
            $sql = "insert into kebab (nombre, foto, precio_min, descripcion) values (:nombre, :foto, :precio_min, :descripcion)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $kebab->getNombre());
            $stm->bindParam(':foto', $kebab->getFoto());
            $stm->bindParam(':precio_min', $kebab->getPrecioMin());
            $stm->bindParam(':descripcion', $kebab->getDescripcion());
            $stm->execute();

            // Obtener el ID del kebab recién creado
            $kebab_id = $this->con->lastInsertId();

            // Asociar ingredientes
            foreach ($kebab->getIngredientes() as $ingrediente) {
                $this->agregarRelacionKebabIngrediente($kebab_id, $ingrediente->getIdIngrediente());
            }

            return true;
        } catch (PDOException $e) {
            return "Error al crear el kebab: " . $e->getMessage();
        }
    }

    // Método para agregar una relación entre un kebab y un ingrediente
    private function agregarRelacionKebabIngrediente($kebab_id, $ingrediente_id)
    {
        $sql = "insert into kebab_tiene_ingrediente (kebab_id_kebab, ingrediente_id_ingrediente) values (:kebab_id, :ingrediente_id)";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':kebab_id', $kebab_id);
        $stm->bindParam(':ingrediente_id', $ingrediente_id);
        $stm->execute();
    }

    // Método para modificar un kebab y sus ingredientes asociados
    public function modificar(Kebab $kebab)
    {
        try {
            $sql = "update kebab set nombre = :nombre, foto = :foto, precio_min = :precio_min, descripcion = :descripcion where id_kebab = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $kebab->getNombre());
            $stm->bindParam(':foto', $kebab->getFoto());
            $stm->bindParam(':precio_min', $kebab->getPrecioMin());
            $stm->bindParam(':descripcion', $kebab->getDescripcion());
            $stm->bindParam(':id', $kebab->getIdKebab(), PDO::PARAM_INT);
            $stm->execute();

            // Actualizar relaciones con ingredientes
            $this->actualizarRelacionIngredientes($kebab);

            return true;
        } catch (PDOException $e) {
            return "Error al modificar el kebab: " . $e->getMessage();
        }
    }

    // Método para actualizar las relaciones entre un kebab y sus ingredientes
    private function actualizarRelacionIngredientes(Kebab $kebab)
    {
        // Eliminar relaciones actuales
        $sql = "delete from kebab_tiene_ingrediente where kebab_id_kebab = :kebab_id";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':kebab_id', $kebab->getIdKebab());
        $stm->execute();

        // Agregar las nuevas relaciones
        foreach ($kebab->getIngredientes() as $ingrediente) {
            $this->agregarRelacionKebabIngrediente($kebab->getIdKebab(), $ingrediente->getIdIngrediente());
        }
    }

    // Método para borrar un kebab y sus relaciones con ingredientes
    public function borrar($id)
    {
        try {
            // Eliminar relaciones con ingredientes
            $sql = "delete from kebab_tiene_ingrediente where kebab_id_kebab = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            // Eliminar el kebab
            $sql = "delete from kebab where id_kebab = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            return true;
        } catch (PDOException $e) {
            return "Error al borrar el kebab: " . $e->getMessage();
        }
    }

    // Método para mostrar todos los kebabs con sus ingredientes
    public function mostrarTodos()
    {
        try {
            $sql = "select * from kebab";
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
                $kebab->setIngredientes($this->findIngredientesByKebabId($registro['id_kebab']));
                $kebabs[] = $kebab;
            }

            return $kebabs;
        } catch (PDOException $e) {
            return "Error al mostrar los kebabs: " . $e->getMessage();
        }
    }
}
