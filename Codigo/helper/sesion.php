<?php
class Sesion {
    // Inicia la sesión, se debe llamar al principio de cada página
    public static function iniciar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Escribe un valor en la sesión
    public static function escribir($clave, $valor) {
        $_SESSION[$clave] = $valor;
    }

    // Lee un valor de la sesión
    public static function leer($clave) {
        if (self::existe($clave)) {
            return $_SESSION[$clave];
        }
        return null;
    }

    // Verifica si una clave existe en la sesión
    public static function existe($clave) {
        return isset($_SESSION[$clave]);
    }

    // Elimina una clave de la sesión
    public static function eliminar($clave) {
        if (self::existe($clave)) {
            unset($_SESSION[$clave]);
        }
    }

    // Cierra la sesión
    public static function cerrar() {
        session_unset();
        session_destroy();
    }
}
?>
