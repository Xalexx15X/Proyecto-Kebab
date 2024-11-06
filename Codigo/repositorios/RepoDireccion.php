<?php

class RepoDireccion
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar una dirección por ID
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from direccion where id = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            return new Direccion($registro['id'], $registro['nombre_calle'], $registro['n_calle'], $registro['tipo_casa'], $registro['n_casa']);
        }
        return null;
    }

    // Método para crear una dirección
    public function crear(Direccion $direccion)
    {
        try {
            $sql = "insert into direccion (nombre_calle, n_calle, tipo_casa, n_casa) values (:nombre_calle, :n_calle, :tipo_casa, :n_casa)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre_calle', $direccion->nombre_calle);
            $stm->bindParam(':n_calle', $direccion->n_calle);
            $stm->bindParam(':tipo_casa', $direccion->tipo_casa);
            $stm->bindParam(':n_casa', $direccion->n_casa);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al crear la dirección: " . $e->getMessage();
        }
    }

    // Método para modificar una dirección existente
    public function modificar(Direccion $direccion)
    {
        try {
            $sql = "update direccion set nombre_calle = :nombre_calle, n_calle = :n_calle, tipo_casa = :tipo_casa, n_casa = :n_casa where id = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre_calle', $direccion->nombre_calle);
            $stm->bindParam(':n_calle', $direccion->n_calle);
            $stm->bindParam(':tipo_casa', $direccion->tipo_casa);
            $stm->bindParam(':n_casa', $direccion->n_casa);
            $stm->bindParam(':id', $direccion->id);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al modificar la dirección: " . $e->getMessage();
        }
    }

    // Método para eliminar una dirección
    public function eliminar($id)
    {
        try {
            $sql = "delete from direccion where id = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al eliminar la dirección: " . $e->getMessage();
        }
    }
    
        // Método para mostrar todos los ingredientes
        public function mostrarTodos()
        {
            try {
                $sql = "select * from direccion";
                $stm = $this->con->prepare($sql);
                $stm->execute();
                $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
                $ingredientes = [];
    
                foreach ($registros as $registro) {
                    $ingredientes[] = new Ingredientes($registro['id'], $registro['nombre'], $registro['precio']);
                }
                return $ingredientes;
            } catch (PDOException $e) {
                return "Error al mostrar los ingredientes: " . $e->getMessage();
            }
        }
}               