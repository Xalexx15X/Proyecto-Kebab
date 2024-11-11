<?php
header("Content-Type: application/json");

$con = Conexion::getConection();
$repoUsuario = new RepoUsuario($con);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

var_dump($method);   // Depuración: muestra el método HTTP
var_dump($input);    // Depuración: muestra el JSON recibido

// Procesa las solicitudes GET
// Procesa las solicitudes GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifica si el parámetro 'id_usuario' está en los query params
    if (isset($_GET['id_usuario'])) {
        $id = $_GET['id_usuario']; // Obtener el ID del parámetro en la URL
        $usuario = $repoUsuario->findById($id);
        
        if ($usuario) {
            echo json_encode($usuario);  // Devuelve el usuario como JSON
        } else {
            echo json_encode(["error" => "Usuario no encontrado."]);
        }
    } else {
        echo json_encode(["error" => "ID de usuario no proporcionado."]);
    }
// Procesa las solicitudes POST para crear un nuevo usuario
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

// Procesa las solicitudes PUT para actualizar un usuario existente
} elseif ($method === 'PUT') {
    // Leer el JSON del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Asegúrate de que todos los campos necesarios están presentes
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
        // Muestra el contenido de la solicitud para verificar qué datos están llegando
        var_dump($input);
        echo json_encode(["error" => "Datos insuficientes para actualizar el usuario."]);
    }
// Procesa las solicitudes DELETE para eliminar un usuario
} elseif ($method === 'DELETE') {
    if (isset($input['id']) && !empty($input['id'])) {
        $id = $input['id'];  // ID del usuario a eliminar
        $result = $repoUsuario->eliminarUsuario($id);  // Usar el método eliminarUsuario
        if ($result) {
            echo json_encode(["success" => true, "message" => "Usuario eliminado correctamente."]);
        } else {
            echo json_encode(["error" => "No se pudo eliminar el usuario."]);
        }
    } else {
        echo json_encode(["error" => "Falta el ID para eliminar el usuario."]);
    }

// Método no soportado
} else {
    echo json_encode(["error" => "Método no soportado."]);
}
?>