<?php
    /*
        Clase para gestionar los usuarios
        
        Métodos:
            findById($id_usuario): Obtener un usuario por su ID
            crear($usuario): Crear un nuevo usuario
            modificar($usuario): Modificar un usuario
            eliminar($id_usuario): Eliminar un usuario por su ID
            mostrarTodos(): Obtener todos los usuarios
            
        TODO: Implementar métodos para gestionar los usuarios (findById, crear, modificar, eliminar, mostrarTodos)
        * Obtener un usuario por su ID: Obtener un usuario por su ID
        * Crear un nuevo usuario: Crear un nuevo usuario
        * Modificar un usuario: Modificar un usuario
        * Eliminar un usuario por su ID: Eliminar un usuario por su ID
        * Obtener todos los usuarios: Obtener todos los usuarios        
    */  



class RepoUsuario
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para obtener un usuario por ID
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE id_usuario = :id";
            $stm = $this->con->prepare($sql);
            $stm->execute(['id' => $id]);
            $registro = $stm->fetch(PDO::FETCH_ASSOC);

            if ($registro) {
                $usuario = new Usuario(
                    $registro['id_usuario'],
                    $registro['nombre'],
                    $registro['contrasena'],
                    $registro['carrito'], // Uso de ?? para evitar errores si la clave no existe
                    $registro['monedero'],
                    $registro['foto'],
                    $registro['telefono'],
                    $registro['ubicacion'],
                    $registro['correo'],
                    $registro['tipo']
                );
                return $usuario;
            } else {
                echo json_encode(["error" => "Usuario no encontrado."]);
                return null;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener el usuario: " . $e->getMessage()]);
            return null;
        }
    }

    public function findByNombreYContrasena($nombre, $contrasena)
    {
        try {
            // Usamos una consulta SQL para obtener el usuario con ese nombre y contraseña
            $sql = "SELECT * FROM usuario WHERE nombre = :nombre AND contrasena = :contrasena";
            $stm = $this->con->prepare($sql);
            $stm->execute(['nombre' => $nombre, 'contrasena' => $contrasena]);
            $registro = $stm->fetch(PDO::FETCH_ASSOC);

            if ($registro) {
                // Si se encuentra, creamos el objeto Usuario y lo retornamos
                $usuario = new Usuario(
                    $registro['id_usuario'],
                    $registro['nombre'],
                    $registro['contrasena'],
                    $registro['carrito'],
                    $registro['monedero'],
                    $registro['foto'],
                    $registro['telefono'],
                    $registro['ubicacion'],
                    $registro['correo'],
                    $registro['tipo']
                );
                return $usuario;
            } else {
                // Si no se encuentra el usuario, retornamos null
                return null;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener el usuario: " . $e->getMessage()]);
            return null;
        }
    }

    public function crear(Usuario $usuario)
    {
        try {
            $carrito = json_encode($usuario->getCarrito());

            $sql = "INSERT INTO usuario (nombre, contrasena, carrito, monedero, foto, telefono, ubicacion, correo, tipo) 
                    VALUES (:nombre, :contrasena, :carrito, :monedero, :foto, :telefono, :ubicacion, :correo, :tipo)";
            $stm = $this->con->prepare($sql);

            $stm->bindValue(':nombre', $usuario->getNombre());
            $stm->bindValue(':contrasena', $usuario->getContrasena());
            $stm->bindValue(':carrito', $carrito);
            $stm->bindValue(':monedero', $usuario->getMonedero());
            $stm->bindValue(':foto', $usuario->getFoto());
            $stm->bindValue(':telefono', $usuario->getTelefono());
            $stm->bindValue(':ubicacion', $usuario->getUbicacion());
            $stm->bindValue(':correo', $usuario->getCorreo());
            $stm->bindValue(':tipo', $usuario->getTipo());

            $resultado = $stm->execute();
            return $resultado;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al crear el usuario: " . $e->getMessage()]);
            return false;
        }
    }


    public function modificar(Usuario $usuario)
{
    try {
        $carrito = json_encode($usuario->getCarrito());
        
        $sql = "UPDATE usuario SET nombre = :nombre, contrasena = :contrasena, carrito = :carrito, 
                monedero = :monedero, foto = :foto, telefono = :telefono, ubicacion = :ubicacion, correo = :correo, tipo = :tipo
                WHERE id_usuario = :id";
        $stm = $this->con->prepare($sql);

        // Vinculación de variables
        $stm->bindValue(':nombre', $usuario->getNombre());
        $stm->bindValue(':contrasena', $usuario->getContrasena());
        $stm->bindValue(':carrito', $carrito);
        $stm->bindValue(':monedero', $usuario->getMonedero());
        $stm->bindValue(':foto', $usuario->getFoto());
        $stm->bindValue(':telefono', $usuario->getTelefono());
        $stm->bindValue(':ubicacion', $usuario->getUbicacion());
        $stm->bindValue(':correo', $usuario->getCorreo());
        $stm->bindValue(':tipo', $usuario->getTipo());
        $stm->bindValue(':id', $usuario->getIdUsuario(), PDO::PARAM_INT);

        // Ejecución de la consulta
        return $stm->execute(); // Aquí estaba el problema
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error al modificar el usuario: " . $e->getMessage()]);
        return false;
    }
}

    // Método para actualizar la relación de alérgenos de un usuario
    public function actualizarRelacionAlergenos(Usuario $usuario)
    {
        $idUsuario = $usuario->getIdUsuario();
        
        // Primero eliminamos todas las relaciones actuales del usuario
        $sqlDelete = "DELETE FROM usuario_tiene_alergenos WHERE usuario_id_usuario = :id_usuario";
        $stmDelete = $this->con->prepare($sqlDelete);
        $stmDelete->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmDelete->execute();

        // Luego agregamos las nuevas relaciones
        $sqlInsert = "INSERT INTO usuario_tiene_alergenos (usuario_id_usuario, alergenos_id_alergenos) VALUES (:id_usuario, :alergeno_id)";
        $stmInsert = $this->con->prepare($sqlInsert);

        foreach ($usuario->getAlergenos() as $alergenoId) {
            $stmInsert->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmInsert->bindParam(':alergeno_id', $alergenoId, PDO::PARAM_INT);
            $stmInsert->execute();
        }
    }



    // Método para agregar la relación entre un usuario y un alérgeno
    private function agregarRelacionUsuarioAlergeno($usuario_id, $alergeno_id)
    {
        $sql = "INSERT INTO usuario_tiene_alergenos (usuario_id_usuario, alergenos_id_alergenos) 
                VALUES (:usuario_id_usuario, :alergeno_id)";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':usuario_id_usuario', $usuario_id, PDO::PARAM_INT);
        $stm->bindParam(':alergeno_id', $alergeno_id, PDO::PARAM_INT);
        $stm->execute();
    }

    // Método para mostrar todos los usuarios con carrito y alérgenos
    public function mostrarTodos()
    {
        try {
            $sql = "SELECT * FROM usuario";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

            $usuarios = [];
            foreach ($registros as $registro) {
                $usuario = new Usuario(
                    $registro['id_usuario'],
                    $registro['nombre'],
                    $registro['contrasena'],
                    $registro['carrito'],
                    $registro['monedero'],
                    $registro['foto'],
                    $registro['telefono'],
                    $registro['ubicacion'],
                    $registro['correo'],
                    $registro['tipo']
                );
            }
            return $usuarios;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al mostrar los usuarios: " . $e->getMessage()]);
        }
    }

    // Método para buscar alérgenos asociados a un usuario
    private function findAlergenosByUsuarioId($id_usuario)
    {
        $alergenos = [];
        $sql = "SELECT a.* FROM alergenos a
                INNER JOIN usuario_tiene_alergenos ua ON a.id_alergenos = ua.alergenos_id_alergenos
                WHERE ua.usuario_id_usuario = :id_usuario";
        $stm = $this->con->prepare($sql);
        $stm->execute(['id_usuario' => $id_usuario]);
        $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($registros as $registro) {
            $alergenos[] = new Alergenos(
                $registro['id_alergenos'], 
                $registro['nombre'], 
                $registro['foto'] ?? '',          // Usa ?? para evitar errores si falta la clave
                $registro['ingredientes'] ?? '',  // Usa ?? aquí también
                $registro['usuarios'] ?? ''       // Y aquí
            );
        }
        return $alergenos;
    }
    
    // Método para eliminar un usuario
    public function eliminarUsuario($id_usuario)
    {
        try {
                    $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stm->execute();

            // Comprobar si se eliminó alguna fila
            if ($stm->rowCount() > 0) {
                return true;  // Usuario eliminado correctamente
            } else {
                return false; // No se encontró el usuario o no se pudo eliminar
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al eliminar el usuario: " . $e->getMessage()]);
            return false;
        }
    }
}
