<?php

class RepoUsuario
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar un usuario por ID, incluyendo la ubicación y alérgenos
    public function findById($id)
    {
        $stm = $this->con->prepare("select * from usuario where id_usuario = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            // Crear el usuario con los datos de la base de datos
            $usuario = new Usuario(
                $registro['id_usuario'],
                $registro['nombre'],
                $registro['contraseña'],
                $registro['direccion'],
                $registro['carrito'],
                $registro['monedero'],
                $registro['foto'],
                $registro['correo'],
                $registro['telefono'],
                $registro['ubicacion']
            );

            // Cargar alérgenos asociados al usuario
            $usuario->setAlergenos($this->findAlergenosByUsuarioId($registro['id_usuario']));

            return $usuario;
        }
        return null;
    }

    // Método para buscar los alérgenos asociados a un usuario
    private function findAlergenosByUsuarioId($id_usuario)
    {
        $alergenos = [];
        $stm = $this->con->prepare("select a.* from alergenos a
                                    inner join usuario_tiene_alergenos ua on a.id_alergeno = ua.alergeno_id
                                    where ua.usuario_id = :id_usuario");
        $stm->execute(['id_usuario' => $id_usuario]);
        $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($registros as $registro) {
            $alergenos[] = new Alergenos($registro['id_alergeno'], $registro['nombre']);
        }
        return $alergenos;
    }

    // Método para crear un usuario
    public function crear(Usuario $usuario)
    {
        try {
            $sql = "insert into usuario (nombre, contraseña, direccion, carrito, monedero, foto, correo, telefono, ubicacion) values (:nombre, :contraseña, :direccion, :carrito, :monedero, :foto, :correo, :telefono, :ubicacion)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $usuario->getNombre());
            $stm->bindParam(':contraseña', $usuario->getContrasena());
            $stm->bindParam(':direccion', $usuario->getDireccion());
            $stm->bindParam(':carrito', $usuario->getCarrito());
            $stm->bindParam(':monedero', $usuario->getMonedero());
            $stm->bindParam(':foto', $usuario->getFoto());
            $stm->bindParam(':correo', $usuario->getCorreo());
            $stm->bindParam(':telefono', $usuario->getTelefono());
            $stm->bindParam(':ubicacion', $usuario->getUbicacion());
            $stm->execute();

            // Obtener el ID del usuario recién creado
            $usuario_id = $this->con->lastInsertId();

            // Asociar alérgenos al usuario
            foreach ($usuario->getAlergenos() as $alergeno) {
                $this->agregarRelacionUsuarioAlergeno($usuario_id, $alergeno->getIdAlergenos());
            }

            return true;
        } catch (PDOException $e) {
            return "Error al crear el usuario: " . $e->getMessage();
        }
    }

    // Método para agregar la relación entre un usuario y un alérgeno
    private function agregarRelacionUsuarioAlergeno($usuario_id, $alergeno_id)
    {
        $sql = "insert into usuario_tiene_alergenos (usuario_id, alergeno_id) values (:usuario_id, :alergeno_id)";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':usuario_id', $usuario_id);
        $stm->bindParam(':alergeno_id', $alergeno_id);
        $stm->execute();
    }

    // Método para modificar un usuario existente
    public function modificar(Usuario $usuario)
    {
        try {
            $sql = "update usuario set nombre = :nombre, contraseña = :contraseña, direccion = :direccion, carrito = :carrito, monedero = :monedero, foto = :foto, correo = :correo, telefono = :telefono, ubicacion = :ubicacion WHERE id_usuario = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $usuario->getNombre());
            $stm->bindParam(':contraseña', $usuario->getContrasena());
            $stm->bindParam(':direccion', $usuario->getDireccion());
            $stm->bindParam(':carrito', $usuario->getCarrito());
            $stm->bindParam(':monedero', $usuario->getMonedero());
            $stm->bindParam(':foto', $usuario->getFoto());
            $stm->bindParam(':correo', $usuario->getCorreo());
            $stm->bindParam(':telefono', $usuario->getTelefono());
            $stm->bindParam(':ubicacion', $usuario->getUbicacion());
            $stm->bindParam(':id', $usuario->getIdUsuario(), PDO::PARAM_INT);
            $stm->execute();

            // Actualizar alérgenos asociados al usuario
            $this->actualizarRelacionAlergenos($usuario);

            return true;
        } catch (PDOException $e) {
            return "Error al modificar el usuario: " . $e->getMessage();
        }
    }

    // Método para actualizar la relación de alérgenos de un usuario
    private function actualizarRelacionAlergenos(Usuario $usuario)
    {
        $sql = "delete from usuario_tiene_alergenos where usuario_id = :id_usuario";
        $stm = $this->con->prepare($sql);
        $stm->bindParam(':id_usuario', $usuario->getIdUsuario());
        $stm->execute();

        foreach ($usuario->getAlergenos() as $alergeno) {
            $this->agregarRelacionUsuarioAlergeno($usuario->getIdUsuario(), $alergeno->getIdAlergenos());
        }
    }

    // Método para borrar un usuario por ID y sus relaciones con alérgenos
    public function borrar($id)
    {
        try {
            // Eliminar relaciones con alérgenos
            $sql = "delete from usuario_tiene_alergenos where usuario_id = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            // Eliminar el usuario
            $sql = "delete from usuario where id_usuario = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            return true;
        } catch (PDOException $e) {
            return "Error al borrar el usuario: " . $e->getMessage();
        }
    }

    // Método para mostrar todos los usuarios, incluyendo sus alérgenos
    public function mostrarTodos()
    {
        try {
            $sql = "select * from usuario";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);

            $usuarios = [];
            foreach ($registros as $registro) {
                $usuario = new Usuario(
                    $registro['id_usuario'],
                    $registro['nombre'],
                    $registro['contraseña'],
                    $registro['direccion'],
                    $registro['carrito'],
                    $registro['monedero'],
                    $registro['foto'],
                    $registro['correo'],
                    $registro['telefono'],
                    $registro['ubicacion']
                );

                // Cargar alérgenos para cada usuario
                $usuario->setAlergenos($this->findAlergenosByUsuarioId($registro['id_usuario']));
                $usuarios[] = $usuario;
            }
            return $usuarios;
        } catch (PDOException $e) {
            return "Error al mostrar los usuarios: " . $e->getMessage();
        }
    }
}
?>
