<?php
// Suponemos que $con es la conexión PDO a la base de datos
$con = Conexion::getConection(); // Obtén la conexión a la base de datos
$repoUsuario = new RepoUsuario($con);


header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Crear un nuevo usuario
    $data = json_decode(file_get_contents('php://input'), true);
    
    $usuario = new Usuario(
        null, // ID se generará automáticamente
        $data['nombre'],
        $data['contrasena'],
        json_encode($data['carrito']),
        $data['monedero'],
        $data['foto'],
        $data['correo'],
        $data['telefono'],
        $data['ubicacion'],
        $data['alergenos'] ?? []
    );
    $result = $repoUsuario->crear($usuario);
    
    if ($result) {
        http_response_code(201);
        header('HTTP/1.1 201 Created');
        echo json_encode(['success' => true, 'message' => 'Usuario creado correctamente']);
    } else {
        http_response_code(500);
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Error al crear el usuario']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Obtener todos los usuarios
    $usuarios = $repoUsuario->mostrarTodos();
    if ($usuarios) {
        http_response_code(200);
        header('HTTP/1.1 200 OK');
        echo json_encode($usuarios);
    } else {
        http_response_code(404);
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'No se encontraron usuarios']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Borrar un usuario por ID
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id_usuario'] ?? null;
    if ($id) {
        $result = $repoUsuario->borrar($id);
        if ($result) {
            http_response_code(200);
            header('HTTP/1.1 200 OK');
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } else {
            http_response_code(404);
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Usuario no encontrado']);
        }
    } else {
        http_response_code(400);
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'ID de usuario no especificado']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Modificar un usuario existente
    $data = json_decode(file_get_contents('php://input'), true);
    $usuario = new Usuario(
        $data['id_usuario'],
        $data['nombre'],
        $data['contrasena'],
        json_encode($data['carrito']),
        $data['monedero'],
        $data['foto'],
        $data['correo'],
        $data['telefono'],
        $data['ubicacion'],
        $data['alergenos'] ?? []
    );
    $result = $repoUsuario->modificar($usuario);
    if ($result) {
        http_response_code(200);
        header('HTTP/1.1 200 OK');
        echo json_encode(['success' => true, 'message' => 'Usuario modificado correctamente']);
    } else {
        http_response_code(500);
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Error al modificar el usuario']);
    }
} 
