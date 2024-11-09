<?php
// Incluye las clases necesarias
include_once 'Usuario.php';
include_once 'RepoUsuario.php';
include_once 'Conexion.php';

// Establece los encabezados para permitir solicitudes CORS y trabajar con JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Obtiene la conexión a la base de datos
$con = Conexion::getConection();

// Crea un objeto RepoUsuario
$repoUsuario = new RepoUsuario($con);

// Verifica el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Variable para manejar los datos de la respuesta
$response = [];

switch ($method) {
    case 'POST':
        // Crear un usuario
        $data = json_decode(file_get_contents("php://input"), true); // Obtiene los datos JSON del cuerpo de la solicitud

        if (
            isset($data['nombre']) && isset($data['contrasena']) && isset($data['carrito']) && isset($data['monedero'])
            && isset($data['foto']) && isset($data['correo']) && isset($data['telefono']) && isset($data['ubicacion'])
        ) {
            // Crea un nuevo objeto Usuario
            $usuario = new Usuario(
                null, // id_usuario será generado automáticamente
                $data['nombre'],
                $data['contrasena'],
                $data['carrito'],
                $data['monedero'],
                $data['foto'],
                $data['correo'],
                $data['telefono'],
                $data['ubicacion'],
                isset($data['alergenos']) ? $data['alergenos'] : [] // Opcional: Si no se proporcionan alérgenos, se usa un array vacío
            );

            // Llama al repositorio para crear el usuario
            $result = $repoUsuario->crear($usuario);

            if ($result === true) {
                $response['success'] = true;
                $response['message'] = 'Usuario creado correctamente';
            } else {
                $response['success'] = false;
                $response['message'] = 'Error al crear el usuario: ' . $result;
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Faltan campos requeridos';
        }
        break;

    case 'GET':
        // Obtener usuario por ID
        if (isset($_GET['id'])) {
            $usuario = $repoUsuario->findById($_GET['id']);

            if ($usuario) {
                $response['success'] = true;
                $response['data'] = [
                    'id_usuario' => $usuario->getIdUsuario(),
                    'nombre' => $usuario->getNombre(),
                    'contrasena' => $usuario->getContrasena(),
                    'carrito' => $usuario->getCarrito(),
                    'monedero' => $usuario->getMonedero(),
                    'foto' => $usuario->getFoto(),
                    'correo' => $usuario->getCorreo(),
                    'telefono' => $usuario->getTelefono(),
                    'ubicacion' => $usuario->getUbicacion(),
                    'alergenos' => $usuario->getAlergenos()
                ];
            } else {
                $response['success'] = false;
                $response['message'] = 'Usuario no encontrado';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Se debe proporcionar un ID de usuario';
        }
        break;

    case 'PUT':
        // Actualizar un usuario existente
        if (isset($_GET['id'])) {
            $data = json_decode(file_get_contents("php://input"), true); // Obtiene los datos JSON del cuerpo de la solicitud

            $usuarioExistente = $repoUsuario->findById($_GET['id']);

            if ($usuarioExistente) {
                // Actualiza los valores del usuario con los datos proporcionados
                $usuarioExistente->setNombre($data['nombre'] ?? $usuarioExistente->getNombre());
                $usuarioExistente->setContrasena($data['contrasena'] ?? $usuarioExistente->getContrasena());
                $usuarioExistente->setCarrito($data['carrito'] ?? $usuarioExistente->getCarrito());
                $usuarioExistente->setMonedero($data['monedero'] ?? $usuarioExistente->getMonedero());
                $usuarioExistente->setFoto($data['foto'] ?? $usuarioExistente->getFoto());
                $usuarioExistente->setCorreo($data['correo'] ?? $usuarioExistente->getCorreo());
                $usuarioExistente->setTelefono($data['telefono'] ?? $usuarioExistente->getTelefono());
                $usuarioExistente->setUbicacion($data['ubicacion'] ?? $usuarioExistente->getUbicacion());

                // Actualizar alérgenos si se proporcionan
                if (isset($data['alergenos'])) {
                    $usuarioExistente->setAlergenos($data['alergenos']);
                }

                $result = $repoUsuario->modificar($usuarioExistente);

                if ($result) {
                    $response['success'] = true;
                    $response['message'] = 'Usuario actualizado correctamente';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Error al actualizar el usuario';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'Usuario no encontrado';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Se debe proporcionar un ID de usuario';
        }
        break;

    case 'DELETE':
        // Eliminar usuario por ID
        if (isset($_GET['id'])) {
            $result = $repoUsuario->borrar($_GET['id']);

            if ($result) {
                $response['success'] = true;
                $response['message'] = 'Usuario eliminado correctamente';
            } else {
                $response['success'] = false;
                $response['message'] = 'Error al eliminar el usuario';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Se debe proporcionar un ID de usuario';
        }
        break;

    default:
        $response['success'] = false;
        $response['message'] = 'Método no soportado';
        break;
}

// Enviar la respuesta en formato JSON
echo json_encode($response);
?>
