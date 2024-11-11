<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoUsuario = new RepoUsuario($con);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'GET') {
    // Obtener el ID de $input (JSON) en lugar de $_GET
    if (isset($input['id'])) {
        $id = $input['id'];
        $usuario = $repoUsuario->findById($id);
        
        if ($usuario) {
            echo json_encode($usuario);
        } else {
            echo json_encode(["error" => "Usuario no encontrado."]);
        }
    } else {
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
        echo json_encode(["success" => $result]);
    } else {
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
        echo json_encode(["success" => $result]);
    } else {
        var_dump($input);
        echo json_encode(["error" => "Datos insuficientes para actualizar el usuario."]);
    }
} elseif ($method === 'DELETE') {
    if (isset($input['id']) && !empty($input['id'])) {
        $id = $input['id'];
        $result = $repoUsuario->eliminarUsuario($id);
        if ($result) {
            echo json_encode(["success" => true, "message" => "Usuario eliminado correctamente."]);
        } else {
            echo json_encode(["error" => "No se pudo eliminar el usuario."]);
        }
    } else {
        echo json_encode(["error" => "Falta el ID para eliminar el usuario."]);
    }
} else {
    echo json_encode(["error" => "Método no soportado."]);
}
?>
