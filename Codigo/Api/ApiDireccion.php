<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoDireccion = new RepoDireccion($con);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'GET') {
    // Obtener las direcciones de un usuario por ID
    if (isset($input['usuario_id_usuario'])) {
        $usuarioId = $input['usuario_id_usuario'];
        $direcciones = $repoDireccion->findByUsuarioId($usuarioId);

        if ($direcciones) {
            http_response_code(200);
            echo json_encode($direcciones);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "No se encontraron direcciones para el usuario especificado."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID de usuario no proporcionado."]);
    }
} elseif ($method === 'POST') {
    // Crear una nueva dirección
    if (isset($input['direccion'], $input['estado'], $input['usuario_id_usuario'])) {
        $direccion = new Direccion(
            null, // El ID se autoincrementa
            $input['direccion'],
            $input['estado'],
            $input['usuario_id_usuario']
        );

        $result = $repoDireccion->crear($direccion);
        if ($result) {
            http_response_code(201); // Código para recurso creado
            echo json_encode(["success" => true, "message" => "Dirección creada correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear la dirección."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos insuficientes para crear la dirección."]);
    }
} elseif ($method === 'PUT') {
    // Actualizar una dirección existente
    if (isset($input['id_direccion'], $input['direccion'], $input['estado'])) {
        $direccion = new Direccion(
            $input['id_direccion'],
            $input['direccion'],
            $input['estado']
        );

        $result = $repoDireccion->modificar($direccion);
        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Dirección actualizada correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar la dirección."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos insuficientes para actualizar la dirección."]);
    }
} elseif ($method === 'DELETE') {
    // Eliminar una dirección por ID
    if (isset($input['id_direccion']) && !empty($input['id_direccion'])) {
        $id = $input['id_direccion'];
        $result = $repoDireccion->eliminar($id);
        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Dirección eliminada correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "No se pudo eliminar la dirección."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Falta el ID para eliminar la dirección."]);
    }
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(["error" => "Método no soportado."]);
}
?>
