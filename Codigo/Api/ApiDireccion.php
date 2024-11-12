<?php

header("Content-Type: application/json");

$con = Conexion::getConection();
$repoDireccion = new RepoDireccion($con);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'GET') {
    if (isset($input['usuario_id_usuario'])) {
        $usuarioId = $input['usuario_id_usuario'];
        $direcciones = $repoDireccion->findByUsuarioId($usuarioId);
        if ($direcciones) {
            http_response_code(200);  // OK
            echo json_encode($direcciones);
        } else {
            http_response_code(404);  // Not Found
            echo json_encode(["error" => "No se encontraron direcciones para el usuario especificado."]);
        }
    } else {
        http_response_code(400);  // Bad Request
        echo json_encode(["error" => "ID de usuario no proporcionado."]);
    }
} elseif ($method === 'POST') {
    if (isset($input['direccion'], $input['estado'], $input['usuario_id_usuario'])) {
        $direccion = new Direccion(null, $input['direccion'], $input['estado'], $input['usuario_id_usuario']);
        $result = $repoDireccion->crear($direccion);
        if ($result) {
            http_response_code(201);  // Created
            echo json_encode(["success" => true, "mensaje" => "Dirección creada correctamente."]);
        } else {
            http_response_code(500);  // Internal Server Error
            echo json_encode(["error" => "Error al crear la dirección."]);
        }
    } else {
        http_response_code(400);  // Bad Request
        echo json_encode(["error" => "Datos insuficientes para crear la dirección."]);
    }
} elseif ($method === 'PUT') {
    if (isset($input['id_direccion'], $input['direccion'], $input['estado'])) {
        $direccion = new Direccion($input['id_direccion'], $input['direccion'], $input['estado'], null);
        $result = $repoDireccion->modificar($direccion);
        if ($result) {
            http_response_code(200);  // OK
            echo json_encode(["success" => true, "mensaje" => "Dirección actualizada correctamente."]);
        } else {
            http_response_code(500);  // Internal Server Error
            echo json_encode(["error" => "Error al actualizar la dirección."]);
        }
    } else {
        http_response_code(400);  // Bad Request
        echo json_encode(["error" => "Datos insuficientes para actualizar la dirección."]);
    }
} elseif ($method === 'DELETE') {
    if (isset($input['id_direccion'])) {
        $result = $repoDireccion->eliminar($input['id_direccion']);
        if ($result) {
            http_response_code(200);  // OK
            echo json_encode(["success" => true, "mensaje" => "Dirección eliminada correctamente."]);
        } else {
            http_response_code(500);  // Internal Server Error
            echo json_encode(["error" => "No se pudo eliminar la dirección."]);
        }
    } else {
        http_response_code(400);  // Bad Request
        echo json_encode(["error" => "Falta el ID para eliminar la dirección."]);
    }
} else {
    http_response_code(405);  // Method Not Allowed
    echo json_encode(["error" => "Método no soportado."]);
}
?>
