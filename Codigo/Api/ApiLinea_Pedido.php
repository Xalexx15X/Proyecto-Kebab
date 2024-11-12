<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoLineaPedido = new RepoLinea_Pedido($con);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

if ($method === 'GET') {
    if (isset($_GET['id_linea_pedido'])) {
        $lineaPedido = $repoLineaPedido->findById($_GET['id_linea_pedido']);
        if ($lineaPedido) {
            http_response_code(200);
            echo json_encode($lineaPedido);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Linea de pedido no encontrada."]);
        }
    } else {
        $lineasPedidos = $repoLineaPedido->mostrarTodos();
        http_response_code(200);
        echo json_encode($lineasPedidos);
    }
} elseif ($method === 'POST') {
    if (isset($input['cantidad'], $input['precio'], $input['linea_pedidos'], $input['id_pedidos'])) {
        $lineaPedido = new Linea_Pedido(
            null,  // ID será autoincrementado por la base de datos
            $input['cantidad'],
            $input['precio'],
            $input['linea_pedidos'],
            $input['id_pedidos']
        );

        if ($repoLineaPedido->crear($lineaPedido)) {
            echo json_encode(["message" => "Linea de pedido creada correctamente."]);
        } else {
            echo json_encode(["error" => "No se pudo crear la línea de pedido."]);
        }
    } else {
        echo json_encode(["error" => "Datos incompletos."]);
    }
} elseif ($method === 'PUT') {
    // Verificar que los datos necesarios estén presentes
    if (isset($input['id_linea_pedido'], $input['cantidad'], $input['precio'], $input['linea_pedidos'], $input['id_pedidos'])) {
        // Verificar si la línea de pedido existe
        if ($lineaPedido) {
            // Actualizar los datos de la línea de pedido
            $lineaPedido->getIdLineaPedido($input['id_linea_pedido']);
            $lineaPedido->setCantidad($input['cantidad']);
            $lineaPedido->setPrecio($input['precio']);
            $lineaPedido->setLineaPedidos($input['linea_pedidos']);  // Asegúrate de que sea el formato correcto
            $lineaPedido->setIdPedidos($input['id_pedidos']);  // Debe ser setIdPedidos, pero asegúrate de que este valor sea necesario
            
            // Actualizar la línea de pedido en la base de datos
            if ($repoLineaPedido->modificar($lineaPedido)) {
                http_response_code(200);
                echo json_encode(["success" => true, "mensaje" => "Línea de pedido actualizada correctamente."]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al actualizar la línea de pedido."]);
            }
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Línea de pedido no encontrada."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos insuficientes para actualizar la línea de pedido."]);
    }
} elseif ($method === 'DELETE') {
    if (isset($input['id_linea_pedido'])) {
        if ($repoLineaPedido->eliminar($input['id_linea_pedido'])) {
            http_response_code(200);
            echo json_encode(["success" => true, "mensaje" => "Línea de pedido eliminada correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar la línea de pedido."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID de línea de pedido no proporcionado."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no soportado."]);
}
?>
