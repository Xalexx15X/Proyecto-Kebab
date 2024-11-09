<?php
// Suponemos que $con es la conexión PDO a la base de datos
$repoDireccion = new RepoDireccion($con);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Crear una nueva dirección
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifica que los datos necesarios estén presentes
    if (isset($data['direccion']) && isset($data['estado'])) {
        $direccion = new Direccion(
            null, // ID se generará automáticamente
            $data['direccion'],
            $data['estado']
        );

        // Si hay usuarios asociados, los agregamos a la dirección
        if (isset($data['usuarios']) && is_array($data['usuarios'])) {
            foreach ($data['usuarios'] as $usuarioData) {
                $usuario = new Usuario(
                    $usuarioData['id_usuario'], 
                    $usuarioData['nombre'],
                    $usuarioData['correo'] // Asegúrate de que estos campos sean correctos
                );
                $direccion->addUsuario($usuario);
            }
        }

        $result = $repoDireccion->crear($direccion);

        if ($result) {
            http_response_code(201);
            header('HTTP/1.1 201 Created');
            echo json_encode(['success' => true, 'message' => 'Dirección creada correctamente']);
        } else {
            http_response_code(500);
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error al crear la dirección']);
        }
    } else {
        http_response_code(400);
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Faltan parámetros: direccion y estado']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Obtener todas las direcciones
    $direcciones = $repoDireccion->mostrarTodos();
    if ($direcciones) {
        http_response_code(200);
        header('HTTP/1.1 200 OK');
        echo json_encode($direcciones);
    } else {
        http_response_code(404);
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'No se encontraron direcciones']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Borrar una dirección por ID
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id_direccion'] ?? null;
    if ($id) {
        $result = $repoDireccion->eliminar($id);
        if ($result) {
            http_response_code(200);
            header('HTTP/1.1 200 OK');
            echo json_encode(['success' => true, 'message' => 'Dirección eliminada correctamente']);
        } else {
            http_response_code(404);
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Dirección no encontrada']);
        }
    } else {
        http_response_code(400);
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'ID de dirección no especificado']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Modificar una dirección existente
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['id_direccion']) && isset($data['direccion']) && isset($data['estado'])) {
        $direccion = new Direccion(
            $data['id_direccion'],
            $data['direccion'],
            $data['estado']
        );

        // Si hay usuarios asociados, los agregamos a la dirección
        if (isset($data['usuarios']) && is_array($data['usuarios'])) {
            foreach ($data['usuarios'] as $usuarioData) {
                $usuario = new Usuario(
                    $usuarioData['id_usuario'], 
                    $usuarioData['nombre'],
                    $usuarioData['correo'] // Asegúrate de que estos campos sean correctos
                );
                $direccion->addUsuario($usuario);
            }
        }

        $result = $repoDireccion->modificar($direccion);

        if ($result) {
            http_response_code(200);
            header('HTTP/1.1 200 OK');
            echo json_encode(['success' => true, 'message' => 'Dirección modificada correctamente']);
        } else {
            http_response_code(500);
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error al modificar la dirección']);
        }
    } else {
        http_response_code(400);
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Faltan parámetros: id_direccion, direccion y estado']);
    }
}

