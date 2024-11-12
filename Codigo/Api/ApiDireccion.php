<?php

header("Content-Type: application/json");

$con = Conexion::getConection();
$repoDireccion = new RepoDireccion($con);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'GET') {
    if (isset($_GET['id_direccion'])) {
        $direccion = $repoDireccion->findById($_GET['id_direccion']);
        if ($direccion) {
            http_response_code(200);
            echo json_encode($direccion);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "direccion no eencontrada.", "/", "No se ha pasado el id de la direccion"]);
        }
    } else {
        $direcciones = $repoDireccion->mostrarTodos();
        http_response_code(200);
        echo json_encode($direcciones);
    }
} elseif ($method === 'POST') {
     // Crear un nuevo kebab
     if (isset($input['direccion'], $input['estado'], $input['id_usuario'])) {
        $direccion = new Direccion(
            null, 
            $input['direccion'],
            $input['estado'],
            $input['id_usuario']
        );
        $result = $repoDireccion->crear($direccion);
        if ($result){
            http_response_code(201); 
            echo json_encode(["message" => "direccion creada exitosamente"]);
        } else {
            http_response_code(500); 
            echo json_encode(["error" => "Error al crear la dirección."]);
        }
    } else {
        http_response_code(400); 
        echo json_encode(["error" => "Datos incompletos para crear la dirección."]);
    }
} elseif ($method === 'PUT') {
    // Actualizar una dirección existente
    if (isset($input['direccion'], $input['estado'], $input['id_usuario'])) {
        $direccion = new Direccion(
            $input['id_direccion'],
            $input['direccion'],
            $input['estado'],
            $input['id_usuario']
        );
        $result = $repoDireccion->modificar($direccion);
        if ($result) {
            http_response_code(200); 
            echo json_encode(["success" => true, "mensaje" => "Dirección actualizada correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar la dirección."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos insuficientes para actualizar la dirección."]);
    }
} elseif ($method === 'DELETE') {
    // Eliminar una direccion por ID
    if (isset($input['id_direccion'])) {
        $id_direccion = $input['id_direccion'];
        $result = $repoDireccion->eliminar($id_direccion);
        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "mensaje" => "Dirección eliminada correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar la dirección."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID de dirección no proporcionado."]);
    }
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(["error" => "Método no soportado."]);
}
?>
