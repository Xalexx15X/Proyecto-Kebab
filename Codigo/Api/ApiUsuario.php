<?php
/*
    Ruta para gestionar usuarios
    
    Métodos:
        GET: Obtener todos los usuarios
        POST: Crear un nuevo usuario
        PUT: Modificar un usuario
        DELETE: Eliminar un usuario

@param $input: JSON con los datos del usuario
@param $result: JSON con los datos del usuario
@param $usuario: Objeto con los datos del usuario
@param $usuarioCreado: Objeto con los datos del usuario creado  

TODO: Implementar métodos para gestionar los usuarios (crear, modificar, eliminar)
* Crear: Crear un nuevo usuario
* Modificar: Modificar un usuario
* Eliminar: Eliminar un usuario
* Obtener todos los usuarios: Obtener todos los usuarios
*/


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
        }
        break;        
        case 'POST':
            if (isset($input['nombre'], $input['contrasena'], $input['carrito'], $input['monedero'], $input['foto'], $input['telefono'], $input['ubicacion'], $input['correo'], $input['tipo'])) {
                // Crear un nuevo usuario si los parámetros están completos
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
                    // Buscar al usuario recién creado para obtener su ID
                    $usuarioCreado = $repoUsuario->findByNombreYContrasena($input['nombre'], $input['contrasena']);
        
                    // Iniciar sesión usando la clase `Sesion`
                    Sesion::iniciar();
                    Sesion::escribir('usuario', [
                        'id' => $usuarioCreado->id_usuario,
                        'nombre' => $usuarioCreado->nombre,
                        'tipo' => $usuarioCreado->tipo,
                    ]);
        
                    http_response_code(201); // Created
                    echo json_encode(["success" => true, "mensaje" => "Usuario creado correctamente."]);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(["error" => "Error al crear el usuario."]);
                }
            }
            // Si no se proporcionan todos los datos para crear un usuario, intentamos realizar un login
            elseif (isset($input['nombre'], $input['contrasena'])) {
                // Buscar el usuario con las credenciales proporcionadas
                $nombre = $input['nombre'];
                $contrasena = $input['contrasena'];
        
                // Verificar las credenciales
                $usuario = $repoUsuario->findByNombreYContrasena($nombre, $contrasena);
        
                if ($usuario) {
                    // Iniciar sesión usando la clase `Sesion`
                    Sesion::iniciar();
                    Sesion::escribir('usuario', [
                        'id' => $usuario->id_usuario,
                        'nombre' => $usuario->nombre,
                        'contrasena' => $usuario->contrasena,
                        'carrito' => $usuario->carrito,
                        'monedero' => $usuario->monedero,
                        'foto' => $usuario->foto,
                        'telefono' => $usuario->telefono,
                        'ubicacion' => $usuario->ubicacion,
                        'correo' => $usuario->correo,
                        'tipo' => $usuario->tipo,
                    ]);
        
                    http_response_code(200); // OK
                    echo json_encode($usuario); // Enviar los datos completos del usuario al frontend
                } else {
                    http_response_code(401); // Unauthorized
                    echo json_encode(["error" => "Credenciales incorrectas."]);
                }
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(["error" => "Faltan los parámetros necesarios."]);
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
    case 'SESSION_DESTROY':
        // Iniciar sesión usando la clase `Sesion`
        Sesion::iniciar();
        if (Sesion::existe('usuario')) {
            Sesion::cerrar();
            http_response_code(200);
            echo json_encode(["success" => true, "mensaje" => "Sesión cerrada correctamente."]);
        } else {    
            http_response_code(401); // Unauthorized
            echo json_encode(["error" => "No hay una sesión activa."]);
        }
        break;
    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Método no soportado."]);
        break;
}
?>
