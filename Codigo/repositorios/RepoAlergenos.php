<?php
class RepoAlergenos
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar un alergeno por ID
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from alergenos where id_alergeno = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            return new Alergenos($registro['id_alergeno'], $registro['nombre'], $registro['foto'], $registro['descripcion']);
        }
        return null;
    }

    // Método para crear un alergeno
    public function crear(Alergenos $alergeno)
    {
        try {
            $sql = "insert into alergenos (nombre, foto, descripcion) values (:nombre, :foto, :descripcion)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $alergeno->nombre);
            $stm->bindParam(':foto', $alergeno->foto);
            $stm->bindParam(':descripcion', $alergeno->descripcion);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al crear el alergeno: " . $e->getMessage();
        }
    }

    // Método para modificar un alergeno existente
    public function modificar(Alergenos $alergeno)
    {
        try {
            $sql = "update alergenos set nombre = :nombre foto = :foto descripcion = :descripcion where id_alergeno = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $alergeno->nombre);
            $stm->bindParam(':foto', $alergeno->foto);
            $stm->bindParam(':descripcion', $alergeno->descripcion);
            $stm->bindParam(':id', $alergeno->id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al modificar el alergeno: " . $e->getMessage();
        }
    }

    // Método para borrar un alergeno por ID
    public function borrar($id)
    {
        try {
            $sql = "delete from alergenos where id_alergeno = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al borrar el alergeno: " . $e->getMessage();
        }
    }

    // Método para mostrar todos los alergenos
    public function mostrarTodos()
    {
        try {
            $sql = "select * from alergenos";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
            $alergenos = [];

            foreach ($registros as $registro) {
                $alergenos[] = new Alergenos($registro['id_alergeno'], $registro['nombre'], $registro['foto'], $registro['descripcion']);
            }
            return $alergenos;
        } catch (PDOException $e) {
            return "Error al mostrar los alergenos: " . $e->getMessage();
        }
    }
}
?>
