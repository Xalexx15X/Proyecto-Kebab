<?php
class RepoIngredientes
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar un ingrediente por ID
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from ingredientes where id_ingrediente = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            return new Ingredientes($registro['id_ingrediente'], $registro['nombre'],  $registro['foto'], $registro['precio'], $registro['tipo']);
        }
        return null;
    }

    // Método para crear un ingrediente
    public function crear(Ingredientes $ingrediente)
    {
        try {
            $sql = "insert into ingredientes (nombre, foto, precio, tipo) values (:nombre, :foto, :precio, :tipo)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $ingrediente->nombre);
            $stm->bindParam(':foto', $ingrediente->foto);
            $stm->bindParam(':precio', $ingrediente->precio);
            $stm->bindParam(':tipo', $ingrediente->tipo);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al crear el ingrediente: " . $e->getMessage();
        }
    }

    // Método para modificar un ingrediente existente
    public function modificar(Ingredientes $ingrediente)
    {
        try {
            $sql = "update ingredientes set nombre = :nombre, foto = :foto, precio = :precio, tipo = :tipo  WHERE id_ingrediente = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $ingrediente->nombre);
            $stm->bindParam(':foto', $ingrediente->foto);
            $stm->bindParam(':precio', $ingrediente->precio);
            $stm->bindParam(':tipo', $ingrediente->tipo);
            $stm->bindParam(':id', $ingrediente->id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al modificar el ingrediente: " . $e->getMessage();
        }
    }

    // Método para borrar un ingrediente por ID
    public function borrar($id)
    {
        try {
            $sql = "delete from ingredientes where id_ingrediente = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al borrar el ingrediente: " . $e->getMessage();
        }
    }

    // Método para mostrar todos los ingredientes
    public function mostrarTodos()
    {
        try {
            $sql = "select * from ingredientes";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
            $ingredientes = [];

            foreach ($registros as $registro) {
                $ingredientes[] = new Ingredientes($registro['id_ingrediente'], $registro['nombre'], $registro['foto'], $registro['precio'], $registro['tipo']);
            }
            return $ingredientes;
        } catch (PDOException $e) {
            return "Error al mostrar los ingredientes: " . $e->getMessage();
        }
    }
}
?>
