<?php
class RepoUsuario
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para encontrar un usuario por ID
    public function findById($id)
    {
        $stm = $this->con->prepare("SELECT * FROM usuario WHERE id_usuario = :id");
        $stm->execute(['id' => $id]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            return new Usuario($registro['id_usuario'], $registro['nombre'], $registro['contraseña'], $registro['direccion'], $registro['carrito'], $registro['monedero'], $registro['foto'], $registro['correo'], $registro['telefono']);
        }
        return null;
    }

    // Método para buscar un usuario por nombre y verificar la contraseña
    public function findByUsuarioYContraseña($usuario, $contrasena)
    {
        $stm = $this->con->prepare("SELECT * FROM usuario WHERE nombre = :nombre");
        $stm->execute(['nombre' => $usuario]);
        $registro = $stm->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            // Verifica la contraseña usando password_verify
            if (password_verify($contrasena, $registro['contraseña'])) {
                return new Usuario($registro['id_usuario'], $registro['nombre'], $registro['contraseña'], $registro['direccion'], $registro['carrito'], $registro['monedero'], $registro['foto'], $registro['correo'], $registro['telefono']);
            }
        }
        return null; // Retorna null si no se encontró el usuario o la contraseña es incorrecta
    }

    // Método para crear un usuario
    public function crear(Usuario $usuario)
    {
        try {
            $sql = "INSERT INTO usuario (nombre, contraseña, direccion, carrito, monedero, foto, correo, telefono) VALUES (:nombre, :contraseña, :direccion, :carrito, :monedero, :foto, :correo, :telefono)";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $usuario->nombre);
            $stm->bindParam(':contraseña', $usuario->contraseña);
            $stm->bindParam(':direccion', $usuario->direccion);
            $stm->bindParam(':carrito', $usuario->carrito);
            $stm->bindParam(':monedero', $usuario->monedero);
            $stm->bindParam(':foto', $usuario->foto);
            $stm->bindParam(':correo', $usuario->correo);
            $stm->bindParam(':telefono', $usuario->telefono);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al crear el usuario: " . $e->getMessage();
        }
    }

    // Método para modificar un usuario existente
    public function modificar(Usuario $usuario)
    {
        try {
            $sql = "UPDATE usuario SET nombre = :nombre, contraseña = :contraseña, direccion = :direccion, carrito = :carrito, monedero = :monedero, foto = :foto, correo = :correo, telefono = :telefono WHERE id_usuario = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':nombre', $usuario->nombre);
            $stm->bindParam(':contraseña', $usuario->contraseña);
            $stm->bindParam(':direccion', $usuario->direccion);
            $stm->bindParam(':carrito', $usuario->carrito);
            $stm->bindParam(':monedero', $usuario->monedero);
            $stm->bindParam(':foto', $usuario->foto);
            $stm->bindParam(':correo', $usuario->correo);
            $stm->bindParam(':telefono', $usuario->telefono);
            $stm->bindParam(':id', $usuario->id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al modificar el usuario: " . $e->getMessage();
        }
    }

    // Método para borrar un usuario por ID
    public function borrar($id)
    {
        try {
            $sql = "DELETE FROM usuario WHERE id_usuario = :id";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return "Error al borrar el usuario: " . $e->getMessage();
        }
    }

    // Método para mostrar todos los usuarios
    public function mostrarTodos()
    {
        try {
            $sql = "SELECT * FROM usuario";
            $stm = $this->con->prepare($sql);
            $stm->execute();
            $registros = $stm->fetchAll(PDO::FETCH_ASSOC);
            $usuarios = [];

            foreach ($registros as $registro) {
                $usuarios[] = new Usuario($registro['id_usuario'], $registro['nombre'], $registro['contraseña'], $registro['direccion'], $registro['carrito'], $registro['monedero'], $registro['foto'], $registro['correo'], $registro['telefono']);
            }
            return $usuarios;
        } catch (PDOException $e) {
            return "Error al mostrar los usuarios: " . $e->getMessage();
        }
    }
}
?>
