<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoIngredientes = new RepoIngredientes($con);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'GET') {
    // Obtener todos los ingredientes o uno específico si se proporciona un ID
    if (isset($_GET['id_ingrediente'])) {
        $ingrediente = $repoIngredientes->findById($_GET['id_ingrediente']);
        if ($ingrediente) {
            http_response_code(200);
            echo json_encode($ingrediente);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Ingrediente no encontrado.", "/", "No se ha pasado el id del ingrediente"]);
        }
    } else {
        $ingredientes = $repoIngredientes->mostrarTodo();
        http_response_code(200);
        echo json_encode($ingredientes);
    }
} elseif ($method === 'POST') {
    // Crear un nuevo ingrediente
    if (isset($input['nombre'], $input['foto'], $input['precio'], $input['tipo'], $input['alergenos'])) {
        $ingrediente = new Ingredientes(
            null, 
            $input['nombre'],
            $input['foto'],
            $input['precio'],
            $input['tipo'],
            $input['alergenos'] 
        );

        $result = $repoIngredientes->crear($ingrediente);
        if ($result) {
            http_response_code(201); 
            echo json_encode(["success" => true, "mensaje" => "Ingrediente creado correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear el ingrediente."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos insuficientes para crear el ingrediente."]);
    }
} elseif ($method === 'PUT') {
    // Actualizar un ingrediente existente
    if (isset($input['id_ingrediente'], $input['nombre'], $input['foto'], $input['precio'], $input['tipo'], $input['alergenos'])) {
        $ingrediente = new Ingredientes(
            $input['id_ingrediente'],
            $input['nombre'],
            $input['foto'],
            $input['precio'],
            $input['tipo'],
            $input['alergenos'] 
        );

        $result = $repoIngredientes->modificar($ingrediente);
        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "mensaje" => "Ingrediente actualizado correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar el ingrediente."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos insuficientes para actualizar el ingrediente."]);
    }
} elseif ($method === 'DELETE') {
    // Eliminar un ingrediente por ID
    if (isset($input['id_ingrediente'])) {
        $id_ingrediente = $input['id_ingrediente'];
        $result = $repoIngredientes->eliminar($id_ingrediente);
        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "mensaje" => "Ingrediente eliminado correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar el ingrediente."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID de ingrediente no proporcionado."]);
    }
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(["error" => "Método no soportado."]);
}
?>
