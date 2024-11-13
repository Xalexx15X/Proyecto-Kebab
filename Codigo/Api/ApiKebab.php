<?php
header("Content-Type: application/json");

$con = Conexion::getConection(); // Obtén la conexión PDO
$repoKebab = new RepoKebab($con);

// Obtenemos el método HTTP de la petición
$method = $_SERVER['REQUEST_METHOD'];

$input = json_decode(file_get_contents('php://input'), true);

if ($method == 'GET') {
    if (isset($_GET['id_kebab'])) {
        $kebab = $repoKebab->findById($_GET['id_kebab']);
        if ($kebab) {
            http_response_code(200);
            echo json_encode($kebab);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "kebab no encontrado.", "/", "No se ha pasado el id del kebab"]);
        }
    } else {
        $kebabs = $repoKebab->mostrarTodos();
        http_response_code(200);
        echo json_encode($kebabs);
    }
} elseif ($method == 'POST') {
    // Crear un nuevo kebab
    if (isset($input['nombre'], $input['foto'], $input['precio_min'], $input['descripcion'], $input['ingredientes'])) {
        $kebab = new Kebab(
            null,
            $input['nombre'],
            $input['foto'],
            $input['precio_min'],
            $input['descripcion'],
            $input['ingredientes'] 
        );
        $result = $repoKebab->crear($kebab);
        if ($result){
            http_response_code(201);
            echo json_encode(["message" => "Kebab creado exitosamente"]);
        } else {
            http_response_code(500); 
            echo json_encode(["error" => "Error al crear el kebab"]);
        }
    } else {
        http_response_code(400); 
        echo json_encode(["error" => "Datos incompletos para crear el kebab"]);
    }
    
} elseif ($method == 'PUT') {
    // Actualizar un ingrediente existente
    if (isset($input['nombre'], $input['foto'], $input['precio_min'], $input['descripcion'], $input['ingredientes'])) {
        $kebab = new Kebab(
            $input['id_kebab'],
            $input['nombre'],
            $input['foto'],
            $input['precio_min'],
            $input['descripcion'],
            $input['ingredientes']
        );

        $result = $repoKebab->modificar($kebab);
        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "mensaje" => "Kebab actualizado correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar el Kebab."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos insuficientes para actualizar el Kebab."]);
    }

} elseif ($method == 'DELETE') {
    // Eliminar un ingrediente por ID
    if (isset($input['id_kebab'])) {
        $id_kebab = $input['id_kebab'];
        $result = $repoKebab->eliminarKebab($id_kebab);
        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "mensaje" => "kebab eliminado correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar el kebab."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID del kebab no proporcionado."]);
    }
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(["error" => "Método no soportado."]);
}
?>
