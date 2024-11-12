<?php
class RepoLinea_Pedido
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para obtener una línea de pedido por ID
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM linea_pedido WHERE id_linea_pedido = :id";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id' => $id]);
            $registro = $stm->fetch(PDO::FETCH_ASSOC);

            if ($registro) {
                $linea_pedido = new Linea_Pedido(
                    $registro['id_linea_pedido'],
                    $registro['cantidad'],
                    $registro['precio'],
                    $registro['linea_pedidos'],  // Aquí debe estar el JSON
                    $registro['pedidos_id_pedidos']
                );
                return $linea_pedido;
            } else {
                echo json_encode(["error" => "Linea de pedido no encontrada."]);
                return null;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener la línea de pedido: " . $e->getMessage()]);
            return null;
        }
    }

    // Método para crear una nueva línea de pedido
    public function crear(Linea_Pedido $linea_pedido)
    {
        try {
            $sql = "INSERT INTO linea_pedido (cantidad, precio, linea_pedidos, pedidos_id_pedidos) 
                    VALUES (:cantidad, :precio, :linea_pedidos, :id_pedidos)";
            $stm = $this->con->prepare($sql);

            $stm->bindValue(':cantidad', $linea_pedido->getCantidad());
            $stm->bindValue(':precio', $linea_pedido->getPrecio());
            $stm->bindValue(':linea_pedidos', json_encode($linea_pedido->getLineaPedidos()));
            $stm->bindValue(':id_pedidos', $linea_pedido->getIdPedidos());

            if ($stm->execute()) {
                return true;
            } else {
                echo json_encode(["error" => "No se pudo crear la línea de pedido."]);
                return false;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al crear la línea de pedido: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para actualizar una línea de pedido
    public function modificar(Linea_Pedido $linea_pedido)
{
    try {
        // Validación adicional antes de la consulta
        $linea_pedidos_json = $linea_pedido->getLineaPedidos(); // Obtiene el JSON
        if (json_decode($linea_pedidos_json) === null) {
            echo json_encode(["error" => "El valor de 'linea_pedidos' no es un JSON válido."]);
            return false;
        }

        $sql = "UPDATE linea_pedido SET cantidad = :cantidad, precio = :precio, 
                linea_pedidos = :linea_pedidos, pedidos_id_pedidos = :id_pedidos
                WHERE id_linea_pedido = :id";
        $stm = $this->con->prepare($sql);
        $stm->bindValue(':id', $linea_pedido->getIdLineaPedido(), PDO::PARAM_INT);
        $stm->bindValue(':cantidad', $linea_pedido->getCantidad());
        $stm->bindValue(':precio', $linea_pedido->getPrecio());
        $stm->bindValue(':linea_pedidos', $linea_pedidos_json); // Asegúrate de pasar el JSON válido
        $stm->bindValue(':id_pedidos', $linea_pedido->getIdPedidos());

        if ($stm->execute()) {
            return true;
        } else {
            echo json_encode(["error" => "No se pudo modificar la línea de pedido."]);
            return false;
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error al modificar la línea de pedido: " . $e->getMessage()]);
        return false;
    }
}


    // Método para eliminar una línea de pedido
    public function eliminar($id_linea_pedido)
    {
        try {
            $sql = "DELETE FROM linea_pedido WHERE id_linea_pedido = :id_linea_pedido";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id_linea_pedido', $id_linea_pedido, PDO::PARAM_INT);
            $stm->execute();

            return $stm->rowCount() > 0;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al eliminar la línea de pedido: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para mostrar todas las líneas de pedido
    public function mostrarTodos()
    {
        try {
            $sql = "SELECT * FROM linea_pedido";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

            $lineas_pedido = [];
            foreach ($registros as $registro) {
                $linea_pedido = new Linea_Pedido(
                    $registro['id_linea_pedido'],
                    $registro['cantidad'],
                    $registro['precio'],
                    $registro['linea_pedidos'], // Aquí debe estar el JSON
                    $registro['pedidos_id_pedidos']
                );
                $lineas_pedido[] = $linea_pedido;
            }
            return $lineas_pedido;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al mostrar las líneas de pedido: " . $e->getMessage()]);
            return [];
        }
    }
}
