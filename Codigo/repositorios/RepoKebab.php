<?php
class RepoKebab
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar un kebab por ID
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from kebab where id_kebab = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            return new Kebab($registro['id_kebab'], $registro['nombre'], $registro['foto'], $registro['precio_min'], $registro['descripcion']);
        }
        return null;
    }

    // Método para crear un kebab
    public function crear(Kebab $kebab)
    {
        try {
            $sql = "insert into kebab (nombre, foto, precio_min, descripcion) values (:nombre, :foto, :precio_min, :descripcion)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $kebab->nombre);
            $stm->bindParam(':foto', $kebab->foto);
            $stm->bindParam(':precio_min', $kebab->precio_min);
            $stm->bindParam(':descripcion', $kebab->descripcion);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al crear el Kebab: " . $e->getMessage();
        }
    }

    // Método para modificar un kebab
    public function modificar(Kebab $kebab)
    {
        try {
            $sql = "update kebab SET nombre = :nombre, foto = :foto, precio_min = :precio_min, descripcion = :descripcion WHERE id_kebab = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $kebab->nombre);
            $stm->bindParam(':foto', $kebab->foto);
            $stm->bindParam(':precio_min', $kebab->precio_min);
            $stm->bindParam(':descripcion', $kebab->descripcion);
            $stm->bindParam(':id', $kebab->id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al modificar el kebab: " . $e->getMessage();
        }
    }

    // Método para borrar un kebab por ID
    public function borrar($id)
    {
        try {
            $sql = "delete from kebab where id_kebab = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al borrar el kebab: " . $e->getMessage();
        }
    }

    // Método para mostrar todos los kebabs
    public function mostrarTodos()
    {
        try {
            $sql = "select * from kebab";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
            $kebabs = [];

            foreach ($registros as $registro) {
                $kebabs[] = new Kebab($registro['id_kebab'], $registro['nombre'], $registro['foto'], $registro['precio_min'], $registro['descripcion']);
            }
            return $kebabs;
        } catch (PDOException $e) {
            return "Error al mostrar los kebabs: " . $e->getMessage();
        }
    }
}
