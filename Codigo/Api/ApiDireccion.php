<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoDireccion = new RepoDireccion($con);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Verifica si el JSON recibido es válido
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "JSON malformado."]);
    exit;
}

switch ($method) {
    case 'GET':
        // Mostrar todas las direcciones
        $direcciones = $repoDireccion->mostrarTodo();
        
        if ($direcciones) {
            http_response_code(200);
            echo json_encode($direcciones); // Enviar todas las direcciones como JSON
        } else {
            http_response_code(404);
            echo json_encode(["error" => "No se encontraron direcciones."]);
        }
        break;

    case 'POST':
        // Si se pasa id_usuario por la URL y el cuerpo está vacío, mostrar direcciones por usuario
        if (isset($_GET['id_usuario']) && empty($input)) {
            $id_usuario = $_GET['id_usuario'];
            $direcciones = $repoDireccion->findByUsuarioId($id_usuario);
            
            if ($direcciones) {
                http_response_code(200);
                echo json_encode($direcciones); // Enviar las direcciones como JSON
            } else {
                http_response_code(404);
                echo json_encode(["error" => "No se encontraron direcciones para el usuario especificado."]);
            }
        } 
        // Crear una nueva dirección
        else if (isset($input['direccion'], $input['estado'], $input['id_usuario'])) {
            $direccion = new Direccion(
                null, 
                $input['direccion'],
                $input['estado'],
                $input['id_usuario']
            );

            $result = $repoDireccion->crear($direccion);
            if ($result) {
                http_response_code(201); // Created
                echo json_encode(["message" => "Dirección creada exitosamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al crear la dirección."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos incompletos o parámetros inválidos."]);
        }
        break;

    case 'PUT':
        // Actualizar una dirección existente
        if (isset($input['id_direccion'], $input['direccion'], $input['estado'], $input['id_usuario'])) {
            $direccion = new Direccion(
                $input['id_direccion'],
                $input['direccion'],
                $input['estado'],
                $input['id_usuario']
            );

            $result = $repoDireccion->modificar($direccion);
            if ($result) {
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Dirección actualizada correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al actualizar la dirección."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos insuficientes para actualizar la dirección."]);
        }
        break;

    case 'DELETE':
        // Eliminar una dirección por ID
        if (isset($input['id_direccion'])) {
            $id_direccion = $input['id_direccion'];
            $result = $repoDireccion->eliminar($id_direccion);
            if ($result) {
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Dirección eliminada correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al eliminar la dirección."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "ID de dirección no proporcionado."]);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Método no soportado."]);
        break;
}
?>
