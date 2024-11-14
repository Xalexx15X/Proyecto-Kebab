<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoPedidos = new RepoPedido($con);

$method = $_SERVER['REQUEST_METHOD'];

// Verificar si el JSON recibido es válido
$input = json_decode(file_get_contents("php://input"), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "JSON malformado."]);
    exit;
}

switch ($method) {
    case 'GET':
        if (isset($_GET['id_pedido'])) {
            // Obtener un pedido por ID
            $pedido = $repoPedidos->findById($_GET['id_pedido']);
            if ($pedido) {
                http_response_code(200);
                echo json_encode($pedido);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Pedido no encontrado."]);
            }
        } else {
            // Obtener todos los pedidos
            $pedidos = $repoPedidos->mostrarTodos();
            http_response_code(200);
            echo json_encode($pedidos);
        }
        break;

    case 'POST':
        if (isset($input['estado'], $input['precio_total'], $input['fecha_hora'], $input['usuario_id_usuario']) &&
            !empty($input['estado']) && !empty($input['precio_total']) && !empty($input['fecha_hora']) && !empty($input['usuario_id_usuario'])) {
            
            // Crear un nuevo pedido
            $pedido = new Pedido(
                null, // El ID será autoincrementado por la base de datos
                $input['estado'],
                $input['precio_total'],
                $input['fecha_hora'],
                [], // Inicialmente, sin líneas de pedido
                $input['usuario_id_usuario'] // El ID del usuario
            );

            if ($repoPedidos->crear($pedido)) {
                http_response_code(201); // Created
                echo json_encode(["success" => true, "mensaje" => "Pedido creado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al crear el pedido."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos insuficientes o inválidos para crear el pedido."]);
        }
        break;

    case 'PUT':
        if (isset($input['id_pedido'], $input['estado'], $input['precio_total'], $input['fecha_hora'], $input['usuario_id_usuario']) &&
            !empty($input['id_pedido']) && !empty($input['estado']) && !empty($input['precio_total']) && !empty($input['fecha_hora']) && !empty($input['usuario_id_usuario'])) {
            
            // Obtener el pedido existente
            $pedido = $repoPedidos->findById($input['id_pedido']);
            
            if ($pedido) {
                // Actualizar los datos del pedido
                $pedido->setEstado($input['estado']);
                $pedido->setPrecioTotal($input['precio_total']);
                $pedido->setFechaHora($input['fecha_hora']);
                $pedido->setIdUsuario($input['usuario_id_usuario']); // Actualizamos el ID del usuario

                // Guardar los cambios en la base de datos
                if ($repoPedidos->modificar($pedido)) {
                    http_response_code(200); // OK
                    echo json_encode(["success" => true, "mensaje" => "Pedido actualizado correctamente."]);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(["error" => "Error al actualizar el pedido."]);
                }
            } else {
                http_response_code(404); // Not Found
                echo json_encode(["error" => "Pedido no encontrado."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos insuficientes o inválidos para actualizar el pedido."]);
        }
        break;

    case 'DELETE':
        if (isset($input['id_pedido']) && !empty($input['id_pedido'])) {
            if ($repoPedidos->eliminar($input['id_pedido'])) {
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Pedido eliminado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al eliminar el pedido."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "ID de pedido no proporcionado."]);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Método no soportado."]);
        break;
}
?>
