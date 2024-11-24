<?php
    /*
        Clase para gestionar la sesión
        
        Métodos:
            iniciar(): Inicia la sesión
            escribir($clave, $valor): Escribe un valor en la sesión
            leer($clave): Lee un valor de la sesión
            existe($clave): Verifica si una clave existe en la sesión
            eliminar($clave): Elimina una clave de la sesión
            cerrar(): Cierra la sesión
            
        TODO: Implementar métodos para gestionar la sesión (iniciar, escribir, leer, existe, eliminar, cerrar)
        * Iniciar: Inicia la sesión
        * Escribir: Escribe un valor en la sesión
        * Leer: Lee un valor de la sesión
        * Existe: Verifica si una clave existe en la sesión
        * Eliminar: Elimina una clave de la sesión
        * Cerrar: Cierra la sesión
    */


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
