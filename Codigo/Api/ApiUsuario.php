<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoUsuario = new RepoUsuario($con);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'GET') {
    if (isset($input['id'])) {
        $id = $input['id'];
        $usuario = $repoUsuario->findById($id);
        
        if ($usuario) {
            http_response_code(200);
            echo json_encode($usuario);
        } else {
            http_response_code(404); 
            echo json_encode(["error" => "Usuario no encontrado."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID de usuario no proporcionado."]);
    }
} elseif ($method === 'POST') {
    if (isset($input['nombre'], $input['contrasena'], $input['carrito'], $input['monedero'], $input['foto'], $input['telefono'], $input['ubicacion'], $input['correo'], $input['tipo'])) {
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
            $input['alergenos'] ?? []
        );
        $result = $repoUsuario->crear($usuario);
        if ($result) {
            http_response_code(201); 
            echo json_encode(["success" => true, "mensage" => "Usuario creado correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear el usuario."]);
        }
    } else {
        http_response_code(400); 
        echo json_encode(["error" => "Datos insuficientes para crear el usuario."]);
    }
} elseif ($method === 'PUT') {
    if (isset($input['id'], $input['nombre'], $input['contrasena'], $input['carrito'], $input['monedero'], $input['foto'], $input['telefono'], $input['ubicacion'], $input['correo'], $input['tipo'])) {
        $alergenos = isset($input['alergenos']) ? $input['alergenos'] : [];
        
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
            $alergenos
        );
        
        $result = $repoUsuario->modificar($usuario);
        if ($result) {
            http_response_code(200); 
            echo json_encode([["success" => true], mensage => "Usuario actualizado correctamente."]);
        } else {
            http_response_code(500); 
            echo json_encode(["error" => "Error al actualizar el usuario."]);
        }
    } else {
        http_response_code(400);
        var_dump($input);
        echo json_encode(["error" => "Datos insuficientes para actualizar el usuario."]);
    }
} elseif ($method === 'DELETE') {
    if (isset($input['id']) && !empty($input['id'])) {
        $id = $input['id'];
        $result = $repoUsuario->eliminarUsuario($id);
        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "mensage" => "Usuario eliminado correctamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "No se pudo eliminar el usuario."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Falta el ID para eliminar el usuario."]);
    }
} else {
    http_response_code(405); 
    echo json_encode(["error" => "Método no soportado."]);
}
?>
