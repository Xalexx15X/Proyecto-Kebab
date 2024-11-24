<?php
    /*
        Clase para cargar automáticamente las clases y repositorios
        
        Métodos:
            autocargar(): Carga automáticamente las clases y repositorios
            autocarga($nombreClase): Carga una clase o repositorio
            
        TODO: Implementar métodos para cargar automáticamente las clases y repositorios (autocargar, autocarga)
        * Carga automáticamente las clases y repositorios: Carga automáticamente las clases y repositorios
        * Carga una clase o repositorio: Carga una clase o repositorio  
        
    */

class Autocargador
{
    public static function autocargar()
    {
        spl_autoload_register([self::class, 'autocarga']);
    }

    public static function autocarga($nombreClase)
    {
        // Definir las rutas donde están las clases y repositorios
        $Carpetas = [
            './repositorios/',
            './Clases/',
            './Vistas/',
            './Api/',
            './helper/',
        ];

        foreach ($Carpetas as $Carpetas) {
            $archivo = $Carpetas . $nombreClase . '.php';
            if (file_exists($archivo)) {
                require_once $archivo;
                return;
            }
        }

        // Si la clase no se encontró, puedes lanzar un error (opcional)
        throw new Exception("No se pudo cargar la clase: $nombreClase");
    }
}

Autocargador::autocargar();
