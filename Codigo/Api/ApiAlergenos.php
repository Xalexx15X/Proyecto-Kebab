<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoAlergenos = new RepoAlergenos($con);

$method = $_SERVER['REQUEST_METHOD'];

$input = json_decode(file_get_contents("php://input"), true);

if ($method === 'GET') {
    if (isset($_GET['id_Alergeno'])) {
        $alergeno = $repoAlergenos->findById($_GET['id_Alergeno']);
        if ($alergeno) {
            http_response_code(200);
            echo json_encode($alergeno);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Alergeno no encontrado.", "/", "No se ha pasado el id de la alergeno"]);
        }
    } else {
        $alergenos = $repoAlergenos->mostrarTodos();
        http_response_code(200);
        echo json_encode($alergenos);
    }
} elseif ($method === 'POST') {
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
            http_response_code(201); 
            echo json_encode(["success" => true, "mensaje" => "Alergeno creado correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear el alérgeno."]);
        }
    } else {
        http_response_code(400); 
        echo json_encode(["error" => "Datos insuficientes para crear el alérgeno."]);
    }
} elseif ($method === 'PUT') {
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
            http_response_code(200);
            echo json_encode(["success" => true, "mensaje" => "Alergeno actualizado correctamente."]);
        } else {
            http_response_code(500); 
            echo json_encode(["error" => "Error al actualizar el alérgeno."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos insuficientes para actualizar el alérgeno."]);
    }
} elseif ($method === 'DELETE') {
    // Eliminar una direccion por ID
    if (isset($input['id_alergenos'])) {
        $id_alergenos = $input['id_alergenos'];
        $result = $repoAlergenos->eliminar($id_alergenos);
        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "mensaje" => "Alergeno eliminado correctamente."]);
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
