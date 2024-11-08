<?php
class RepoAlergenos
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar un alergeno por ID y cargar sus ingredientes y usuarios
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from alergenos where id_alergenos = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            // Cargar ingredientes y usuarios asociados al alergeno
            $ingredientes = $this->cargarIngredientes($id);
            $usuarios = $this->cargarUsuarios($id);

            return new Alergenos($registro['id_alergenos'], $registro['nombre'], $registro['foto'], $registro['descripcion'], $ingredientes, $usuarios);
        }
        return null;
    }

    // Método para cargar los ingredientes asociados a un alergeno
    private function cargarIngredientes($id_alergeno)
    {
        $stm = $this->con->prepare("
            SELECT i.* FROM ingredientes i
            INNER JOIN alergenos_tiene_ingredientes ati ON i.id_ingredientes = ati.ingredientes_id_ingredientes
            WHERE ati.alergenos_id_alergenos = :id_alergeno
        ");
        $stm->execute(['id_alergeno' => $id_alergeno]);
        $resultados = $stm->fetchAll(PDO::FETCH_ASSOC);
        $ingredientes = [];

        foreach ($resultados as $row) {
            $ingredientes[] = new Ingredientes($row['id_ingredientes'], $row['nombre'], $row['foto'], $row['precio'], $row['tipo']);
        }

        return $ingredientes;
    }

    // Método para cargar los usuarios asociados a un alergeno
    private function cargarUsuarios($id_alergeno)
    {
        $stm = $this->con->prepare("
            SELECT u.* FROM usuario u
            INNER JOIN usuario_tiene_alergenos uta ON u.id_usuario = uta.usuario_id_usuario
            WHERE uta.alergenos_id_alergenos = :id_alergeno
        ");
        $stm->execute(['id_alergeno' => $id_alergeno]);
        $resultados = $stm->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];

        foreach ($resultados as $registro) {
            $usuarios[] = new Usuario($registro['id_usuario'], $registro['nombre'], $registro['contrasena'], $registro['carrito'], $registro['monedero'], $registro['foto'], $registro['telefono'], $registro['ubicacion']);
        }

        return $usuarios;
    }

    // Método para crear un alergeno
    public function crear(Alergenos $alergeno)
    {
        try {
            $sql = "insert into alergenos (nombre, foto, descripcion) values (:nombre, :foto, :descripcion)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $alergeno->getNombre());
            $stm->bindParam(':foto', $alergeno->getFoto());
            $stm->bindParam(':descripcion', $alergeno->getDescripcion());
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
            $sql = "update alergenos set nombre = :nombre, foto = :foto, descripcion = :descripcion where id_alergenos = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $alergeno->getNombre());
            $stm->bindParam(':foto', $alergeno->getFoto());
            $stm->bindParam(':descripcion', $alergeno->getDescripcion());
            $stm->bindParam(':id', $alergeno->getIdAlergenos(), PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al modificar el alergeno: " . $e->getMessage();
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
                // Cargar ingredientes y usuarios para cada alergeno
                $ingredientes = $this->cargarIngredientes($registro['id_alergenos']);
                $usuarios = $this->cargarUsuarios($registro['id_alergenos']);
                $alergenos[] = new Alergenos($registro['id_alergenos'], $registro['nombre'], $registro['foto'], $registro['descripcion'], $ingredientes, $usuarios);
            }

            return $alergenos;
        } catch (PDOException $e) {
            return "Error al mostrar los alergenos: " . $e->getMessage();
        }
    }
}
