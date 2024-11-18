<?php

header("Content-Type: application/json");

$con = Conexion::getConection();
$repoAlergenos = new RepoAlergenos($con);

$method = $_SERVER['REQUEST_METHOD'];
// Decodificar el cuerpo de la solicitud solo si no es un GET
if ($method != 'GET') {
    $input = json_decode(file_get_contents("php://input"), true);

    // Verificar si el JSON recibido es válido
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "JSON malformado."]);
        exit;
    }
}
switch ($method) {
    case 'GET':
        if (isset($_GET['id_Alergeno'])) {
            $alergeno = $repoAlergenos->findById($_GET['id_Alergeno']);
            if ($alergeno) {
                http_response_code(200);
                echo json_encode($alergeno);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Alergeno no encontrado con el ID especificado."]);
            }
        } else {
            $alergenos = $repoAlergenos->mostrarTodos();
            http_response_code(200);
            echo json_encode($alergenos);
        }
        break;

    case 'POST':
        // Crear un nuevo alérgeno
        if (isset($input['nombre'], $input['foto'], $input['ingredientes'], $input['usuarios'])) {
            $alergeno = new Alergenos(
                null, 
                $input['nombre'], 
                $input['foto'], 
                $input['ingredientes'], 
                $input['usuarios']
            );

            $result = $repoAlergenos->crear($alergeno);
            if ($result) {
                http_response_code(201); // Created
                echo json_encode(["success" => true, "mensaje" => "Alergeno creado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al crear el alérgeno."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos insuficientes para crear el alérgeno."]);
        }
        break;

    case 'PUT':
        // Modificar un alérgeno
        if (isset($input['id_alergenos'], $input['nombre'], $input['foto'], $input['ingredientes'], $input['usuarios'])) {
            $alergeno = new Alergenos(
                $input['id_alergenos'], 
                $input['nombre'], 
                $input['foto'], 
                $input['ingredientes'], 
                $input['usuarios']
            );

            $result = $repoAlergenos->modificar($alergeno);
            if ($result) {
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Alergeno actualizado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al actualizar el alérgeno."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos insuficientes para actualizar el alérgeno."]);
        }
        break;

    case 'DELETE':
        // Eliminar un alérgeno por ID
        if (isset($input['id_alergenos'])) {
            $id_alergenos = $input['id_alergenos'];
            $result = $repoAlergenos->eliminar($id_alergenos);
            if ($result) {
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Alergeno eliminado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al eliminar el alérgeno."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "ID de alérgeno no proporcionado."]);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Método no soportado."]);
        break;
}
?>
