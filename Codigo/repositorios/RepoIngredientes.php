<?php

class RepoIngredientes
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar un ingrediente por ID, incluyendo sus alérgenos
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from ingredientes where id_ingrediente = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            $ingrediente = new Ingredientes(
                $registro['id_ingrediente'], 
                $registro['nombre'],  
                $registro['foto'], 
                $registro['precio'], 
                $registro['tipo']
            );
            $ingrediente->setAlergenos($this->findAlergenosByIngredienteId($registro['id_ingrediente']));
            return $ingrediente;
        }
        return null;
    }

    // Método para encontrar los alérgenos relacionados con un ingrediente
    private function findAlergenosByIngredienteId($id_ingrediente)
    {
        $alergenos = [];
        $stm = $this->con->prepare("select a.* from alergenos a 
                                    inner join ingrediente_tiene_alergeno ita on a.id_alergeno = ita.alergeno_id_alergeno 
                                    WHERE ita.ingrediente_id_ingrediente = :id_ingrediente");
        $stm->execute(['id_ingrediente' => $id_ingrediente]);
        $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($registros as $registro) {
            $alergenos[] = new Alergenos($registro['id_alergeno'], $registro['nombre_alergeno']);
        }
        return $alergenos;
    }

    // Método para crear un ingrediente y asociarlo con alérgenos
    public function crear(Ingredientes $ingrediente)
    {
        try {
            $sql = "insert into ingredientes (nombre, foto, precio, tipo) values (:nombre, :foto, :precio, :tipo)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $ingrediente->getNombre());
            $stm->bindParam(':foto', $ingrediente->getFoto());
            $stm->bindParam(':precio', $ingrediente->getPrecio());
            $stm->bindParam(':tipo', $ingrediente->getTipo());
            $stm->execute();

            // Obtener el ID del ingrediente recién creado
            $ingrediente_id = $this->con->lastInsertId();

            // Asociar alérgenos
            foreach ($ingrediente->getAlergenos() as $alergeno) {
                $this->agregarRelacionIngredienteAlergeno($ingrediente_id, $alergeno->getIdAlergenos());
            }

            return true;
        } catch (PDOException $e) {
            return "Error al crear el ingrediente: " . $e->getMessage();
        }
    }

    // Método para agregar una relación entre un ingrediente y un alérgeno
    private function agregarRelacionIngredienteAlergeno($ingrediente_id, $alergeno_id)
    {
        $sql = "insert into ingrediente_tiene_alergeno (ingrediente_id_ingrediente, alergeno_id_alergeno) values (:ingrediente_id, :alergeno_id)";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':ingrediente_id', $ingrediente_id);
        $stm->bindParam(':alergeno_id', $alergeno_id);
        $stm->execute();
    }

    // Método para modificar un ingrediente y sus alérgenos asociados
    public function modificar(Ingredientes $ingrediente)
    {
        try {
            $sql = "update ingredientes set nombre = :nombre, foto = :foto, precio = :precio, tipo = :tipo where id_ingrediente = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $ingrediente->getNombre());
            $stm->bindParam(':foto', $ingrediente->getFoto());
            $stm->bindParam(':precio', $ingrediente->getPrecio());
            $stm->bindParam(':tipo', $ingrediente->getTipo());
            $stm->bindParam(':id', $ingrediente->getIdIngrediente(), PDO::PARAM_INT);
            $stm->execute();

            // Actualizar relaciones con alérgenos
            $this->actualizarRelacionAlergenos($ingrediente);

            return true;
        } catch (PDOException $e) {
            return "Error al modificar el ingrediente: " . $e->getMessage();
        }
    }

    // Método para actualizar las relaciones entre un ingrediente y sus alérgenos
    private function actualizarRelacionAlergenos(Ingredientes $ingrediente)
    {
        // Eliminar relaciones actuales
        $sql = "delete from ingrediente_tiene_alergeno where ingrediente_id_ingrediente = :ingrediente_id";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':ingrediente_id', $ingrediente->getIdIngrediente());
        $stm->execute();

        // Agregar las nuevas relaciones
        foreach ($ingrediente->getAlergenos() as $alergeno) {
            $this->agregarRelacionIngredienteAlergeno($ingrediente->getIdIngrediente(), $alergeno->getIdAlergenos());
        }
    }

    // Método para borrar un ingrediente y sus relaciones con alérgenos
    public function borrar($id)
    {
        try {
            // Eliminar relaciones con alérgenos
            $sql = "delete from ingrediente_tiene_alergeno where ingrediente_id_ingrediente = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            // Eliminar el ingrediente
            $sql = "DELETE FROM ingredientes WHERE id_ingrediente = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            return true;
        } catch (PDOException $e) {
            return "Error al borrar el ingrediente: " . $e->getMessage();
        }
    }

    // Método para mostrar todos los ingredientes con sus alérgenos
    public function mostrarTodos()
    {
        try {
            $sql = "select * from ingredientes";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
            $ingredientes = [];

            foreach ($registros as $registro) {
                $ingrediente = new Ingredientes(
                    $registro['id_ingrediente'], 
                    $registro['nombre'], 
                    $registro['foto'], 
                    $registro['precio'], 
                    $registro['tipo']
                );
                $ingrediente->setAlergenos($this->findAlergenosByIngredienteId($registro['id_ingrediente']));
                $ingredientes[] = $ingrediente;
            }

            return $ingredientes;
        } catch (PDOException $e) {
            return "Error al mostrar los ingredientes: " . $e->getMessage();
        }
    }
}
