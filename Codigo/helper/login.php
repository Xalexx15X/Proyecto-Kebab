<?php
class Login
{
    private static $repoUsuario;

    // Establece el repositorio de usuarios
    public static function setRepoUsuario(RepoUsuario $repo)
    {
        self::$repoUsuario = $repo;
    }

    // Método para identificar al usuario
    public static function Identifica(string $usuario, string $contrasena, bool $recuerdame)
    {
        // Verificar si el usuario existe y la contraseña es correcta
        return self::ExisteUsuario($usuario, $contrasena, $recuerdame);
    }

    // Método para verificar si existe el usuario
    private static function ExisteUsuario(string $usuario, string $contrasena = null)
    {
        $usuarioEncontrado = self::$repoUsuario->findByUsuarioYContraseña($usuario, $contrasena);
        
        if ($usuarioEncontrado) {
            // Si el usuario y la contraseña son correctos, se pueden iniciar sesión
            $_SESSION['usuario'] = $usuario; // Almacena el usuario en la sesión
            if ($recuerdame) {
                // Si se solicita recordar al usuario, establecer una cookie
                setcookie('usuario', $usuario, time() + (86400 * 30), "/"); // Cookie válida por 30 días
            }
            return true; // Inicio de sesión exitoso
        } else {
            return false; // Fallo en el inicio de sesión
        }
    }

    // Método para verificar si el usuario está logueado
    public static function UsuarioEstaLogueado()
    {
        return isset($_SESSION['usuario']);
    }
}
?>
