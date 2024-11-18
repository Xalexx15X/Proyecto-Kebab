<?php

header("Content-Type: application/json");

$con = Conexion::getConection();
$repoUsuario = new RepoUsuario($con);

$method = $_SERVER['REQUEST_METHOD'];

// Verificar si el JSON recibido es válido
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
        if (isset($_GET['id_usuario'])) {
            // Obtener un usuario por ID
            $usuario = $repoUsuario->findById($_GET['id_usuario']);
            
            if ($usuario) {
                http_response_code(200);
                echo json_encode($usuario);  // Enviamos el usuario encontrado
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Usuario no encontrado."]);
            }
        } elseif (isset($_GET['nombre']) && isset($_GET['contrasena'])) {
            // Autenticar al usuario con nombre y contraseña (en los parámetros de la URL)
            $nombre = $_GET['nombre'];
            $contrasena = $_GET['contrasena'];

            // Buscar al usuario por nombre y contraseña
            $usuario = $repoUsuario->findByNombreYContrasena($nombre, $contrasena);

            if ($usuario) {
                http_response_code(200); // OK
                echo json_encode($usuario); // Devolver el usuario encontrado
            } else {
                http_response_code(401); // Unauthorized
                echo json_encode(["error" => "Credenciales incorrectas."]);
            }
        } else {
            // Si no se pasa un nombre y contraseña, o un id_usuario, obtener todos los usuarios
            $usuarios = $repoUsuario->mostrarTodos();
            
            http_response_code(200);
            echo json_encode($usuarios);  // Enviamos todos los usuarios
        }
        break;
    case 'POST':
        if (isset($input['nombre'], $input['contrasena'], $input['carrito'], $input['monedero'], $input['foto'], $input['telefono'], $input['ubicacion'], $input['correo'], $input['tipo'])){

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
                $input['tipo'] ?? "Cliente"
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
        if (isset($input['id'], $input['nombre'], $input['contrasena'], $input['carrito'], $input['monedero'], $input['foto'], $input['telefono'], $input['ubicacion'], $input['correo'], $input['tipo'])) {

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
                $input['tipo']
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
        if (isset($input['id'])) {
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
