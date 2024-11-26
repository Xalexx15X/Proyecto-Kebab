<?php
    /*
        Ruta para gestionar líneas de pedido
        
        Métodos:
            GET: Obtener una línea de pedido por ID
            POST: Crear una nueva línea de pedido
            PUT: Modificar una línea de pedido
            DELETE: Eliminar una línea de pedido
            
        @param $input: JSON con los datos de la línea de pedido
        @param $result: JSON con los datos de la línea de pedido
        @param $lineaPedido: Objeto con los datos de la línea de pedido
        @param $lineaPedidoCreado: Objeto con los datos de la línea de pedido creado

        TODO: Implementar métodos para gestionar las líneas de pedido (crear, modificar, eliminar)
        * Crear: Crear una nueva línea de pedido
        * Modificar: Modificar una línea de pedido
        * Eliminar: Eliminar una línea de pedido        
        * Obtener una línea de pedido por ID: Obtener una línea de pedido por ID
        * Obtener todas las líneas de pedido: Obtener todas las líneas de pedido
    */

header("Content-Type: application/json");

$con = Conexion::getConection();
$repoLineaPedido = new RepoLinea_Pedido($con);

$method = $_SERVER['REQUEST_METHOD'];  // Obtiene el tipo de petición HTTP

// Verificar si el JSON recibido es válido
$input = json_decode(file_get_contents("php://input"), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "JSON malformado."]);
    exit;
}

switch ($method) {
    case 'GET':
        if (isset($_GET['id_linea_pedido'])) {
            // Obtener una línea de pedido por ID
            $lineaPedido = $repoLineaPedido->findById($_GET['id_linea_pedido']);
            if ($lineaPedido) {
                http_response_code(200);
                echo json_encode($lineaPedido);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Línea de pedido no encontrada."]);
            }
        } else {
            // Obtener todas las líneas de pedido
            $lineasPedidos = $repoLineaPedido->mostrarTodos();
            http_response_code(200);
            echo json_encode($lineasPedidos);
        }
        break;

    case 'POST':
        // Crear una nueva línea de pedido
        if (isset($input['cantidad'], $input['precio'], $input['linea_pedidos'], $input['id_pedidos']) &&
            !empty($input['cantidad']) && !empty($input['precio']) && !empty($input['linea_pedidos']) && !empty($input['id_pedidos'])) {

            $lineaPedido = new Linea_Pedido(
                null,  // El ID será autoincrementado por la base de datos
                $input['cantidad'],
                $input['precio'],
                $input['linea_pedidos'],
                $input['id_pedidos']
            );

            if ($repoLineaPedido->crear($lineaPedido)) {
                http_response_code(201); // Created
                echo json_encode(["message" => "Línea de pedido creada correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "No se pudo crear la línea de pedido."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos incompletos o inválidos."]);
        }
        break;

    case 'PUT':
        // Actualizar una línea de pedido existente
        if (isset($input['id_linea_pedido'], $input['cantidad'], $input['precio'], $input['linea_pedidos'], $input['id_pedidos'])) {
            $lineaPedido = $repoLineaPedido->findById($input['id_linea_pedido']);
            
            if ($lineaPedido) {
                $lineaPedido->setCantidad($input['cantidad']);
                $lineaPedido->setPrecio($input['precio']);
                $lineaPedido->setLineaPedidos($input['linea_pedidos']);
                $lineaPedido->setIdPedidos($input['id_pedidos']);
                
                if ($repoLineaPedido->modificar($lineaPedido)) {
                    http_response_code(200); // OK
                    echo json_encode(["success" => true, "mensaje" => "Línea de pedido actualizada correctamente."]);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(["error" => "Error al actualizar la línea de pedido."]);
                }
            } else {
                http_response_code(404); // Not Found
                echo json_encode(["error" => "Línea de pedido no encontrada."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos insuficientes o inválidos para actualizar la línea de pedido."]);
        }
        break;

    case 'DELETE':
        // Eliminar una línea de pedido por ID
        if (isset($input['id_linea_pedido']) && !empty($input['id_linea_pedido'])) {
            if ($repoLineaPedido->eliminar($input['id_linea_pedido'])) {
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Línea de pedido eliminada correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al eliminar la línea de pedido."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "ID de línea de pedido no proporcionado."]);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Método no soportado."]);
        break;
}


