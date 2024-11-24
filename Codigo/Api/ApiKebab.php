<?php
    /*
        Ruta para gestionar los kebabs

        Métodos:
            GET: Obtener un kebab por ID
            POST: Crear un nuevo kebab
            PUT: Modificar un kebab
            DELETE: Eliminar un kebab
            
        @param $input: JSON con los datos del kebab
        @param $result: JSON con los datos del kebab
        @param $kebab: Objeto con los datos del kebab
        @param $kebabCreado: Objeto con los datos del kebab creado
        
        TODO: Implementar métodos para gestionar los kebabs (crear, modificar, eliminar)
        * Crear: Crear un nuevo kebab
        * Modificar: Modificar un kebab
        * Eliminar: Eliminar un kebab
        * Obtener un kebab por ID: Obtener un kebab por ID
        * Obtener todos los kebabs: Obtener todos los kebabs    
    */  


header("Content-Type: application/json");

$con = Conexion::getConection(); // Obtén la conexión PDO
$repoKebab = new RepoKebab($con);

// Obtenemos el método HTTP de la petición
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

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
        if (isset($_GET['id_kebab'])) {
            // Obtener un kebab por ID
            $kebab = $repoKebab->findById($_GET['id_kebab']);
            if ($kebab) {
                http_response_code(200);
                echo json_encode($kebab);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Kebab no encontrado."]);
            }
        } else {
            // Obtener todos los kebabs
            $kebabs = $repoKebab->mostrarTodos();
            http_response_code(200);
            echo json_encode($kebabs);
        }
        break;

    case 'POST':
        // Crear un nuevo kebab
        if (isset($input['nombre'], $input['foto'], $input['precio_min'], $input['descripcion'], $input['ingredientes'])){
            $kebab = new Kebab(
                null,
                $input['nombre'],
                $input['foto'],
                $input['precio_min'],
                $input['descripcion'],
                $input['ingredientes']
            );

            $result = $repoKebab->crear($kebab);
            if ($result) {
                http_response_code(201);
                echo json_encode(["message" => "Kebab creado exitosamente."]);
            } else {
                http_response_code(500); 
                echo json_encode(["error" => "Error al crear el kebab."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Datos incompletos para crear el kebab."]);
        }
        break;

    case 'PUT':
        // Actualizar un kebab existente
        if (isset($input['id_kebab'], $input['nombre'], $input['foto'], $input['precio_min'], $input['descripcion'], $input['ingredientes'])) {

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
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Kebab actualizado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al actualizar el kebab."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos insuficientes para actualizar el kebab."]);
        }
        break;

    case 'DELETE':
        // Eliminar un kebab por ID
        if (isset($input['id_kebab']) && !empty($input['id_kebab'])) {
            $id_kebab = $input['id_kebab'];
            $result = $repoKebab->eliminarKebab($id_kebab);
            if ($result) {
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Kebab eliminado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al eliminar el kebab."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "ID del kebab no proporcionado."]);
        }
        break;

    default:
        http_response_code(405); // Método no permitido
        echo json_encode(["error" => "Método no soportado."]);
        break;
}
?>
