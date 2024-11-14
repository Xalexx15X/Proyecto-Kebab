<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoIngredientes = new RepoIngredientes($con);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

// Verificar si el JSON recibido es válido
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "JSON malformado."]);
    exit;
}

switch ($method) {
    case 'GET':
        if (isset($_GET['id_ingrediente'])) {
            // Obtener un ingrediente por ID
            $ingrediente = $repoIngredientes->findById($_GET['id_ingrediente']);
            if ($ingrediente) {
                http_response_code(200);
                echo json_encode($ingrediente);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Ingrediente no encontrado."]);
            }
        } else {
            // Obtener todos los ingredientes
            $ingredientes = $repoIngredientes->mostrarTodo();
            http_response_code(200);
            echo json_encode($ingredientes);
        }
        break;

    case 'POST':
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
                http_response_code(201); // Created
                echo json_encode(["success" => true, "mensaje" => "Ingrediente creado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al crear el ingrediente."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos insuficientes para crear el ingrediente."]);
        }
        break;

    case 'PUT':
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
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Ingrediente actualizado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al actualizar el ingrediente."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos insuficientes para actualizar el ingrediente."]);
        }
        break;

    case 'DELETE':
        // Eliminar un ingrediente por ID
        if (isset($input['id_ingrediente'])) {
            $id_ingrediente = $input['id_ingrediente'];
            $result = $repoIngredientes->eliminar($id_ingrediente);
            if ($result) {
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Ingrediente eliminado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al eliminar el ingrediente."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "ID de ingrediente no proporcionado."]);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Método no soportado."]);
        break;
}
?>
