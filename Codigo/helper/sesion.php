<?php
class Sesion
{
    // Iniciar la sesión
    public static function iniciar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Inicia la sesión si no está ya activa
        }
    }

    // Leer un valor de la sesión por su clave
    public static function leer(string $clave)
    {
        self::iniciar(); // Asegúrate de que la sesión está iniciada
        return isset($_SESSION[$clave]) ? $_SESSION[$clave] : null; // Devuelve el valor o null si no existe
    }

    // Verificar si una clave existe en la sesión
    public static function existe(string $clave)
    {
        self::iniciar(); // Asegúrate de que la sesión está iniciada
        return isset($_SESSION[$clave]); // Retorna true si existe, false si no
    }

    // Escribir un valor en la sesión
    public static function escribir($clave, $valor)
    {
        self::iniciar(); // Asegúrate de que la sesión está iniciada
        $_SESSION[$clave] = $valor; // Establece el valor en la sesión
    }

    // Eliminar un valor de la sesión
    public static function eliminar($clave)
    {
        self::iniciar(); // Asegúrate de que la sesión está iniciada
        if (self::existe($clave)) {
            unset($_SESSION[$clave]); // Elimina la clave de la sesión si existe
        }
    }
    // Cerrar la sesión
    public static function cerrar()
    {
        self::iniciar(); // Asegúrate de que la sesión está iniciada
        session_unset(); // Limpia todas las variables de sesión
        session_destroy(); // Destruye la sesión
    }
}
?>
