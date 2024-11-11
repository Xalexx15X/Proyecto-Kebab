<?php
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
                    $registro['carrito'],
                    $registro['monedero'],
                    $registro['foto'],
                    $registro['telefono'],
                    $registro['ubicacion']
                );
                
                // Cargar alérgenos para cada usuario
                $usuario->setAlergenos($this->findAlergenosByUsuarioId($registro['id_usuario']));
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

    // Método para crear un usuario
    public function crear(Usuario $usuario)
    {
        try {
            // Convertimos el carrito a JSON en caso de que sea un array
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

            if ($stm->execute()) {
                $usuario_id = $this->con->lastInsertId();
                $usuario->setIdUsuario($usuario_id);

                // Asociar alérgenos
                foreach ($usuario->getAlergenos() as $alergeno_id) {
                    $this->agregarRelacionUsuarioAlergeno($usuario_id, $alergeno_id);
                }

                return true;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al crear el usuario: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para modificar un usuario
    public function modificar(Usuario $usuario)
    {
        try {
            $sql = "UPDATE usuario SET nombre = :nombre, contrasena = :contrasena, carrito = :carrito, 
                    monedero = :monedero, foto = :foto, telefono = :telefono, ubicacion = :ubicacion, correo = :correo, tipo = :tipo
                    WHERE id_usuario = :id";
            $stm = $this->con->prepare($sql);

            $stm->bindParam(':nombre', $usuario->getNombre());
            $stm->bindParam(':contrasena', $usuario->getContrasena());
            $stm->bindParam(':carrito', json_encode($usuario->getCarrito()));
            $stm->bindParam(':monedero', $usuario->getMonedero());
            $stm->bindParam(':foto', $usuario->getFoto());
            $stm->bindParam(':telefono', $usuario->getTelefono());
            $stm->bindParam(':ubicacion', $usuario->getUbicacion());
            $stm->bindParam(':correo', $usuario->getCorreo());
            $stm->bindParam(':tipo', $usuario->getTipo());
            $stm->bindParam(':id', $usuario->getIdUsuario(), PDO::PARAM_INT);

            if ($stm->execute()) {
                // Actualizar alérgenos asociados
                $this->actualizarRelacionAlergenos($usuario);
                return true;
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al modificar el usuario: " . $e->getMessage()]);
            return false;
        }
    }

    // Método para actualizar la relación de alérgenos de un usuario
    private function actualizarRelacionAlergenos(Usuario $usuario)
    {
        if (!empty($usuario->getAlergenos())) {
            // Eliminar alérgenos actuales
            $sql = "DELETE FROM usuario_tiene_alergenos WHERE usuario_id_usuario = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $usuario->getIdUsuario(), PDO::PARAM_INT);
            $stm->execute();

            // Agregar los nuevos alérgenos
            foreach ($usuario->getAlergenos() as $alergenos) {
                // Aquí ya puedes trabajar con el objeto Alergeno, no con solo el ID
                $this->agregarRelacionUsuarioAlergeno($usuario->getIdUsuario(), $alergenos->getIdAlergenos());
            }            
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
                    $registro['ubicacion']
                );

                // Cargar alérgenos para cada usuario
                $usuario->setAlergenos($this->findAlergenosByUsuarioId($registro['id_usuario']));
                $usuarios[] = $usuario;
            }
            return $usuarios;
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al mostrar los usuarios: " . $e->getMessage()]);
            return [];
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
        // Si la descripción no está en la base de datos, pasa una cadena vacía
        $alergenos[] = new Alergenos(
            $registro['id_alergenos'], 
            $registro['nombre'], 
            $registro['foto'], 
            $registro['ingredientes'], 
            $registro['usuarios']
        );
    }
    return $alergenos;
}
// Método para eliminar un usuario
public function eliminarUsuario($id_usuario)
{
    try {
        // Primero eliminamos las relaciones del usuario con los alérgenos
        $sql = "DELETE FROM usuario_tiene_alergenos WHERE usuario_id_usuario = :id_usuario";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stm->execute();

        // Luego eliminamos el usuario de la tabla 'usuario'
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
