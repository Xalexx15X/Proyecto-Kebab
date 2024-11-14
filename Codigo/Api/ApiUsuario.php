<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoUsuario = new RepoUsuario($con);

$method = $_SERVER['REQUEST_METHOD'];

// Verificar si el JSON recibido es válido
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "JSON malformado."]);
    exit;
}

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Obtener usuario por ID
            $id = $_GET['id'];
            $usuario = $repoUsuario->findById($id);
            if ($usuario) {
                http_response_code(200);
                echo json_encode($usuario);
            } else {
                http_response_code(404); 
                echo json_encode(["error" => "Usuario no encontrado."]);
            }
        } else {
            // Obtener todos los usuarios
            $usuarios = $repoUsuario->mostrarTodos();
            http_response_code(200); // OK
            echo json_encode($usuarios);
        }
        break;

    case 'POST':
        if (isset($input['nombre'], $input['contrasena'], $input['carrito'], $input['monedero'], $input['foto'], $input['telefono'], $input['ubicacion'], $input['correo'], $input['tipo'], $input['alergenos']) &&
            !empty($input['nombre']) && !empty($input['contrasena']) && !empty($input['carrito']) && !empty($input['monedero']) && !empty($input['foto']) && !empty($input['telefono']) && !empty($input['ubicacion']) && !empty($input['correo']) && !empty($input['tipo']) && !empty($input['alergenos'])) {

            // Crear un nuevo usuario
            $usuario = new Usuario(
                null, 
                $input['nombre'],
                $input['contrasena'],
                $input['carrito'],
                $input['monedero'],
                $input['foto'],
                $input['telefono'],
                $input['ubicacion'],
                $input['correo'],
                $input['tipo'],
                $input['alergenos']
            );

            $result = $repoUsuario->crear($usuario);
            if ($result) {
                http_response_code(201); // Created
                echo json_encode(["success" => true, "mensaje" => "Usuario creado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al crear el usuario."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos insuficientes para crear el usuario."]);
        }
        break;

    case 'PUT':
        if (isset($input['id'], $input['nombre'], $input['contrasena'], $input['carrito'], $input['monedero'], $input['foto'], $input['telefono'], $input['ubicacion'], $input['correo'], $input['tipo'], $input['alergenos']) &&
            !empty($input['id']) && !empty($input['nombre']) && !empty($input['contrasena']) && !empty($input['carrito']) && !empty($input['monedero']) && !empty($input['foto']) && !empty($input['telefono']) && !empty($input['ubicacion']) && !empty($input['correo']) && !empty($input['tipo']) && !empty($input['alergenos'])) {

            // Obtener el usuario existente
            $usuario = new Usuario(
                $input['id'],
                $input['nombre'],
                $input['contrasena'],
                $input['carrito'],
                $input['monedero'],
                $input['foto'],
                $input['telefono'],
                $input['ubicacion'],
                $input['correo'],
                $input['tipo'],
                $input['alergenos']
            );

            $result = $repoUsuario->modificar($usuario);
            if ($result) {
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Usuario actualizado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al actualizar el usuario."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos insuficientes para actualizar el usuario."]);
        }
        break;

    case 'DELETE':
        if (isset($input['id']) && !empty($input['id'])) {
            $id = $input['id'];
            $result = $repoUsuario->eliminarUsuario($id);
            if ($result) {
                http_response_code(200); // OK
                echo json_encode(["success" => true, "mensaje" => "Usuario eliminado correctamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "No se pudo eliminar el usuario."]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Falta el ID para eliminar el usuario."]);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Método no soportado."]);
        break;
}
?>
