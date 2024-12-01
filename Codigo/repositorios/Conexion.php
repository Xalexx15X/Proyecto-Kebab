<?php
    /*
        Clase para gestionar la conexión a la base de datos
        
        Métodos:
            getConection(): Obtener la conexión a la base de datos
            
        TODO: Implementar métodos para gestionar la conexión a la base de datos (getConection)
        * Obtener la conexión a la base de datos: Obtener la conexión a la base de datos
    */


class Conexion {
    // Variable estática para almacenar la conexión
    private static $con;

    // Método estático para obtener la conexión
    public static function getConection(): PDO {
        // Si no existe una conexión, crearla
        if (self::$con == null) {
            try {
                // Configuración de la conexión usando PDO
                self::$con = new PDO(
                    "mysql:host=localhost;dbname=tiendadekebab", // DSN
                    'root', // Usuario
                    'root', // Contraseña
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Modo de error como excepción
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC  // Modo de obtención de resultados por defecto
                    ]
                );
            } catch (PDOException $e) {
                // Manejo de errores de conexión
                die("Error en la conexión: " . $e->getMessage());
            }
        }
        
        // devuelvo la conexión
        return self::$con;
    }
}
?>
