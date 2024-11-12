<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoPedidos = new RepoPedido($con);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

if ($method === 'GET') {
    if (isset($_GET['id_pedido'])) {
        $pedido = $repoPedidos->findById($_GET['id_pedido']);
        if ($pedido) {
            http_response_code(200);
            echo json_encode($pedido);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Pedido no encontrado."]);
        }
    } else {
        $pedidos = $repoPedidos->mostrarTodos();
        http_response_code(200);
        echo json_encode($pedidos);
    }
} elseif ($method === 'POST') {
    if (isset($input['estado'], $input['precio_total'], $input['fecha_hora'], $input['usuario_id_usuario'])) {
        // Crear el pedido solo con el id_usuario
        $pedido = new Pedido(
            null, 
            $input['estado'], 
            $input['precio_total'], 
            $input['fecha_hora'],
            [], // Se puede llenar más tarde si tienes líneas de pedido
            $input['usuario_id_usuario'] // Solo el id_usuario
        );

        if ($repoPedidos->crear($pedido)) {
            http_response_code(201);
            echo json_encode(["success" => true, "mensaje" => "Pedido creado correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear el pedido."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos insuficientes para crear el pedido."]);
    }
} elseif ($method === 'PUT') {
    // Actualizar un pedido existente
    if (isset($input['id_pedido'], $input['estado'], $input['precio_total'], $input['fecha_hora'], $input['usuario_id_usuario'])) {
        if ($pedido) {
            $pedido->setIdPedidos($input['id_pedido']);
            $pedido->setEstado($input['estado']);
            $pedido->setPrecioTotal($input['precio_total']);
            $pedido->setFechaHora($input['fecha_hora']);
            $pedido->setIdUsuario($input['usuario_id_usuario']); // Actualizamos el id_usuario

            if ($repoPedidos->modificar($pedido)) {
                http_response_code(200);
                echo json_encode(["success" => true, "mensaje" => "Pedido actualizado correctamente."]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al actualizar el pedido."]);
            }
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Pedido no encontrado."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos insuficientes para actualizar el pedido."]);
    }
} elseif ($method === 'DELETE') {
    // Eliminar un pedido
    if (isset($input['id_pedido'])) {
        if ($repoPedidos->eliminar($input['id_pedido'])) {
            http_response_code(200);
            echo json_encode(["success" => true, "mensaje" => "Pedido eliminado correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar el pedido."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID de pedido no proporcionado."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no soportado."]);
}
?>
